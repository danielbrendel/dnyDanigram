<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace Tests\Feature\Models;

use App\AppModel;
use App\BookmarksModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookmarksModelTest extends TestCase
{
    public function testValidateEntityType()
    {
        BookmarksModel::validateEntityType('ENT_USER');
        $this->addToAssertionCount(1);

        $this->expectExceptionMessage('Invalid entity type: ENT_INVALID');
        BookmarksModel::validateEntityType('ENT_INVALID');
    }

    public function testAdd()
    {
        try {
            BookmarksModel::add(env('TEST_USERID'), env('TEST_ENTITY_HASHTAG'), 'ENT_HASHTAG');
            $result = BookmarksModel::where('userId', '=', env('TEST_USERID'))->where('entityId', '=', env('TEST_ENTITY_HASHTAG'))->count();
            $this->assertEquals($result, 1);

            BookmarksModel::add(env('TEST_USERID'), env('TEST_ENTITY_HASHTAG'), 'ENT_HASHTAG');
            $result = BookmarksModel::where('userId', '=', env('TEST_USERID'))->where('entityId', '=', env('TEST_ENTITY_HASHTAG'))->count();
            $this->assertEquals($result, 1);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends testAdd
     */
    public function testHasUserBookmarked()
    {
        try {
            $result = BookmarksModel::hasUserBookmarked(env('TEST_USERID'), env('TEST_ENTITY_HASHTAG'), 'ENT_HASHTAG');
            $this->assertTrue($result);

            $result = BookmarksModel::hasUserBookmarked(env('TEST_USERID_NONEXISTENT'), env('TEST_ENTITY_HASHTAG'), 'ENT_HASHTAG');
            $this->assertFalse($result);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends testHasUserBookmarked
     */
    public function testGetForUser()
    {
        try {
            $result = BookmarksModel::getForUser(env('TEST_USERID'));
            $this->assertIsObject($result);
            $this->assertTrue(count($result) > 0);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends testGetForUser
     */
    public function testRemove()
    {
        try {
            BookmarksModel::remove(env('TEST_USERID'), env('TEST_ENTITY_HASHTAG'), 'ENT_HASHTAG');
            $result = BookmarksModel::where('userId', '=', env('TEST_USERID'))->where('entityId', '=', env('TEST_ENTITY_HASHTAG'))->count();
            $this->assertEquals($result, 0);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}
