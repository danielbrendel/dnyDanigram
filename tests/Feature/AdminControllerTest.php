<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2022 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->post('/login', [
           'email' => env('TEST_USEREMAIL'),
           'password' => env('TEST_USERPW')
        ]);
    }

    public function testLockPost()
    {
        $response = $this->get('/p/' . env('TEST_POSTID') . '/lock');

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
    }

    public function testLockHashtag()
    {
        $response = $this->get('/t/' . env('TEST_ENTITY_HASHTAG') . '/lock');

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
    }

    public function testDeactivateUser()
    {
        $response = $this->get('/u/' . env('TEST_USERID2') . '/deactivate');

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
    }

    public function testLockComment()
    {
        $response = $this->get('/c/' . env('TEST_COMMENT') . '/lock');

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
    }
}
