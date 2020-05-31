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

class ThreadModel extends Model
{
    /**
     * Add thread comment
     *
     * @param $userId
     * @param $postId
     * @param $text
     * @return mixed
     * @throws \Exception
     */
    public static function add($userId, $postId, $text)
    {
        try {
            $post = PostModel::getPost($postId);
            if (!$post) {
                throw new \Exception('Post not found: ' . $postId);
            }

            $thread = new ThreadModel();
            $thread->userId = $userId;
            $thread->postId = $postId;
            $thread->text = $text;
            $thread->save();

            $user = User::get($post->userId);
            if (($user) && ($userId !== $post->userId)) {
                PushModel::addNotification(__('app.user_posted_comment_short', ['name' => $user->username]), __('app.user_posted_comment', ['name' => $user->username, 'msg' => $text, 'item' => url('/p/' . $postId . '#' . $thread->id)]), 'PUSH_COMMENTED', $user->id);
            }

            $highlightNames = AppModel::getHighlightList($text);
            foreach ($highlightNames as $name) {
                $curUser = User::getByUsername($name);
                if ($curUser) {
                    PushModel::addNotification(__('app.user_highlighted_short', ['name' => $user->username]), __('app.user_highlighted', ['name' => $user->username, 'item' => url('/p/' . $post->id . '#' . $thread->id)]), 'PUSH_HIGHLIGHTED', $curUser->id);
                }
            }

            return $thread->id;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Remove thread entry
     *
     * @param $threadId
     * @throws \Exception
     */
    public static function remove($threadId)
    {
        try {
            $thread = ThreadModel::where('id', '=', $threadId)->where('userId', '=', auth()->id())->first();
            if ($thread) {
                $thread->delete();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Edit thread entry
     *
     * @param $threadId
     * @param $newText
     * @throws \Exception
     */
    public static function edit($threadId, $newText)
    {
        try {
            $thread = ThreadModel::where('id', '=', $threadId)->where('userId', '=', auth()->id())->first();
            if ($thread) {
                $thread->text = $newText;
                $thread->save();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get thread posts from image post
     * @param $id
     * @param null $paginate
     * @return mixed
     * @throws \Exception
     */
    public static function getFromPost($id, $paginate = null)
    {
        try {
            $threads = ThreadModel::where('postId', '=', $id)->where('locked', '=', false);
            if ($paginate !== null) {
                $threads->where('id', '<', $paginate);
            }

            return $threads->orderBy('id', 'desc')->limit(env('APP_THREADPACKLIMIT'))->get();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
