<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2022 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Class AppModel
 *
 * General application interface
 */
class AppModel extends Model
{
    const ONE_HOUR = 3600;
    const ONE_DAY = self::ONE_HOUR * 24;
    const MAX_EXPRESSION_LENGTH = 15;

    /**
     * Get name parts
     * @return array
     */
    public static function getNameParts()
    {
        return array(
          substr(env('APP_PROJECTNAME'), 0, env('APP_DIVISION')),
          substr(env('APP_PROJECTNAME'), env('APP_DIVISION'))
        );
    }

    /**
     * Get index content
     * @return mixed
     */
    public static function getIndexContent()
    {
        return Cache::remember('index_content', AppModel::ONE_DAY, function() {
           return DB::table('app_settings')->first()->home_index_content;
        });
    }

    /**
     * Get cookie consent text
     * @return mixed
     */
    public static function getCookieConsentText()
    {
        return Cache::remember('cookie_consent', AppModel::ONE_DAY, function() {
            return DB::table('app_settings')->first()->cookie_consent;
        });
    }

    /**
     * Get about content
     * @return mixed
     */
    public static function getAboutContent()
    {
        return Cache::remember('about', AppModel::ONE_DAY, function() {
            return DB::table('app_settings')->first()->about;
        });
    }

    /**
     * Get ToS content
     * @return mixed
     */
    public static function getTermsOfService()
    {
        return Cache::remember('tos', AppModel::ONE_DAY, function() {
            return DB::table('app_settings')->first()->tos;
        });
    }

    /**
     * Get imprint content
     * @return mixed
     */
    public static function getImprint()
    {
        return Cache::remember('imprint', AppModel::ONE_DAY, function() {
            return DB::table('app_settings')->first()->imprint;
        });
    }

    /**
     * Get short registration info
     * @return mixed
     */
    public static function getRegInfo()
    {
        return Cache::remember('reg_info', AppModel::ONE_DAY, function() {
            return DB::table('app_settings')->first()->reg_info;
        });
    }

    /**
     * Get welcome content
     * @return mixed
     */
    public static function getWelcomeContent()
    {
        return Cache::remember('welcome_content', AppModel::ONE_DAY, function() {
            return DB::table('app_settings')->first()->welcome_content;
        });
    }

    /**
     * Get formatted project name
     * @return mixed
     */
    public static function getFormattedProjectName()
    {
        return Cache::remember('formatted_project_name', AppModel::ONE_DAY, function() {
            return DB::table('app_settings')->first()->formatted_project_name;
        });
    }

    /**
     * Get formatted project name
     * @return mixed
     */
    public static function getDefaultTheme()
    {
        return Cache::remember('default_theme', AppModel::ONE_DAY, function() {
            return DB::table('app_settings')->first()->default_theme;
        });
    }

    /**
     * Get head code content
     * @return mixed
     */
    public static function getHeadCode()
    {
        return Cache::remember('head_code', AppModel::ONE_DAY, function() {
            return DB::table('app_settings')->first()->head_code;
        });
    }

    /**
     * Get ad code content
     * @return mixed
     */
    public static function getAdCode()
    {
        return Cache::remember('adcode', AppModel::ONE_DAY, function() {
            return DB::table('app_settings')->first()->adcode;
        });
    }

    /**
     * Return if string is a valid identifier for usernames and tags
     * @param $ident
     * @return mixed
     */
    public static function isValidNameIdent($ident)
    {
        if (is_numeric($ident) || (strlen($ident) == 0)) {
            return false;
        }

        return !preg_match('/[^a-z_\-0-9]/i', $ident);
    }

    /**
     * Get image type of file
     *
     * @param $file
     * @return mixed
     */
    public static function getImageType($file)
    {
        $imagetypes = array(
            array('png', IMAGETYPE_PNG),
            array('jpg', IMAGETYPE_JPEG),
            array('jpeg', IMAGETYPE_JPEG)
        );

        for ($i = 0; $i < count($imagetypes); $i++) {
            if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) == $imagetypes[$i][0]) {
                if (exif_imagetype($file) == $imagetypes[$i][1])
                    return $imagetypes[$i][1];
            }
        }

        return null;
    }

    /**
     * Get a list of mentioned users
     *
     * @param $text
     * @return array
     */
    public static function getMentionList($text)
    {
        $inMention = false;
        $terminationChars = array(' ', '.', '!', '\n');
        $curName = '';

        $result = array();

        for ($i = 0; $i < strlen($text); $i++) {
            if ($text[$i] === '@') {
                $inMention = true;
                if (strlen($curName) > 0) {
                    if (!in_array($curName, $result)) {
                        $result[] = $curName;
                    }
                }
                $curName = '';
                continue;
            }

            if ($inMention) {
                if ((in_array($text[$i], $terminationChars)) || ($i === strlen($text) - 1)) {
                    if (!in_array($curName, $result)) {
                        $result[] = $curName;
                    }

                    $curName = '';
                    $inMention = false;
                    continue;
                }

                $curName .= $text[$i];
            }
        }

        return $result;
    }

    /**
     * Get settings
     *
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     * @throws Exception
     */
    public static function getSettings()
    {
        try {
            return DB::table('app_settings')->first();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Save setting
     *
     * @param $key
     * @param $value
     * @return void
     * @throws Exception
     */
    public static function saveSetting($key, $value)
    {
        try {
            DB::table('app_settings')->where('id', '=', 1)->update(array($key => $value));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Save env content
     *
     * @throws \Exception
     */
    public static function saveEnvironmentConfig()
    {
        try {
            $content = '# Danigram environment configuration' . PHP_EOL;

            foreach ($_ENV as $key => $value) {
                $type = gettype($value);
                if ($type === 'string') {
                    $content .= $key . '="' . $value . '"' . PHP_EOL;
                } else {
                    if ($type === 'bool') {
                        $content .= $key = '=' . (($value) ? 'true' : 'false') . '' . PHP_EOL;
                    } else {
                        $content .= $key = '=' . $value . '' . PHP_EOL;
                    }
                }
            }

            $entire = file_get_contents(base_path() . '/.env') . PHP_EOL . $content;

            file_put_contents(base_path() . '/.env', $entire);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Generate a random password
     *
     * @param $length
     * @return string
     * @throws Exception
     */
    public static function getRandomPassword($length)
    {
        try {
            $chars = 'abcdefghijklmnopqrstuvwxyz1234567890%$!';

            $result = '';

            for ($i = 0; $i < $length; $i++) {
                $result .= $chars[rand(0, strlen($chars) - 1)];
            }

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get list of available languages
     *
     * @return array
     * @throws Exception
     */
    public static function getLanguageList()
    {
        try {
            $result = array();
            $files = scandir(base_path() . '/resources/lang');
            foreach ($files as $file) {
                if (($file[0] !== '.') && (is_dir(base_path() . '/resources/lang/' . $file))) {
                    $result[] = $file;
                }
            }

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get short expression
     *
     * @param $exp
     * @return string
     */
    public static function getShortExpression($exp)
    {
        return (strlen($exp) > self::MAX_EXPRESSION_LENGTH) ?
            substr($exp, 0, self::MAX_EXPRESSION_LENGTH) . '...' :
            $exp;
    }

    /**
     * Lock entity
     * @param $id
     * @param $type
     * @return void
     * @throws Exception
     */
    public static function lockEntity($id, $type)
    {
        try {
            if ($type === 'ENT_HASHTAG') {
                $item = TagsModel::where('id', '=', $id)->first();
                if ($item) {
                    $item->locked = true;
                    $item->save();
                }
            } else if ($type === 'ENT_USER') {
                $item = User::where('id', '=', $id)->first();
                if ($item) {
                    $item->deactivated = true;
                    $item->save();
                }
            } else if ($type === 'ENT_POST') {
                $item = PostModel::where('id', '=', $id)->first();
                if ($item) {
                    $item->locked = true;
                    $item->save();
                }
            } else if ($type === 'ENT_COMMENT') {
                $item = ThreadModel::where('id', '=', $id)->first();
                if ($item) {
                    $item->locked = true;
                    $item->save();
                }
            } else if ($type === 'ENT_FORUMPOST') {
                $item = ForumPostModel::where('id', '=', $id)->first();
                if ($item) {
                    $item->locked = true;
                    $item->save();
                }
            } else {
                throw new Exception('Invalid type: ' . $type, 500);
            }

            $rows = ReportModel::where('entityId', '=', $id)->where('type', '=', $type)->get();
            foreach ($rows as $row) {
                $row->delete();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete entity
     * @param $id
     * @param $type
     * @throws Exception
     */
    public static function deleteEntity($id, $type)
    {
        try {
            if ($type === 'ENT_HASHTAG') {
                $item = TagsModel::where('id', '=', $id)->first();
                if ($item) {
                    $item->delete();
                }
            } else if ($type === 'ENT_USER') {
                $item = User::where('id', '=', $id)->first();
                if ($item) {
                    $item->username = '_deleted_' . md5(random_bytes(55));
                    $item->email = md5(random_bytes(55));
                    $item->avatar = 'default.png';
                    $item->password = '';
					$item->deactivated = true;
                    $item->save();
                }
            } else if ($type === 'ENT_POST') {
                $item = PostModel::where('id', '=', $id)->first();
                if ($item) {
                    $item->locked = true;
                    $item->save();
                    if (file_exists(public_path() . '/gfx/posts/' . $item->image_full)) {
                        unlink(public_path() . '/gfx/posts/' . $item->image_full);
                    }
                    if (file_exists(public_path() . '/gfx/posts/' . $item->image_thumb)) {
                        unlink(public_path() . '/gfx/posts/' . $item->image_thumb);
                    }
                }
            } else if ($type === 'ENT_COMMENT') {
                $item = ThreadModel::where('id', '=', $id)->first();
                if ($item) {
                    $item->locked = true;
                    $item->text = '';
                    $item->save();
                }
            } else if ($type === 'ENT_FORUMPOST') {
                $item = ForumPostModel::where('id', '=', $id)->first();
                if ($item) {
                    $item->locked = true;
                    $item->message = '';
                    $item->save();
                }
            } else {
                throw new Exception('Invalid type: ' . $type, 500);
            }

            $rows = ReportModel::where('entityId', '=', $id)->where('type', '=', $type)->get();
            foreach ($rows as $row) {
                $row->delete();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Set entity safe
     *
     * @param $id
     * @param $type
     * @return void
     * @throws Exception
     */
    public static function setEntitySafe($id, $type)
    {
        try {
            $rows = ReportModel::where('entityId', '=', $id)->where('type', '=', $type)->get();
            foreach ($rows as $row) {
                $row->delete();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Save formatted project name
     *
     * @param $code
     * @return void
     * @throws Exception
     */
    public static function saveFormattedProjectName($code)
    {
        try {
            DB::update('UPDATE app_settings SET formatted_project_name = ? WHERE id = 1', array($code));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Initialize newsletter sending
     *
     * @param $subject
     * @param $content
     * @return void
     * @throws Exception
     */
    public static function initNewsletter($subject, $content)
    {
        try {
            $token = md5($subject . $content . random_bytes(55));

            DB::update('UPDATE app_settings SET newsletter_token = ?, newsletter_subject = ?, newsletter_content = ? WHERE id = 1', array($token, $subject, $content));
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Process newsletter job
     *
     * @return array
     * @throws Exception
     */
    public static function newsletterJob()
    {
        try {
            $result = array();

            $settings = self::getSettings();
            if ($settings->newsletter_token !== null) {
                $users = User::where('deactivated', '=', false)->where('account_confirm', '=', '_confirmed')->where('newsletter', '=', true)->where('newsletter_token', '<>', $settings->newsletter_token)->limit(env('APP_NEWSLETTER_UCOUNT'))->get();
                foreach ($users as $user) {
                    $user->newsletter_token = $settings->newsletter_token;
                    $user->save();

                    MailerModel::sendMail($user->email, $settings->newsletter_subject, $settings->newsletter_content);

                    $result[] = array('username' => $user->username, 'email' => $user->email, 'sent_date' => date('Y-m-d H:i:s'));
                }
            }

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Create a HelpRealm ticket
     *
     * @param $name
     * @param $email
     * @param $subject
     * @param $body
     * @return void
     * @throws Exception
     */
    public static function createTicket($name, $email, $subject, $body)
    {
        try {
            $postFields = [
                'apitoken' => env('HELPREALM_TOKEN'),
                'subject' => $subject,
                'text' => $body,
                'name' => $name,
                'email' => $email,
                'type' => env('HELPREALM_TICKETTYPEID'),
                'prio' => '1',
                'attachment' => null
            ];

            $ch = curl_init("https://helprealm.io/api/" . env('HELPREALM_WORKSPACE') . '/ticket/create');

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

            $response = curl_exec($ch);
            if(curl_error($ch)) {
                throw new Exception(curl_error($ch), curl_errno($ch));
            }

            curl_close($ch);

            $json = json_decode($response);
            if ($json->code !== 201) {
                throw new Exception('Backend returned error ' . ((isset($json->data->invalid_fields)) ? print_r($json->data->invalid_fields, true) : ''), $json->code);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}
