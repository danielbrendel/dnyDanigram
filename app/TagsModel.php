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
use App\HeartModel;

/**
 * Class TagsModel
 *
 * Represents the tag interface
 */
class TagsModel extends Model
{
    /**
     * Add tag to list
     *
     * @param $name
     * @throws \Exception
     */
    public static function addTag($name)
    {
        try {
            $name = str_replace('#', '', $name);

            $exists = TagsModel::where('tag', '=', $name)->first();
            if (!$exists) {
                $tag = new TagsModel();
                $tag->tag = $name;
                $tag->save();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Add heart to tag
     *
     * @param $name
     * @param $userId
     * @return bool
     * @throws \Exception
     */
    public static function heartTag($name, $userId)
    {
        try {
            $tag = TagsModel::where('tag', '=', $name)->first();
            if ($tag) {
                if (!HeartModel::hasUserHearted($userId, $tag->id, 'ENT_HASHTAG')) {
                    HeartModel::addHeart($userId, $tag->id, 'ENT_HASHTAG');

                    $tag->hearts++;
                    $tag->save();

                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Remove heart from tag
     *
     * @param $name
     * @param $userId
     * @return bool
     * @throws \Exception
     */
    public static function unheartTag($name, $userId)
    {
        try {
            $tag = TagsModel::where('tag', '=', $name)->first();
            if ($tag) {
                if (HeartModel::hasUserHearted($userId, $tag->id, 'ENT_HASHTAG')) {
                    HeartModel::removeHeart($userId, $tag->id, 'ENT_HASHTAG');

                    $tag->hearts--;
                    $tag->save();

                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get list of popular tags
     *
     * @return mixed
     * @throws \Exception
     */
    public static function getPopularTags()
    {
        try {
            $tagList = Cache::remember('popular_tags', 60, function() {
                return TagsModel::where('locked', '=', false)->orderBy('hearts', 'desc')->limit(env('APP_TOPNTAGS'))->get();
            });
            return $tagList;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
