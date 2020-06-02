<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FavoritesModel;

class FavoritesController extends Controller
{
    /**
     * Add favorite
     * @return \Illuminate\Http\JsonResponse
     */
    public function add()
    {
        try {
            $entityId = request('entityId');
            $entType = request('entType');

            FavoritesModel::add(auth()->id(), $entityId, $entType);

            return response()->json(array('code' => 200, 'msg' => __('app.favorite_added')));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }

    /**
     * Remove favorite
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove()
    {
        try {
            $entityId = request('entityId');
            $entType = request('entType');

            FavoritesModel::remove(auth()->id(), $entityId, $entType);

            return response()->json(array('code' => 200, 'msg' => __('app.favorite_removed')));
        } catch (\Exception $e) {
            return response()->json(array('code' => 500, 'msg' => $e->getMessage()));
        }
    }
}
