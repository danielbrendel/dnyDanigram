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
     * Return if string is a valid identifier for usernames and tags
     * @param $ident
     * @return false|int
     */
    public static function isValidNameIdent($ident)
    {
        return !preg_match('/[^a-z_\-0-9]/i', $ident);
    }

    /**
     * Get image type of file
     *
     * @param $file
     * @return mixed|null
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
                    $result[] = $curName;
                }
                $curName = '';
                continue;
            }

            if ($inMention) {
                if ((in_array($text[$i], $terminationChars)) || ($i === strlen($text) - 1)) {
                    $result[] = $curName;
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
     * @throws \Exception
     */
    public static function getSettings()
    {
        try {
            return DB::table('app_settings')->first();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Save setting
     *
     * @param $key
     * @param $value
     * @throws \Exception
     */
    public static function saveSetting($key, $value)
    {
        try {
            DB::table('app_settings')->where('id', '=', 1)->update(array($key => $value));
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Generate a random password
     *
     * @param $length
     * @return string
     * @throws \Exception
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
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get custom CSS content
     *
     * @return false|string
     * @throws \Exception
     */
    public static function getCustomCss()
    {
        try {
            if (file_exists(public_path() . '/css/custom.css')) {
                return file_get_contents(public_path() . '/css/custom.css');
            }

            return '';
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Save custom CSS content
     *
     * @throws \Exception
     */
    public static function saveCustomCss($code)
    {
        try {
            file_put_contents(public_path() . '/css/custom.css', $code);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get list of available languages
     *
     * @return array
     * @throws \Exception
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
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
