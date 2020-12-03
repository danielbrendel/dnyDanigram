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
 * Class ForumModel
 *
 * Interface to forum
 */
class ForumModel extends Model
{
    /**
     * Create new forum
     * 
     * @param $name
     * @param $description
     * @return int
     * @throws Exception
     */
    public static function add($name, $description)
    {
        try {
            $item = new ForumModel;
            $item->name = $name;
            $item->description = $description;
            $item->locked = false;
            $item->save();

            return $item->id;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Edit existing forum
     * 
     * @param $id
     * @param $name
     * @param $description
     * @return void
     * @throws Exception
     */
    public static function edit($id, $name, $description)
    {
        try {
            $item = ForumModel::where('locked', '=', false)->where('id', '=', $id)->first();
            if (!$item) {
                throw new Exception('Item not found: ' . $id);
            }

            $item->name = $name;
            $item->description = $description;
            $item->save();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Lock/Unlock forum
     * 
     * @param $id
     * @return void
     * @throws Exception 
     */
    public static function lock($id, $locked = true)
    {
        try {
            $item = ForumModel::where('locked', '=', false)->where('id', '=', $id)->first();
            if (!$item) {
                throw new Exception('Item not found: ' . $id);
            }

            $item->locked = $locked;
            $item->save();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Query forum list
     * 
     * @param $paginate
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public static function queryList($paginate, $name)
    {
        try {
            $query = ForumModel::where('locked', '=', false);

            if ($paginate !== null) {
                $query->where('id', '>', $paginate);
            }

            if ((is_string($name)) && (strlen($name) > 0)) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . trim(strtolower($name)) . '%']);
            }

            return $query->limit(env('APP_FORUMPACKLIMIT'))->get()->toArray();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
