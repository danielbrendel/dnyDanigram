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
use App\ForumPostModel;
use App\User;

/**
 * Class ForumThreadModel
 *
 * Interface to forum thread
 */
class ForumThreadModel extends Model
{
    /**
     * Add new forum thread
     * 
     * @param $ownerId
     * @param $forumId
     * @param $title
     * @param $initialMessage
     * @return void
     * @throws Exception
     */
    public static function add($ownerId, $forumId, $title, $initialMessage)
    {
        try {
            $item = new ForumThreadModel;
            $item->ownerId = $ownerId;
            $item->forumId = $forumId;
            $item->title = $title;
            $item->save();

            ForumPostModel::add($item->id, $ownerId, $initialMessage);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get list of threads
     * 
     * @param $forumId
     * @param $paginate
     * @return mixed
     * @throws Exception
     */
    public static function list($forumId, $paginate = null)
    {
        try {
            $query = ForumThreadModel::where('locked', '=', false)->where('forumId', '=', $forumId);

            if ($paginate !== null) {
                $query->where('id', '<', $paginate);
            }

            $collection = $query->orderBy('id', 'desc')->limit(env('APP_FORUMPACKLIMIT'))->get();

            foreach ($collection as &$item) {
                $item->user = User::where('id', '=', $item->ownerId)->first()->toArray();
            }

            return $collection->toArray();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
