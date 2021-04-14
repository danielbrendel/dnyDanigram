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

use Exception;
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
     * @throws Exception
     */
    private static function validateEntityType($type)
    {
        try {
            $types = array('ENT_POST', 'ENT_HASHTAG', 'ENT_COMMENT');

            if (!in_array($type, $types)) {
                throw new Exception('Unknown type: ' . $type, 404);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Add heart to post if not already
     *
     * @param $userId
     * @param $entityId
     * @param $entType
     * @throws Exception
     */
    public static function addHeart($userId, $entityId, $entType)
    {
        try {
            static::validateEntityType($entType);

            $heart = HeartModel::where('userId', '=', $userId)->where('entityId', '=', $entityId)->where('type', '=', $entType)->first();
            if ($heart) {
                throw new Exception(__('app.already_hearted'));
            }

            $heart = new HeartModel;
            $heart->userId = $userId;
            $heart->entityId = $entityId;
            $heart->type = $entType;
            $heart->save();

            $user = User::get($userId);

            if ($entType === 'ENT_POST') {
                $post = PostModel::where('id', '=', $entityId)->first();
                if ($post) {
                    $post->hearts++;
                    $post->save();

                    if ($userId !== $post->userId) {
                        PushModel::addNotification(__('app.user_hearted_post_short', ['name' => $user->username]), __('app.user_hearted_post', ['name' => $user->username, 'item' => url('/p/' . $entityId)]), 'PUSH_HEARTED', $post->userId);
                    }
                }
            } else if ($entType === 'ENT_HASHTAG') {
                $tag = TagsModel::where('id', '=', $entityId)->first();
                if ($tag) {
                    $tag->hearts++;
                    $tag->save();
                }
            } else if ($entType === 'ENT_COMMENT') {
                $comment = ThreadModel::where('id', '=', $entityId)->first();
                if ($comment) {
                    $comment->hearts++;
                    $comment->save();

                    if ($userId !== $comment->userId) {
                        PushModel::addNotification(__('app.user_hearted_comment_short', ['name' => $user->username]), __('app.user_hearted_comment', ['name' => $user->username, 'item' => url('/p/' . $comment->postId . '#' . $entityId)]), 'PUSH_HEARTED', $comment->userId);
                    }
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Remove heart of post
     * @param $userId
     * @param $entityId
     * @param $entType
     * @throws Exception
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

            if ($entType === 'ENT_POST') {
                $post = PostModel::where('id', '=', $entityId)->first();
                if ($post) {
                    $post->hearts--;
                    $post->save();
                }
            } else if ($entType === 'ENT_HASHTAG') {
                $tag = TagsModel::where('id', '=', $entityId)->first();
                if ($tag) {
                    $tag->hearts--;
                    $tag->save();
                }
            } else if ($entType === 'ENT_COMMENT') {
                $comment = ThreadModel::where('id', '=', $entityId)->first();
                if ($comment) {
                    $comment->hearts--;
                    $comment->save();
                }
            }
        } catch (Exception $e) {
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
     * @throws Exception
     */
    public static function hasUserHearted($userId, $entityId, $entType)
    {
        try {
            static::validateEntityType($entType);

            $heart = HeartModel::where('userId', '=', $userId)->where('entityId', '=', $entityId)->where('type', '=', $entType)->first();

            return $heart !== null;
        } catch (\Execption $e) {
            throw $e;
        }
    }

    /**
     * Get all hearts from a specific entity
     * @param $entityId
     * @param $entType
     * @return mixed
     * @throws Exception
     */
    public static function getFromEntity($entityId, $entType)
    {
        try {
            static::validateEntityType($entType);

            $rowset = HeartModel::where('entityId', '=', $entityId)->where('type', '=', $entType)->get();

            return $rowset;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
