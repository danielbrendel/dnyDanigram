<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2022 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace App\Http\Controllers;

use App\PushModel;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
	/**
     * Constructor
     */
	public function __construct()
	{
		parent::__construct();
	}
	
    /**
     * Get notification list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        try {
            $mark = (bool)request('mark', true);

            $notifications = PushModel::getUnseenNotifications(auth()->id(), $mark);

            return response()->json(array('code' => 200, 'data' => $notifications));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Fetch notifications
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch()
    {
        try {
            $paginate = request('paginate', null);

            $notifications = PushModel::getNotifications(auth()->id(), env('APP_PUSHPACKLIMIT'), $paginate);

            return response()->json(array('code' => 200, 'data' => $notifications));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Mark notifications as seen
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function mark()
    {
        try {
            PushModel::markSeen(auth()->id());

            return response()->json(array('code' => 200));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }
}
