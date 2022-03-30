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
use App\TagsModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagsModelTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testAddTag()
    {
        try {
            $tagname = md5(random_bytes(55));

            TagsModel::addTag('#' . $tagname);

            $result = TagsModel::where('tag', '=', $tagname)->count();
            $this->assertEquals(1, $result);

            return $tagname;
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends testAddTag
     * @param $tagname
     * @return string
     */
    public function testHeartTag($tagname)
    {
        try {
            $result = TagsModel::heartTag($tagname, env('TEST_USERID'));
            $this->assertTrue($result);

            return $tagname;
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends testHeartTag
     * @param $tagname
     * @return string
     */
    public function testGetPopularTags($tagname)
    {
        try {
            $result = TagsModel::getPopularTags();
            $this->assertIsObject($result);
            foreach ($result as $item) {
                $this->assertIsObject($item);
                $this->assertTrue(isset($item->tag));
            }

            return $tagname;
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @depends testGetPopularTags
     * @param $tagname
     */
    public function testUnheartTag($tagname)
    {
        try {
            $result = TagsModel::unheartTag($tagname, env('TEST_USERID'));
            $this->assertTrue($result);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}
