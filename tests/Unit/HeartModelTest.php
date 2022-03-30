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
use App\HeartModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeartModelTest extends TestCase
{
    public function testAddHeart()
    {
        try {
            HeartModel::addHeart(env('TEST_USERID'), env('TEST_ENTITY_HASHTAG'), 'ENT_HASHTAG');

            $result = HeartModel::where('userId', '=', env('TEST_USERID'))->where('entityId', '=', env('TEST_ENTITY_HASHTAG'))->where('type', '=', 'ENT_HASHTAG')->count();
            $this->assertTrue($result > 0);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends testAddHeart
     */
    public function testHasUserHearted()
    {
        try {
            $result = HeartModel::hasUserHearted(env('TEST_USERID'), env('TEST_ENTITY_HASHTAG'), 'ENT_HASHTAG');
            $this->assertTrue($result);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends testHasUserHearted
     */
    public function testGetFromEntity()
    {
        try {
            $result = HeartModel::getFromEntity(env('TEST_ENTITY_HASHTAG'), 'ENT_HASHTAG');
            $this->assertIsObject($result);
            $this->assertTrue(count($result) > 0);
            $this->assertEquals(env('TEST_USERID'), $result[0]->userId);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends testGetFromEntity
     */
    public function testRemoveHeart()
    {
        try {
            HeartModel::removeHeart(env('TEST_USERID'), env('TEST_ENTITY_HASHTAG'), 'ENT_HASHTAG');

            $result = HeartModel::where('userId', '=', env('TEST_USERID'))->where('entityId', '=', env('TEST_ENTITY_HASHTAG'))->where('type', '=', 'ENT_HASHTAG')->count();
            $this->assertTrue($result == 0);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}
