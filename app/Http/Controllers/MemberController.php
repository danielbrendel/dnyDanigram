<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace App\Http\Controllers;

use App\AppModel;
use App\CaptchaModel;
use App\FavoritesModel;
use App\IgnoreModel;
use App\PostModel;
use App\ReportModel;
use App\TagsModel;
use App\User;
use App\ProfileModel;
use App\ProfileDataModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class MemberController extends Controller
{
    /**
     * Validate permissions
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            if ((!env('APP_PUBLICFEED')) && (Auth::guest())) {
                return redirect('/');
            }

            return $next($request);
        });
    }

    /**
     * Show profile
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $user = User::get($id);
            if (!$user) {
                $user = User::getByUsername($id);
            }
            if ((!$user) || ($user->deactivated)) {
                return redirect('/')->with('flash.error', __('app.user_not_found_or_deactivated'));
            }

            $user->stats = User::getStats($user->id);
            $user->ignored = IgnoreModel::hasIgnored(auth()->id(), $user->id);

            $user->genderStr = __('app.gender_unspecified');
            if ($user->gender === 1) {
                $user->genderStr = __('app.gender_male');
            } else if ($user->gender === 2) {
                $user->genderStr = __('app.gender_female');
            } else if ($user->gender === 3) {
                $user->genderStr = __('app.gender_diverse');
            }

            $user->age = Carbon::parse($user->birthday)->age;

            return view('member.profile', [
                'user' => User::getByAuthId(),
                'profile' => $user,
                'taglist' => TagsModel::getPopularTags(),
                'favorited' => FavoritesModel::hasUserFavorited(auth()->id(), $user->id, 'ENT_USER'),
                'captcha' => CaptchaModel::createSum(session()->getId()),
                'cookie_consent' => AppModel::getCookieConsentText(),
                'meta_description' => str_replace(PHP_EOL, ' ', $user->bio),
                'profile_data' => ProfileDataModel::queryAll(auth()->id())
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Redirect to own profile
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function profile()
    {
        return redirect('/u/' . auth()->id());
    }

    public function save()
    {
        try {
            $attr = request()->validate([
               'username' => 'nullable',
               'bio' => 'nullable',
               'gender' => 'required|numeric|min:0|max:3',
               'birthday' => 'nullable|date',
               'location' => 'nullable',
               'password' => 'nullable',
               'password_confirm' => 'nullable',
               'email' => 'nullable|email',
                'email_on_message' => 'nullable|numeric',
                'newsletter' => 'nullable|numeric',
                'nsfw' => 'nullable',
                'geo_exclude' => 'nullable'
            ]);

            if (isset($attr['username'])) {
                User::changeUsername(auth()->id(), $attr['username']);
            }

            if (isset($attr['bio'])) {
                User::changeBio(auth()->id(), $attr['bio']);
            }

            if (isset($attr['gender'])) {
                User::saveGender(auth()->id(), $attr['gender']);
            }

            if (isset($attr['birthday'])) {
                User::saveBirthday(auth()->id(), $attr['birthday']);
            }

            if (isset($attr['location'])) {
                User::saveLocation(auth()->id(), $attr['location']);
            }

            if (isset($attr['password'])) {
                if ($attr['password'] !== $attr['password_confirm']) {
                    return back()->with('error', __('app.password_mismatch'));
                }

                User::changePassword(auth()->id(), $attr['password']);
            }

            if (isset($attr['email'])) {
                User::changeEMail(auth()->id(), $attr['email']);
            }

            if (!isset($attr['email_on_message'])) {
                $attr['email_on_message'] = false;
            }

            if (!isset($attr['newsletter'])) {
                $attr['newsletter'] = false;
            }

            if (!isset($attr['nsfw'])) {
                $attr['nsfw'] = false;
            }

            if (!isset($attr['geo_exclude'])) {
                $attr['geo_exclude'] = false;
            }

            User::saveEmailOnMessageFlag(auth()->id(), (bool)$attr['email_on_message']);
            User::saveNewsletterFlag(auth()->id(), (bool)$attr['newsletter']);
            User::saveNsfwFlag(auth()->id(), (bool)$attr['nsfw']);
            User::saveGeoExcludeFlag(auth()->id(), (bool)$attr['geo_exclude']);

            $profileItemList = ProfileModel::getList();
            foreach ($profileItemList as $listItem) {
                $curRequestData = request($listItem->name, '');
                ProfileDataModel::addOrEdit(auth()->id(), $listItem->name, $curRequestData);
            }

            $av = request()->file('avatar');
            if ($av != null) {
                $tmpName = md5(random_bytes(55));

                $av->move(base_path() . '/public/gfx/avatars', $tmpName . '.' . $av->getClientOriginalExtension());

                list($width, $height) = getimagesize(base_path() . '/public/gfx/avatars/' . $tmpName . '.' . $av->getClientOriginalExtension());

                $avimg = imagecreatetruecolor(64, 64);
                if (!$avimg)
                    throw new \Exception('imagecreatetruecolor() failed');

                $srcimage = null;
                $newname =  md5_file(base_path() . '/public/gfx/avatars/' . $tmpName . '.' . $av->getClientOriginalExtension()) . '.' . $av->getClientOriginalExtension();
                switch (AppModel::getImageType(base_path() . '/public/gfx/avatars/' . $tmpName . '.' . $av->getClientOriginalExtension())) {
                    case IMAGETYPE_PNG:
                        $srcimage = imagecreatefrompng(base_path() . '/public/gfx/avatars/' . $tmpName . '.' . $av->getClientOriginalExtension());
                        imagecopyresampled($avimg, $srcimage, 0, 0, 0, 0, 64, 64, $width, $height);
                        imagepng($avimg, base_path() . '/public/gfx/avatars/' . $newname);
                        break;
                    case IMAGETYPE_JPEG:
                        $srcimage = imagecreatefromjpeg(base_path() . '/public/gfx/avatars/' . $tmpName . '.' . $av->getClientOriginalExtension());
                        imagecopyresampled($avimg, $srcimage, 0, 0, 0, 0, 64, 64, $width, $height);
                        imagejpeg($avimg, base_path() . '/public/gfx/avatars/' . $newname);
                        break;
                    default:
                        return back()->with('error', __('app.settings_avatar_invalid_image_type'));
                        break;
                }

                unlink(base_path() . '/public/gfx/avatars/' . $tmpName . '.' . $av->getClientOriginalExtension());

                $user = User::get(auth()->id());
                $user->avatar = $newname;
                $user->save();
            }

            return back()->with('flash.success', __('app.profile_saved'));
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }

    /**
     * Report a user
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function report($id)
    {
        try {
            $user = User::get($id);
            if (!$user) {
                return response()->json(array('code' => 404, 'msg' => __('app.user_not_found')));
            }

            ReportModel::addReport(auth()->id(), $user->id, 'ENT_USER');

            return response()->json(array('code' => 200, 'msg' => __('app.user_reported')));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Delete own user account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteOwnAccount()
    {
        try {
            $pw = request('password');

            $user = User::get(auth()->id());
            if ((!$user) || (!Hash::check($pw, $user->password))) {
                return response()->json(array('code' => 403, 'msg' => __('app.invalid_password')));
            }

            AppModel::deleteEntity(auth()->id(), 'ENT_USER');

            Auth::logout();
            request()->session()->invalidate();

            return response()->json(array('code' => 200, 'msg' => __('app.account_deleted')));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Add to ignore list
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToIgnore($id)
    {
        try {
            IgnoreModel::add(auth()->id(), $id);
            FavoritesModel::remove(auth()->id(), $id, 'ENT_USER');

            return back()->with('flash.success', __('app.added_to_ignore'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove from ignore list
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFromIgnore($id)
    {
        try {
            IgnoreModel::remove(auth()->id(), $id);

            return back()->with('flash.success', __('app.removed_from_ignore'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store member geo position
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveGeoLocation()
    {
        try {
            $latitude = request('latitude', null);
            $longitude = request('longitude', null);

            User::storeGeoLocation($latitude, $longitude);

            return response()->json(array('code' => 200));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * View geosearch page
     * 
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function viewGeosearch()
    {
        if (Auth::guest()) {
            return redirect('/');
        }

        $user = User::getByAuthId();
        if ($user) {
            $user->stats = User::getStats($user->id);
        }

        return view('member.geosearch', [
            'user' => $user,
            'taglist' => TagsModel::getPopularTags(),
            'captcha' => CaptchaModel::createSum(session()->getId()),
            'cookie_consent' => AppModel::getCookieConsentText()
        ]);
    }

    /**
     * Perform geosearch and return user list if found any
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function geosearch()
    {
        try {
            $distance = request('distance', env('APP_GEOMAX', 150));
            $paginate = request('paginate', null);

            $data = User::findWithinRange($distance, $paginate);

            return response()->json(array('code' => 200, 'data' => $data, 'max_distance' => $distance));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * View profile search page
     * 
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function viewProfileSearch()
    {
        if (Auth::guest()) {
            return redirect('/');
        }

        $user = User::getByAuthId();
        if ($user) {
            $user->stats = User::getStats($user->id);
        }

        return view('member.profilesearch', [
            'user' => $user,
            'taglist' => TagsModel::getPopularTags(),
            'captcha' => CaptchaModel::createSum(session()->getId()),
            'cookie_consent' => AppModel::getCookieConsentText()
        ]);
    }

     /**
     * Perform profile search and return user list if found any
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function profilesearch()
    {
        try {
            $username = request('username', '');
            $bio = request('bio', '');
            $age_from = request('age_from', null);
            $age_till = request('age_till', null);
            $gender = request('gender', 0);
            $location = request('location', '');
            $paginate = request('paginate', null);

            if ($age_from === '') {
                $age_from = null;
            }

            if ($age_till === '') {
                $age_till = null;
            }

            $data = User::findProfiles($username, $bio, $age_from, $age_till, $gender, $location, $paginate);

            return response()->json(array('code' => 200, 'data' => $data));
        } catch (Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }
}
