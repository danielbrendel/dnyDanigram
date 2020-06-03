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
use App\FaqModel;
use App\User;
use Dotenv\Dotenv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MaintainerController extends Controller
{
    /**
     * Validate permissions
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = User::get(auth()->id());
            if ((!$user) || (!$user->maintainer)) {
                abort(403);
            }

            return $next($request);
        });
    }

    /**
     * Show index page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('maintainer.index', [
            'user' => User::get(auth()->id()),
            'settings' => AppModel::getSettings(),
            'faqs' => FaqModel::getAll(),
            'custom_css' => AppModel::getCustomCss(),
            'langs' => AppModel::getLanguageList()
        ]);
    }

    /**
     * Save app database settings
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save()
    {
        try {
            $attr = request()->validate([
               'attribute' => 'required',
               'content' => 'required'
            ]);

            AppModel::saveSetting($attr['attribute'], $attr['content']);

            Artisan::call('cache:clear');

            return back()->with('flash.success', __('app.settings_saved'));
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }

    /**
     * Add FAQ item
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addFaq()
    {
        try {
            $attr = request()->validate([
                'question' => 'required',
                'answer' => 'required'
            ]);

            $faq = new FaqModel();
            $faq->question = $attr['question'];
            $faq->answer = $attr['answer'];
            $faq->save();

            Artisan::call('cache:clear');

            return back()->with('flash.success', __('app.faq_saved'));
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }

    /**
     * Edit FAQ item
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editFaq()
    {
        try {
            $attr = request()->validate([
                'id' => 'required|numeric',
                'question' => 'required',
                'answer' => 'required'
            ]);

            $faq = FaqModel::where('id', '=', $attr['id'])->first();
            $faq->question = $attr['question'];
            $faq->answer = $attr['answer'];
            $faq->save();

            Artisan::call('cache:clear');

            return back()->with('flash.success', __('app.faq_saved'));
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }

    /**
     * Remove FAQ item
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFaq($id)
    {
        try {
            $faq = FaqModel::where('id', '=', $id)->first();
            $faq->delete();

            Artisan::call('cache:clear');

            return back()->with('flash.success', __('app.faq_removed'));
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }

    /**
     * Store env configuration
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function envSave()
    {
        try {
            foreach ($_POST as $key => $value) {
                if (substr($key, 0, 4) === 'ENV_') {
                    $_ENV[substr($key, 4)] = $value;
                }
            }

            AppModel::saveEnvironmentConfig();

            return back()->with('flash.success', __('app.env_saved'));
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }

    /**
     * Get user details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userDetails()
    {
        try {
            $ident = request('ident');

            $user = User::getByIdent($ident);
            if (!$user) {
                return response()->json(array('code' => 404, 'msg' => __('app.user_not_found')));
            }

            return response()->json(array('code' => 200, 'data' => $user));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Save user data
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userSave()
    {
        try {
            $attr = request()->validate([
                'id' => 'required|numeric',
                'username' => 'required',
                'email' => 'required|email',
                'deactivated' => 'nullable|numeric',
                'admin' => 'nullable|numeric',
                'maintainer' => 'nullable|numeric'
            ]);

            $user = User::get($attr['id']);
            if (!$user) {
                return back()->with('flash.error', __('app.user_not_found'));
            }

            $user->username = $attr['username'];
            $user->email = $attr['email'];
            $user->deactivated = (isset($attr['deactivated'])) ? (bool)$attr['deactivated'] : false;
            $user->admin = (isset($attr['admin'])) ? (bool)$attr['admin'] : false;
            $user->maintainer = (isset($attr['maintainer'])) ? (bool)$attr['maintainer'] : false;
            if ($user->maintainer === true) {
                $user->admin = true;
            }
            $user->save();

            return back()->with('flash.success', __('app.saved'));
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }

    /**
     * Send newsletter
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function newsletter()
    {
        try {
            $attr = request()->validate([
               'subject' => 'required',
               'content' => 'required'
            ]);

            User::sendNewsletter($attr['subject'], $attr['content']);

            return back()->with('flash.success', __('app.newsletter_sent'));
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }

    /**
     * Save CSS content
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveCss()
    {
        try {
            $attr = request()->validate([
               'code' => 'nullable'
            ]);

            AppModel::saveCustomCss($attr['code']);

            return back()->with('flash.success', __('app.custom_css_saved'));
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }

    /**
     * Save favicon
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveFavicon()
    {
        try {
            $attr = request()->validate([
               'favicon' => 'required|file'
            ]);

            $av = request()->file('favicon');
            if ($av != null) {
                if ($av->getClientOriginalExtension() !== 'png') {
                    return back()->with('error', __('app.not_a_png_file'));
                }

                $tmpName = md5(random_bytes(55));

                $av->move(base_path() . '/public/', $tmpName . '.' . $av->getClientOriginalExtension());

                list($width, $height) = getimagesize(base_path() . '/public/' . $tmpName . '.' . $av->getClientOriginalExtension());

                $avimg = imagecreatetruecolor(64, 64);
                if (!$avimg)
                    throw new \Exception('imagecreatetruecolor() failed');

                $srcimage = null;
                $newname =  'favicon.' . $av->getClientOriginalExtension();
                switch (AppModel::getImageType(base_path() . '/public/' . $tmpName . '.' . $av->getClientOriginalExtension())) {
                    case IMAGETYPE_PNG:
                        $srcimage = imagecreatefrompng(base_path() . '/public/' . $tmpName . '.' . $av->getClientOriginalExtension());
                        imagecopyresampled($avimg, $srcimage, 0, 0, 0, 0, 64, 64, $width, $height);
                        imagepng($avimg, base_path() . '/public/' . $newname);
                        break;
                    default:
                        return back()->with('error', __('app.not_a_png_file'));
                        break;
                }

                unlink(base_path() . '/public/' . $tmpName . '.' . $av->getClientOriginalExtension());

                return back()->with('success', __('app.saved'));
            }
        } catch (\Exception $e) {
            return back()->with('flash.error', $e->getMessage());
        }
    }
}