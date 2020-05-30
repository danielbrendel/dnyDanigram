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
 * Class ReportModel
 *
 * Represents the interface for reporting
 */
class ReportModel extends Model
{
    /**
     * Throw if type is unknown
     *
     * @param $type
     * @throws \Exception
     */
    private static function validateEntityType($type)
    {
        try {
            $types = array('ENT_POST', 'ENT_HASHTAG', 'ENT_COMMENT', 'ENT_USER');

            if (!in_array($type, $types)) {
                throw new \Exception('Unknown type: ' . $type, 404);
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Add report to post if not already
     *
     * @param $userId
     * @param $entityId
     * @param $entType
     * @throws \Exception
     */
    public static function addReport($userId, $entityId, $entType)
    {
        try {
            static::validateEntityType($entType);

            $report = ReportModel::where('userId', '=', $userId)->where('entityId', '=', $entityId)->where('type', '=', $entType)->first();
            if ($report) {
                throw new \Exception(__('app.already_reported'));
            }

            $report = new HeartModel;
            $report->userId = $userId;
            $report->entityId = $entityId;
            $report->type = $entType;
            $report->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
