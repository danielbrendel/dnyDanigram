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
          substr(env('APP_NAME'), 0, env('APP_DIVISION')),
          substr(env('APP_NAME'), env('APP_DIVISION'))
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
}
