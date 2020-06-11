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

class MainControllerTest extends TestCase
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
        $response = $this->get('/');

        $response->assertStatus(302);
        $response->assertRedirect('/feed');
    }

    public function testAbout()
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertViewIs('home.about');
    }

    public function testImprint()
    {
        $response = $this->get('/imprint');

        $response->assertStatus(200);
        $response->assertViewIs('home.imprint');
    }

    public function testTos()
    {
        $response = $this->get('/tos');

        $response->assertStatus(200);
        $response->assertViewIs('home.tos');
    }

    public function testFaq()
    {
        $response = $this->get('/faq');

        $response->assertStatus(200);
        $response->assertViewIs('home.faq');
    }

    public function testNews()
    {
        $response = $this->get('/news');

        $response->assertStatus(200);
        $response->assertViewIs('home.news');
    }

    public function testLogout()
    {
        $response = $this->get('/logout');

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }
}
