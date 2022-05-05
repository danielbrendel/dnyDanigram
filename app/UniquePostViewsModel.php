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

/**
 * Class UniquePostViewsModel
 *
 * Interface to unique post views
 */
class UniquePostViewsModel extends Model
{
    const COUNT_MILLION = 1000000;
    const COUNT_HUNDREDTHOUSAND = 100000;
    const COUNT_TENTHOUSAND = 10000;
    const COUNT_THOUSAND = 1000;

    /**
     * Add hashed IP address as viewer for given post and return view count
     *
     * @param $postId
     * @return int
     * @throws Exception
     */
    public static function viewForPost($postId)
    {
        try {
            $count = 0;
            $token = md5(request()->ip());

            $item = UniquePostViewsModel::where('postId', '=', $postId)->where('token', '=', $token)->first();
            if (!$item) {
                $item = new UniquePostViewsModel();
                $item->postId = $postId;
                $item->token = $token;
                $item->save();
            }

            $count = Cache::remember('view_for_post_' . $postId, 60 * 15, function() use ($postId) {
                return UniquePostViewsModel::where('postId', '=', $postId)->count();
            });

            return $count;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Generate a string representation for the view count
     *
     * @param $count
     * @return string
     * @throws Exception
     */
    public static function viewCountAsString($count)
    {
        try {
            if ($count >= self::COUNT_MILLION) {
                return strval(round($count / self::COUNT_MILLION, 1)) . 'M';
            } else if (($count < self::COUNT_MILLION) && ($count >= self::COUNT_HUNDREDTHOUSAND)) {
                return strval(round($count / self::COUNT_THOUSAND, 1)) . 'K';
            } else if (($count < self::COUNT_HUNDREDTHOUSAND) && ($count >= self::COUNT_TENTHOUSAND)) {
                return strval(round($count / self::COUNT_THOUSAND, 1)) . 'K';
            } else if (($count < self::COUNT_TENTHOUSAND) && ($count >= self::COUNT_THOUSAND)) {
                return strval(round($count / self::COUNT_THOUSAND, 1)) . 'K';
            } else {
                return strval($count);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}
