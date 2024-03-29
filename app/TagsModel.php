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
use App\HeartModel;

/**
 * Class TagsModel
 *
 * Represents the tag interface
 */
class TagsModel extends Model
{
    const HASHTAG_DEFAULT_PREVIEW = '_hashtag_preview.png';

    /**
     * Add tag to list
     *
     * @param $name
     * @return void
     * @throws Exception
     */
    public static function addTag($name)
    {
        try {
            $name = strtolower(str_replace('#', '', $name));

            $exists = TagsModel::where('tag', '=', $name)->first();
            if (!$exists) {
                $tag = new TagsModel();
                $tag->tag = $name;
                $tag->save();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Add heart to tag
     *
     * @param $name
     * @param $userId
     * @return bool
     * @throws Exception
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
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Remove heart from tag
     *
     * @param $name
     * @param $userId
     * @return bool
     * @throws Exception
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
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get list of popular tags
     *
     * @return mixed
     * @throws Exception
     */
    public static function getPopularTags()
    {
        try {
            $tagList = Cache::remember('popular_tags', 60, function() {
                return TagsModel::where('locked', '=', false)->orderBy('hearts', 'desc')->limit(env('APP_TOPNTAGS'))->get();
            });
            foreach ($tagList as &$tag) {
                $tag->total_posts = Cache::remember('tag_stats_posts_' . $tag->tag, 3600 * 24, function () use ($tag) {
                    return PostModel::where('hashtags', 'LIKE', '%' . $tag->tag . ' %')->count();
                });

                $tag->top_image = Cache::remember('tag_top_image_' . $tag->tag, 30, function() use ($tag) {
                    $post = PostModel::where('locked', '=', false)->where('nsfw', '=', false)->where('hashtags', 'LIKE', '%' . $tag->tag . ' %')->where('video', '=', false)->where('image_thumb', '<>', '_none')->orderBy('hearts', 'desc')->first();
                    if ($post) {
                        return $post->image_thumb;
                    }

                    return self::HASHTAG_DEFAULT_PREVIEW;
                });
            }
            return $tagList;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get top image for hashtag
     *
     * @param $hashtag
     * @return mixed
     */
    public static function getTopImage($hashtag)
    {
        return Cache::remember('tag_top_image_' . $hashtag, 30, function() use ($hashtag) {
            $post = PostModel::where('locked', '=', false)->where('nsfw', '=', false)->where('hashtags', 'LIKE', '%' . $hashtag . ' %')->where('video', '=', false)->orderBy('hearts', 'desc')->first();
            if ($post) {
                return $post->image_thumb;
            }

            return self::HASHTAG_DEFAULT_PREVIEW;
        });
    }
}
