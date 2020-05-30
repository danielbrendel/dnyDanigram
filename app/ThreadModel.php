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
            $thread = new ThreadModel();
            $thread->userId = $userId;
            $thread->postId = $postId;
            $thread->text = $text;
            $thread->save();

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
