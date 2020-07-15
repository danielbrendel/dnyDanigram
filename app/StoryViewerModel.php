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
 * Class StoryViewerModel
 *
 * Represents the interface for story visitors
 */
class StoryViewerModel extends Model
{
    /**
     * Add viewer to story
     *
     * @param $storyId
     * @param $userId
     * @throws \Exception
     */
    public static function addViewer($storyId, $userId)
    {
        try {
            $exists = StoryViewerModel::where('viewer', '=', $userId)->where('story', '=', $storyId)->count();
            if ($exists === 0) {
                $item = new StoryViewerModel();
                $item->viewer = $userId;
                $item->story = $storyId;
                $item->save();
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Indicate if a user as viewed a story
     *
     * @param $storyId
     * @param $userId
     * @return bool
     * @throws \Exception
     */
    public static function hasViewed($storyId, $userId)
    {
        try {
            $exists = StoryViewerModel::where('viewer', '=', $userId)->where('story', '=', $storyId)->count();

            return $exists > 0;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
