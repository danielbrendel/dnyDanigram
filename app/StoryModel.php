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

use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StoryModel
 *
 * Represents the interface to the stories
 */
class StoryModel extends Model
{
    /**
     * Add new story
     *
     * @param $userId
     * @param $text
     * @param $background
     * @param $text_color
     * @param $type
     * @throws \Exception
     */
    public static function add($userId, $text, $background, $text_color, $type)
    {
        try {
            $item = new StoryModel();
            $item->message = $text;
            $item->background = $background;
            $item->text_color = $text_color;
            $item->type = $type;
            $item->userId = $userId;
            $item->expired = false;
            $item->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Indicate whether a user shall be indicated for unseen stories
     *
     * @param $viewer
     * @param $poster
     * @return bool
     * @throws \Exception
     */
    public static function shallIndicate($viewer, $poster)
    {
        try {
            $stories = StoryModel::where('expired', '=', false)->where('userId', '=', $poster)->get();
            foreach ($stories as $story) {
                if (!StoryViewerModel::hasViewed($story->id, $viewer)) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * View a story list
     *
     * @param $viewerId
     * @param $posterId
     * @return mixed
     * @throws \Exception
     */
    public static function view($viewerId, $posterId)
    {
        try {
            $result = array();

            $stories = StoryModel::where('expired', '=', false)->where('userId', '=', $posterId)->get();
            if ($stories) {
                foreach ($stories as $story) {
                    if (!StoryViewerModel::hasViewed($story->id, $viewerId)) {
                        StoryViewerModel::addViewer($story->id, $viewerId);
                        $result[] = $story;
                    }
                }
            }

            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get a selection of unseen user stories
     *
     * @param $userId
     * @param $limit
     * @return array
     */
    public static function randomSelection($userId, $limit)
    {
        try {
            $result = array();
            $favs = FavoritesModel::where('userId', '=', $userId)->where('type', '=', 'ENT_USER')->limit($limit)->get();
            foreach ($favs as $fav) {
                $stories = StoryModel::where('userId', '=', $fav->entityId)->where('expired', '=', false)->get();
                foreach ($stories as $story) {
                    if (!StoryViewerModel::hasViewed($story->id, $userId)) {
                        $found = false;
                        foreach ($result as $exists) {
                            if ($exists->userId === $fav->entityId) {
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $story->user = User::get($fav->entityId);
                            $result[] = $story;
                        }
                    }
                }
            }

            return $result;
        } catch (\Exception $e) {

        }
    }

    /**
     * Check stories if expired and flag them accordingly
     *
     * @throws \Exception
     */
    public static function expireStory()
    {
        try {
            $stories = StoryModel::where('expired', '=', false)->get();
            foreach ($stories as $story) {
                $dtNow = new DateTime('now');
                $dtItem = new DateTime(date('Y-m-d H:i:s', strtotime($story->created_at)));
                $dtItem->add(new \DateInterval('PT' . env('APP_STORYDURATION', 24) . 'H'));
                if ($dtNow > $dtItem) {
                    $story->expired = true;
                    $story->save();
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
