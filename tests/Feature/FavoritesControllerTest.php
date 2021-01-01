<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2021 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoritesControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->post('/login', [
            'email' => env('TEST_USEREMAIL'),
            'password' => env('TEST_USERPW')
        ]);
    }

    public function testAdd()
    {
        $response = $this->post('/f/add?entityId=' . env('TEST_ENTITY_HASHTAG') . '&entType=ENT_HASHTAG');

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
    }

    /**
     * @depends testAdd
     */
    public function testRemove()
    {
        $response = $this->post('/f/remove?entityId=' . env('TEST_ENTITY_HASHTAG') . '&entType=ENT_HASHTAG');

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
    }
}
