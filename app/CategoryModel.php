<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2021 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\PostsModel;

/**
 * Class CategoryModel
 *
 * Represents interface to Categories
 */
class CategoryModel extends Model
{
    /**
     * Get all categories
     * 
     * @return mixed
     * @throws \Exception
     */
    public static function queryAll()
    {
        try {
            return CategoryModel::get();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
