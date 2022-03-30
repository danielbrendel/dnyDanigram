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
     * @throws Exception
     */
    public static function queryAll()
    {
        try {
            return CategoryModel::get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Add new category item
     *
     * @param $attr
     * @return void
     * @throws Exception
     */
    public static function add($attr)
    {
        try {
            $item = new CategoryModel();
            $item->name = $attr['name'];
            $item->icon = $attr['icon'];
            $item->save();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Edit existing category item
     *
     * @param $id
     * @param $name
     * @param $icon
     * @return void
     * @throws Exception
     */
    public static function edit($id, $name, $icon = null)
    {
        try {
            $item = CategoryModel::where('id', '=', $id)->first();
            if (!$item) {
                throw new Exception('Category not found: ' . $id);
            }

            $item->name = $name;
            $item->icon = $icon;
            $item->save();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Remove an existing category item
     *
     * @param $id
     * @return void
     * @throws Exception
     */
    public static function remove($id)
    {
        try {
            $item = CategoryModel::where('id', '=', $id)->first();
            if (!$item) {
                throw new Exception('Category not found: ' . $id);
            }

            $item->delete();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
