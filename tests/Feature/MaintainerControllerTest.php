<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintainerControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->post('/login', [
            'email' => env('TEST_USEREMAIL'),
            'password' => env('TEST_USERPW')
        ]);
    }

    public function testIndex()
    {
        $response = $this->get('/maintainer');
        $response->assertStatus(200);
        $response->assertViewIs('maintainer.index');
    }

    public function testSave()
    {
        $response = $this->post('/maintainer/save', [
            'attribute' => 'about',
            'content' => md5(random_bytes(55))
        ]);

        $response->assertStatus(302);
    }

    public function testAddFaq()
    {
        $response = $this->post('/maintainer/faq/create', [
            'question' => md5(random_bytes(55)),
            'answer' => md5(random_bytes(55))
        ]);

        $response->assertStatus(302);
    }

    public function testEditFaq()
    {
        $response = $this->post('/maintainer/faq/edit', [
            'id' => env('TEST_FAQID'),
            'question' => md5(random_bytes(55)),
            'answer' => md5(random_bytes(55))
        ]);

        $response->assertStatus(302);
    }

    public function testSaveEnv()
    {
        $response = $this->post('/maintainer/env/save', [
            'ENV_APP_DESCRIPTION' => md5(random_bytes(55))
        ]);

        $response->assertStatus(302);
    }

    public function testUserDetails()
    {
        $response = $this->get('/maintainer/u/details?ident=' . env('TEST_USERID'));
        $response->assertStatus(200);

        $content = json_decode($response->getContent());
        $this->assertEquals(200, $content->code);
        $this->assertIsObject($content->data);
    }

    public function testNewsletter()
    {
        $response = $this->post('/maintainer/newsletter', [
            'subject' => md5(random_bytes(55)),
            'content' => md5(random_bytes(55))
        ]);

        $response->assertStatus(302);
        $response->assertRedirect();
    }

    public function testWelcomeContent()
    {
        $response = $this->post('/maintainer/welcomecontent', [
            'content' => md5(random_bytes(55))
        ]);

        $response->assertStatus(302);
        $response->assertRedirect();
    }

    public function testSaveFormattedProjectName()
    {
        $response = $this->post('/maintainer/formattedprojectname', [
            'content' => '<strong>' . md5(random_bytes(55)) . '</strong>'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect();
    }
}
