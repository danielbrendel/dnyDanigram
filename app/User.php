<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace App;

use Exception;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use \App\ClientModel;
use \App\AgentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use stdClass;
use Illuminate\Support\Carbon;

/**
 * Class User
 *
 * Represents a user that can either be an agent or a client
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get user object
     *
     * @param int $id The ID of the user
     * @return mixed
     */
    public static function get($id)
    {
        $user = User::where('id', '=', $id)->first();

        return $user;
    }

    /**
     * Return admin flag value
     * @param $id
     * @return bool
     */
    public static function isAdmin($id)
    {
        $user = static::get($id);

        if (($user) && ($user->admin)) {
            return true;
        }

        return false;
    }

    /**
     * Get user object by email
     *
     * @param string $email The E-Mail address
     * @return mixed
     */
    public static function getByEmail($email)
    {
        $user = User::where('email', '=', $email)->first();

        return $user;
    }

    /**
     * Get user object by username
     *
     * @param string $username The username
     * @return mixed
     */
    public static function getByUsername($username)
    {
        $user = User::where('username', '=', $username)->first();

        return $user;
    }

    /**
     * Get user object by authentication ID
     *
     * @return mixed
     */
    public static function getByAuthId()
    {
        if (Auth::guest()) {
            return null;
        }

        return User::where('id', '=', auth()->id())->first();
    }

    /**
     * Perform registration
     *
     * @param $attr
     * @return int
     * @throws Exception
     */
    public static function register($attr)
    {
        try {
            if (!Auth::guest()) {
                throw new Exception(__('app.register_already_signed_in'));
            }

            $attr['username'] = strtolower($attr['username']);

            if ($attr['password'] !== $attr['password_confirmation']) {
                throw new Exception(__('app.register_password_mismatch'));
            }

            $sum = CaptchaModel::querySum(session()->getId());
            if ($attr['captcha'] !== $sum) {
                throw new Exception(__('app.register_captcha_invalid'));
            }

            if (User::getByEmail($attr['email'])) {
                throw new Exception(__('app.register_email_in_use'));
            }

            if (User::getByUsername($attr['username'])) {
                throw new Exception(__('app.register_username_in_use'));
            }

            if (!AppModel::isValidNameIdent($attr['username'])) {
                throw new Exception(__('app.register_username_invalid_chars'));
            }

            $user = new User();
            $user->username = $attr['username'];
            $user->password = password_hash($attr['password'], PASSWORD_BCRYPT);
            $user->email = $attr['email'];
            $user->avatar = 'default.png';
            $user->account_confirm = md5($attr['email'] . $attr['username'] . random_bytes(55));
            $user->save();

            $html = view('mail.registered', ['username' => $user->username, 'hash' => $user->account_confirm])->render();
            MailerModel::sendMail($user->email, __('app.mail_subject_register'), $html);

            return $user->id;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public static function resend($id)
    {
        try {
            $user = User::where('id', '=', $id)->where('account_confirm', '<>', '_confirmed')->first();
            if (!$user) {
                throw new Exception(__('app.user_id_not_found_or_already_confirmed', ['id' => $id]));
            }

            $html = view('mail.registered', ['username' => $user->username, 'hash' => $user->account_confirm])->render();
            MailerModel::sendMail($user->email, __('app.mail_subject_register'), $html);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Confirm account
     *
     * @param $hash
     * @throws Exception
     */
    public static function confirm($hash)
    {
        try {
            $user = User::where('account_confirm', '=', $hash)->first();
            if ($user === null) {
                throw new Exception(__('app.register_confirm_token_not_found'));
            }

            $user->account_confirm = '_confirmed';
            $user->save();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Initialize password recovery
     *
     * @param $email
     * @throws Exception
     */
    public static function recover($email)
    {
        try {
            $user = User::getByEmail($email);
            if (!$user) {
                throw new Exception(__('app.email_not_found'));
            }

            $user->password_reset = md5($user->email . date('c') . uniqid('', true));
            $user->save();

            $htmlCode = view('mail.pwreset', ['username' => $user->username, 'hash' => $user->password_reset])->render();
            MailerModel::sendMail($user->email, __('app.mail_password_reset_subject'), $htmlCode);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Perform password reset
     *
     * @param $password
     * @param $password_confirm
     * @param $hash
     * @throws Exception
     */
    public static function reset($password, $password_confirm, $hash)
    {
        try {
            if ($password != $password_confirm) {
                throw new Exception(__('app.password_mismatch'));
            }

            $user = User::where('password_reset', '=', $hash)->first();
            if (!$user) {
                throw new Exception(__('app.hash_not_found'));
            }

            $user->password = password_hash($password, PASSWORD_BCRYPT);
            $user->password_reset = '';
            $user->save();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get user stats
     *
     * @param $userId
     * @return stdClass
     * @throws Exception
     */
    public static function getStats($userId)
    {
        try {
            $stats = new stdClass();

            $stats->posts = Cache::remember('user_stats_posts_' . $userId, 3600 * 24, function () use ($userId) {
                return PostModel::where('userId', '=', $userId)->count();
            });

            $stats->comments = Cache::remember('user_stats_comments_' . $userId, 3600 * 24, function () use ($userId) {
                ThreadModel::where('userId', '=', $userId)->count();
            });

            return $stats;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Change username
     *
     * @param $id
     * @param $name
     * @throws Exception
     */
    public static function changeUsername($id, $name)
    {
        try {
            $user = User::get($id);
            if (($user) && (!$user->deactivated) && ($user->username !== $name)) {
                $inuse = User::where('username', '=', $name)->count();
                if ($inuse > 0) {
                    throw new Exception(__('app.name_already_in_use'));
                }

                if (!AppModel::isValidNameIdent($name)) {
                    throw new Exception(__('app.invalid_name_ident'));
                }

                $user->username = $name;
                $user->save();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Change bio
     *
     * @param $id
     * @param $bio
     * @throws Exception
     */
    public static function changeBio($id, $bio)
    {
        try {
            $user = User::get($id);
            if (($user) && (!$user->deactivated)) {
                $user->bio = $bio;
                $user->save();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Save gender
     *
     * @param $id
     * @param $gender
     * @throws Exception
     */
    public static function saveGender($id, $gender)
    {
        try {
            $user = User::get($id);
            if (($user) && (!$user->deactivated)) {
                $user->gender = $gender;
                $user->save();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Save birthday
     *
     * @param $id
     * @param $birthday
     * @throws Exception
     */
    public static function saveBirthday($id, $birthday)
    {
        try {
            $user = User::get($id);
            if (($user) && (!$user->deactivated)) {
                $user->birthday = $birthday;
                $user->save();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Save location
     *
     * @param $id
     * @param $location
     * @throws Exception
     */
    public static function saveLocation($id, $location)
    {
        try {
            $user = User::get($id);
            if (($user) && (!$user->deactivated)) {
                $user->location = $location;
                $user->save();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Change password
     *
     * @param $id
     * @param $password
     * @throws Exception
     */
    public static function changePassword($id, $password)
    {
        try {
            $user = User::get($id);
            if (($user) && (!$user->deactivated)) {
                $user->password = password_hash($password, PASSWORD_BCRYPT);
                $user->save();

                $html = view('mail.pw_changed', ['name' => $user->username])->render();
                MailerModel::sendMail($user->email, __('app.password_changed'), $html);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Change E-Mail
     *
     * @param $id
     * @param $email
     * @throws Exception
     */
    public static function changeEMail($id, $email)
    {
        try {
            $user = User::get($id);
            if (($user) && (!$user->deactivated) && ($user->email !== $email)) {
                $oldMail = $user->email;
                $user->email = $email;
                $user->save();

                $html = view('mail.email_changed', ['name' => $user->username, 'email' => $email])->render();
                MailerModel::sendMail($user->email, __('app.email_changed'), $html);
                MailerModel::sendMail($oldMail, __('app.email_changed'), $html);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Save email on message flag
     *
     * @param $id
     * @param $value
     * @throws Exception
     */
    public static function saveEmailOnMessageFlag($id, $value)
    {
        try {
            $user = User::get($id);
            if (($user) && (!$user->deactivated)) {
                $user->email_on_message = $value;
                $user->save();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Save newsletter flag
     *
     * @param $id
     * @param $value
     * @throws Exception
     */
    public static function saveNewsletterFlag($id, $value)
    {
        try {
            $user = User::get($id);
            if (($user) && (!$user->deactivated)) {
                $user->newsletter = $value;
                $user->save();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Save nsfw flag
     *
     * @param $id
     * @param $value
     * @throws Exception
     */
    public static function saveNsfwFlag($id, $value)
    {
        try {
            $user = User::get($id);
            if (($user) && (!$user->deactivated)) {
                $user->nsfw = $value;
                $user->save();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get user by ID, username or e-mail
     *
     * @param $ident
     * @return mixed
     * @throws Exception
     */
    public static function getByIdent($ident)
    {
        try {
            $user = User::get($ident);
            if (!$user) {
                $user = User::getByUsername($ident);
                if (!$user) {
                    $user = User::getByEmail($ident);
                }
            }

            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Send news letter
     *
     * @param $subject
     * @param $content
     * @throws Exception
     */
    public static function sendNewsletter($subject, $content)
    {
        try {
            $users = User::where('deactivated', '=', false)->where('newsletter', '=', true)->get();
            foreach ($users as $user) {
                MailerModel::sendMail($user->email, $subject, $content);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Save geo position of user
     * 
     * @param $latitude
     * @param $longitude
     * @return void
     * @throws Exception
     */
    public static function storeGeoLocation($latitude, $longitude)
    {
        try {
            $item = User::where('deactivated', '=', false)->where('id', '=', auth()->id())->first();
            if (!$item) {
                throw new Exception(__('app.user_not_found_or_deactivated'));
            }

            $item->latitude = $latitude;
            $item->longitude = $longitude;
            $item->save();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find members within geo range
     * 
     * @param $max_distance
     * @param $paginate
     * @return mixed
     * @throws Exception
     */
    public static function findWithinRange($max_distance, $paginate = null)
    {
        try {
            $user = User::getByAuthId();
            if ($user->geo_exclude) {
                return array();
            }

            $query = \DB::table(with(new User)->getTable())
                ->select(\DB::raw('id, username, avatar, latitude, longitude, SQRT(POW(69.1 * (latitude - ' . $user->latitude . '), 2) + POW(69.1 * (' . $user->longitude . ' - longitude) * COS(latitude / 57.3), 2)) AS distance'))
                ->where('deactivated', '=', false)
                ->where('geo_exclude', '=', false)
                ->where('id', '<>', $user->id)
                ->havingRaw('distance <= ?', [$max_distance]);

            if ($paginate !== null) {
                $query->where('id', '>', $paginate);
            }

            return $query->limit(env('APP_PROFILEPACKLIMIT'))->get()->toArray();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Save geo_exclude flag
     *
     * @param $id
     * @param $value
     * @throws Exception
     */
    public static function saveGeoExcludeFlag($id, $value)
    {
        try {
            $user = User::get($id);
            if (($user) && (!$user->deactivated)) {
                $user->geo_exclude = $value;
                $user->save();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find profiles by given criteria
     * 
     * @param $username
     * @param $bio
     * @param $age_from
     * @param $age_till
     * @param $gender
     * @param $location
     * @param $paginate
     * @return mixed
     * @throws Exception
     */
    public static function findProfiles($username, $bio, $age_from, $age_till, $gender, $location, $paginate)
    {
        try {
            $query = User::where('deactivated', '=', false);

            if ((is_string($username)) && (strlen($username) > 0)) {
                $query->whereRaw('LOWER(username) LIKE ?', ['%' . trim(strtolower($username)) . '%']);
            }

            if ((is_string($bio)) && (strlen($bio) > 0)) {
                $query->whereRaw('LOWER(bio) LIKE ?', ['%' . trim(strtolower($bio)) . '%']);
            }

            if ((is_string($location)) && (strlen($location) > 0)) {
                $query->whereRaw('LOWER(location) LIKE ?', ['%' . trim(strtolower($location)) . '%']);
            }

            if ($gender !== 0) {
                $query->where('gender', '=', $gender);
            }

            if (($age_from !== null) && (is_numeric($age_from))) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, birthday, CURDATE()) >= ?', [(int)$age_from]);
            }

            if (($age_till !== null) && (is_numeric($age_till))) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, birthday, CURDATE()) <= ?', [(int)$age_till]);
            }

            if ($paginate !== null) {
                $query->where('id', '>', $paginate);
            }

            $collection = $query->limit(env('APP_PROFILEPACKLIMIT'))->get();

            foreach ($collection as &$item) {
                $item->genderStr = __('app.gender_unspecified');
                if ($item->gender == 1) {
                    $item->genderStr = __('app.gender_male');
                } else if ($item->gender == 2) {
                    $item->genderStr = __('app.gender_female');
                } else if ($item->gender == 3) {
                    $item->genderStr = __('app.gender_diverse');
                }

                $item->age = Carbon::parse($item->birthday)->age;
            }

            return $collection->toArray();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
