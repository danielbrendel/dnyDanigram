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
 * Class BookmarksModel
 *
 * Interface to bookmarks
 */
class BookmarksModel extends Model
{
    /**
     * Validate entity type
     *
     * @param $entType
     * @throws \Exception
     */
    public static function validateEntityType($entType)
    {
        try {
            $types = array('ENT_HASHTAG', 'ENT_USER');

            if (!in_array($entType, $types)) {
                throw new \Exception('Invalid entity type: ' . $entType);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Add new bookmark entry
     *
     * @param $userId
     * @param $entityId
     * @param $entType
     * @throws \Exception
     */
    public static function add($userId, $entityId, $entType)
    {
        try {
            static::validateEntityType($entType);

            $exists = BookmarksModel::where('userId', '=', $userId)->where('entityId', '=', $entityId)->where('type', '=', $entType)->count();
            if (!$exists) {
                $entry = new BookmarksModel();
                $entry->userId = $userId;
                $entry->entityId = $entityId;
                $entry->type = $entType;
                $entry->save();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Remove bookmark
     *
     * @param $userId
     * @param $entityId
     * @param $entType
     * @throws \Exception
     */
    public static function remove($userId, $entityId, $entType)
    {
        try {
            static::validateEntityType($entType);

            $exists = BookmarksModel::where('userId', '=', $userId)->where('entityId', '=', $entityId)->where('type', '=', $entType)->first();
            if ($exists) {
                $exists->delete();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Check if user as bookmarked a specific entity
     *
     * @param $userId
     * @param $entityId
     * @param $entType
     * @return bool
     * @throws \Exception
     */
    public static function hasUserBookmarked($userId, $entityId, $entType)
    {
        try {
            $exists = BookmarksModel::where('userId', '=', $userId)->where('entityId', '=', $entityId)->where('type', '=', $entType)->count();

            return $exists > 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get bookmarks of user
     *
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function getForUser($id)
    {
        try {
            return BookmarksModel::where('userId', '=', $id)->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
