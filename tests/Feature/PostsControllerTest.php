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

class PostsControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->post('/login', [
            'email' => env('TEST_USEREMAIL'),
            'password' => env('TEST_USERPW')
        ]);
    }

    public function testViewUpload()
    {
        $response = $this->get('/upload');

        $response->assertStatus(200);
        $response->assertViewIs('member.upload');
    }

    public function testShowPost()
    {
        $response = $this->get('/p/' . env('TEST_POSTID'));

        $response->assertStatus(200);
        $response->assertViewIs('member.showpost');
    }

    public function testFeed()
    {
        $response = $this->get('/feed');

        $response->assertStatus(200);
        $response->assertViewIs('member.index');
    }

    public function testHashtag()
    {
        $response = $this->get('/t/' . env('TEST_HASHTAG_NAME'));

        $response->assertStatus(200);
        $response->assertViewIs('member.hashtag');
    }

    public function testFetchPosts()
    {
        $response = $this->get('/fetch/posts');

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
        $this->assertTrue(isset($content->data));
        $this->assertTrue(isset($content->last));
    }

    public function testFetchThread()
    {
        $response = $this->get('/fetch/thread?post=' . env('TEST_POSTID'));

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
        $this->assertTrue(isset($content->data));
        $this->assertTrue(isset($content->last));
    }

    public function testFetchSubThread()
    {
        $response = $this->get('/c/subthread?parent=' . env('TEST_THREADID'));

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
        $this->assertTrue(isset($content->data));
        $this->assertTrue(isset($content->last));
    }

    public function testAddThread()
    {
        $response = $this->post('/p/' . env('TEST_POSTID') . '/thread/add', [
            'message' => md5(random_bytes(55))
        ]);

        $response->assertStatus(302);
        $response->assertRedirect();
    }

    public function testReplyThread()
    {
        $response = $this->post('/c/reply?parent=' . env('TEST_THREADID'), [
            'text' => md5(random_bytes(55))
        ]);

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
        $this->assertTrue(isset($content->post));
    }

    public function testHeart()
    {
        $response = $this->post('/heart?entity=' . env('TEST_POSTID') . '&type=ENT_POST&value=1');

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
        $this->assertTrue(isset($content->value));
        $this->assertTrue(isset($content->count));

        $response = $this->post('/heart?entity=' . env('TEST_POSTID') . '&type=ENT_POST&value=0');

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
        $this->assertTrue(isset($content->value));
        $this->assertTrue(isset($content->count));
    }

    public function testFetchSinglePost()
    {
        $response = $this->get('/fetch/post?post=' . env('TEST_POSTID'));

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
        $this->assertTrue(isset($content->elem));
    }

    public function testReportPost()
    {
        $response = $this->post('/p/' . env('TEST_POSTID') . '/report');

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
    }

    public function testReportTag()
    {
        $response = $this->get('/t/' . env('TEST_ENTITY_HASHTAG') . '/report');

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
    }

    public function testReportComment()
    {
        $response = $this->post('/comment/report?comment=' . env('TEST_COMMENTID'));

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
    }

    public function testEditComment()
    {
        $response = $this->post('/comment/edit', [
            'comment' => env('TEST_COMMENTID'),
            'text' => md5(random_bytes(55))
        ]);

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
    }

    public function testDeleteComment()
    {
        $response = $this->post('/comment/delete', [
            'comment' => env('TEST_COMMENTID')
        ]);

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
    }

    public function testExpireStories()
    {
        $response = $this->get('/stories/expire');

        $response->assertStatus(200);
        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
    }
}
