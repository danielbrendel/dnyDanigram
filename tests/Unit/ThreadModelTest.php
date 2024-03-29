<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2022 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace Tests\Feature\Models;

use App\AppModel;
use App\FavoritesModel;
use App\CaptchaModel;
use App\FaqModel;
use App\PostModel;
use App\ThreadModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreadModelTest extends TestCase
{
    public function testAdd()
    {
        try {
            $text = md5(random_bytes(55));

            ThreadModel::add(env('TEST_USERID'), env('TEST_POSTID'), $text);

            $result = ThreadModel::where('userId', '=', env('TEST_USERID'))->where('postId', '=', env('TEST_POSTID'))->where('text', '=', $text)->first();
            $this->assertIsObject($result);
            $this->assertEquals($text, $result->text);

            return $result->id;
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }

    /**
     * @depends testAdd
     */
    public function testEdit($id)
    {
        try {
            $text = md5(random_bytes(55));

            ThreadModel::edit($id, $text, env('TEST_USERID'));

            $result = ThreadModel::where('id', '=', $id)->first();
            $this->assertEquals($text, $result->text);

            return $id;
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }

    /**
     * @depends testEdit
     */
    public function testGetFromPost($id)
    {
        try {
            $result = ThreadModel::getFromPost(env('TEST_POSTID'), env('TEST_FETCHLIMIT'));
            $this->assertIsObject($result);
            foreach ($result as $item) {
                $this->assertIsObject($item);
                $this->assertTrue(isset($item->text));
            }

            return $id;
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }

    /**
     * @param $id
     * @depends testGetFromPost
     */
    public function testRemove($id)
    {
        try {
            ThreadModel::remove($id, env('TEST_USERID'));

            $result = ThreadModel::where('id', '=', $id)->count();
            $this->assertEquals(0, $result);
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }
}
