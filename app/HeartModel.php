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

/**
 * Class HeartModel
 *
 * Represents the interface to give hearts to a post
 */
class HeartModel extends Model
{
    /**
     * Throw if type is unknown
     *
     * @param $type
     * @throws \Exception
     */
    private static function validateEntityType($type)
    {
        try {
            $types = array('ENT_POST', 'ENT_HASHTAG');

            if (!in_array($type, $types)) {
                throw new \Exception('Unknown type: ' . $type, 404);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Add heart to post if not already
     *
     * @param $userId
     * @param $entityId
     * @param $entType
     * @throws \Exception
     */
    public static function addHeart($userId, $entityId, $entType)
    {
        try {
            static::validateEntityType($entType);

            $heart = HeartModel::where('userId', '=', $userId)->where('entityId', '=', $entityId)->where('type', '=', $entType)->first();
            if ($heart) {
                throw new \Exception(__('app.already_hearted'));
            }

            $heart = new HeartModel;
            $heart->userId = $userId;
            $heart->entityId = $entityId;
            $heart->type = $entType;
            $heart->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Remove heart of post
     * @param $userId
     * @param $entityId
     * @param $entType
     * @throws \Exception
     */
    public static function removeHeart($userId, $entityId, $entType)
    {
        try {
            static::validateEntityType($entType);

            $heart = HeartModel::where('userId', '=', $userId)->where('entityId', '=', $entityId)->where('type', '=', $entType)->first();
            if (!$heart) {
                throw new Exception(__('app.heart_not_exists'));
            }

            $heart->delete();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Check if user has voted for given entity
     *
     * @param $userId
     * @param $entityId
     * @param $entType
     * @return bool
     */
    public static function hasUserHearted($userId, $entityId, $entType)
    {
        try {
            $heart = HeartModel::where('userId', '=', $userId)->where('entityId', '=', $entityId)->where('type', '=', $entType)->first();

            return $heart !== null;
        } catch (\Execption $e) {
            throw $e;
        }
    }
}
