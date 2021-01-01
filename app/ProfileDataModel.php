<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2021 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace App;

use Illuminate\Database\Eloquent\Model;
use Exception;
use App\ProfileModel;

/**
 * Class ProfileDataModel
 *
 * Represents the interface to profile item content entries
 */
class ProfileDataModel extends Model
{
    /**
     * Add or edit profile data item entry
     * 
     * @param $userId
     * @param $profile_item_ident
     * @param $content
     * @return void
     * @throws Exception
     */
    public static function addOrEdit($userId, $profile_item_ident, $content)
    {
        try {
            $item = ProfileDataModel::where('userId', '=', $userId)->where('profile_item_ident', '=', $profile_item_ident)->first();
            if (!$item) {
                $item = new ProfileDataModel();
                $item->userId = $userId;
                $item->profile_item_ident = $profile_item_ident;
            }

            $item->content = $content;
            $item->save();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Query user profile data item content
     * 
     * @param $userId
     * @param $profile_item_name
     * @return string
     * @throws Exception
     */
    public static function querySingle($userId, $profile_item_name)
    {
        try {
            $profileItem = ProfileModel::where('name', '=', $profile_item_name)->where('locale', '=', \App::getLocale())->where('active', '=', true)->first();
            if (!$profileItem) {
                return null;
            }

            $dataItem = ProfileDataModel::where('userId', '=', $userId)->where('profile_item_ident', '=', $profileItem->name)->first();
            if (!$dataItem) {
                return null;
            }

            return $dataItem->content;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get all profile items of a user
     * 
     * @param $userId
     * @return array
     * @throws Exception
     */
    public static function queryAll($userId)
    {
        try {
            $result = array();

            $profileItem = ProfileModel::where('locale', '=', \App::getLocale())->where('active', '=', true)->get();
            if (!$profileItem) {
                return $result;
            }

            foreach ($profileItem as $pi) {
                $result[$pi->name] = array('translation' => $pi->translation, 'content' => '');

                $dataItem = ProfileDataModel::where('userId', '=', $userId)->where('profile_item_ident', '=', $pi->name)->first();
                if (!$dataItem) {
                    continue;
                }

                $result[$pi->name]['content'] = $dataItem->content;
            }

            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
