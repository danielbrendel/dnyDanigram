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

/**
 * Class ThreadModel
 *
 * Represents the interface to post comment threads
 */
class ThreadModel extends Model
{
    const MAX_PREVIEW_MSG = 25;

    /**
     * Add thread comment
     *
     * @param $userId
     * @param $postId
     * @param $text
     * @return mixed
     * @throws Exception
     */
    public static function add($userId, $postId, $text)
    {
        try {
            $post = PostModel::getPost($postId);
            if (!$post) {
                throw new Exception('Post not found: ' . $postId);
            }

            $thread = new ThreadModel();
            $thread->userId = $userId;
            $thread->postId = $postId;
            $thread->text = \Purifier::clean($text);
            $thread->save();

            $user = User::get($post->userId);
            if (($user) && ($userId !== $post->userId)) {
                if (!IgnoreModel::hasIgnored($post->userId, $userId)) {
                    PushModel::addNotification(__('app.user_posted_comment_short', ['name' => $user->username]), __('app.user_posted_comment', ['name' => $user->username, 'msg' => ((strlen($text) > self::MAX_PREVIEW_MSG) ? substr($text, 0, self::MAX_PREVIEW_MSG) . '...' : $text), 'item' => url('/p/' . $postId . '?c=' . $thread->id . '#' . $thread->id)]), 'PUSH_COMMENTED', $user->id);
                }
            }

            $mentionedNames = AppModel::getMentionList($text);
            foreach ($mentionedNames as $name) {
                $curUser = User::getByUsername($name);
                if ($curUser) {
                    if (!IgnoreModel::hasIgnored($curUser->id, $userId)) {
                        PushModel::addNotification(__('app.user_mentioned_short', ['name' => $user->username]), __('app.user_mentioned', ['name' => $user->username, 'item' => url('/p/' . $post->id . '#' . $thread->id)]), 'PUSH_MENTIONED', $curUser->id);
                    }
                }
            }

            return $thread->id;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Remove thread entry
     *
     * @param $threadId
     * @param $userId
     * @return void
     * @throws Exception
     */
    public static function remove($threadId, $userId = null)
    {
        try {
            if ($userId === null) {
                $userId = auth()->id();
            }

            $thread = ThreadModel::where('id', '=', $threadId)->where('userId', '=', $userId)->first();
            if ($thread) {
                $thread->delete();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Edit thread entry
     *
     * @param $threadId
     * @param $newText
     * @param null $userId
     * @return void
     * @throws Exception
     */
    public static function edit($threadId, $newText, $userId = null)
    {
        try {
            if ($userId === null) {
                $userId = auth()->id();
            }

            $thread = ThreadModel::where('id', '=', $threadId)->where('userId', '=', $userId)->first();
            if ($thread) {
                $thread->text = $newText;
                $thread->save();
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get thread posts from image post
     * @param $id
     * @param null $paginate
     * @return mixed
     * @throws Exception
     */
    public static function getFromPost($id, $paginate = null)
    {
        try {
            $threads = ThreadModel::where('postId', '=', $id)->where('locked', '=', false)->where('parentId', '=', 0);
            if ($paginate !== null) {
                $threads->where('id', '<', $paginate);
            }

            return $threads->orderBy('id', 'desc')->limit(env('APP_THREADPACKLIMIT'))->get()->toArray();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get sub thread count
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public static function getSubCount($id)
    {
        try {
            return ThreadModel::where('parentId', '=', $id)->where('locked', '=', false)->count();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get sub thread posts
     *
     * @param $id
     * @param null $paginate
     * @return mixed
     * @throws Exception
     */
    public static function getSubPosts($id, $paginate = null)
    {
        try {
            $threads = ThreadModel::where('parentId', '=', $id)->where('locked', '=', false);
            if ($paginate !== null) {
                $threads->where('id', '>', $paginate);
            }

            return $threads->orderBy('id', 'asc')->limit(env('APP_THREADPACKLIMIT'))->get()->toArray();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Add thread reply message
     *
     * @param $userId
     * @param $parentId
     * @param $text
     * @return ThreadModel
     * @throws Exception
     */
    public static function reply($userId, $parentId, $text)
    {
        try {
            $parent = ThreadModel::where('id', '=', $parentId)->where('locked', '=', false)->where('parentId', '=', 0)->first();
            if (!$parent) {
                throw new Exception('Parent item not found for ' . $parentId);
            }

            $thread = new ThreadModel();
            $thread->userId = $userId;
            $thread->postId = $parent->postId;
            $thread->parentId = $parentId;
            $thread->text = $text;
            $thread->save();

            $user = User::get($parent->userId);
            if (($user) && ($userId !== $parent->userId)) {
                if (!IgnoreModel::hasIgnored($parent->userId, $userId)) {
                    PushModel::addNotification(__('app.user_replied_comment_short', ['name' => $user->username]), __('app.user_replied_comment', ['name' => $user->username, 'msg' => ((strlen($text) > self::MAX_PREVIEW_MSG) ? substr($text, 0, self::MAX_PREVIEW_MSG) . '...' : $text), 'item' => url('/p/' . $parent->postId . '?c=' . $thread->id . '#' . $thread->id)]), 'PUSH_COMMENTED', $user->id);
                }
            }

            $mentionedNames = AppModel::getMentionList($text);
            foreach ($mentionedNames as $name) {
                $curUser = User::getByUsername($name);
                if ($curUser) {
                    if (!IgnoreModel::hasIgnored($curUser->id, $userId)) {
                        PushModel::addNotification(__('app.user_mentioned_short', ['name' => $user->username]), __('app.user_mentioned', ['name' => $user->username, 'item' => url('/p/' . $parent->postId . '#' . $thread->id)]), 'PUSH_MENTIONED', $curUser->id);
                    }
                }
            }

            return $thread;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
