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
use Exception;

/**
 * Class ForumPostModel
 *
 * Interface to forum postings
 */
class ForumPostModel extends Model
{
    /**
     * Add forum thread posting
     * 
     * @param $threadId
     * @param $userId
     * @param $message
     * @return void
     * @throws Exception
     */
    public static function add($threadId, $userId, $message)
    {
        try {
            $thread = ForumThreadModel::where('id', '=', $threadId);
            if ((!$thread) || ($thread->locked)) {
                throw new Exception(__('app.thread_not_found_or_locked'));
            }

            $item = new ForumPostModel;
            $item->threadId = $threadId;
            $item->userId = $userId;
            $item->message = $message;
            $item->save();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get postings of a thread
     * 
     * @param $threadId
     * @param $paginate
     * @return mixed
     * @throws Exception
     */
    public static function getPosts($threadId, $paginate = null)
    {
        try {
            $query = ForumPostModel::where('locked', '=', false)->where('threadId', '=', $threadId);

            if ($paginate !== null) {
                $query->where('id', '>', $paginate);
            }

            $collection = $query->limit(env('APP_FORUMPACKLIMIT'))->get();

            foreach ($collection as &$item) {
                $item->user = User::get($item->userId);
                $item->diffForHumans = $item->created_at->diffForHumans();
            }

            return $collection->toArray();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
