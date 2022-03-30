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
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    public function testGet()
    {
        try {
            $result = User::get(env('TEST_USERID'));
            $this->assertIsObject($result);
            $this->assertEquals(env('TEST_USERID'), $result->id);
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }

    public function testIsAdmin()
    {
        try {
            $result = User::isAdmin(env('TEST_USERID'));
            $this->assertTrue($result);
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }

    public function testGetByEmail()
    {
        try {
            $result = User::getByEmail(env('TEST_USEREMAIL'));
            $this->assertEquals(env('TEST_USEREMAIL'), $result->email);
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }

    public function testGetByUsername()
    {
        try {
            $result = User::getByUsername(env('TEST_USERNAME'));
            $this->assertEquals(env('TEST_USERNAME'), $result->username);
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }

    public function testRegister()
    {
        $this->markTestSkipped();

        try {
            $_SESSION['PHPSESSID'] = 'TestCase';

            $captcha = CaptchaModel::createSum($_SESSION['PHPSESSID']);

            $attr = array(
                'username' => md5(random_bytes(55)),
                'email' => md5(random_bytes(55)) . '@domain.tld',
                'password' => 'password',
                'password_confirmation' => 'password',
                'captcha' => $captcha[0] + $captcha[1]
            );

            User::register($attr);

            return $attr['username'];
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }

    /**
     * @depends testRegister
     */
    public function testConfirm($username)
    {
        try {
            $hash = User::where('username', '=', $username)->first()->account_confirm;

            User::confirm($hash);

            $value = User::where('username', '=', $username)->where('account_confirm', '=', '_confirmed')->count();
            $this->assertEquals(1, $value);
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }

    public function testChangeUsername()
    {
        try {
            $name = md5(random_bytes(55));

            User::changeUsername(env('TEST_USERID2'), $name);

            $value = User::where('username', '=', $name)->count();
            $this->assertEquals(1, $value);
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }

    public function testChangeBio()
    {
        try {
            $bio = md5(random_bytes(55));

            User::changeBio(env('TEST_USERID2'), $bio);

            $value = User::where('bio', '=', $bio)->count();
            $this->assertEquals(1, $value);
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }

    public function testChangePassword()
    {
        try {
            $pw = md5(random_bytes(55));

            User::changePassword(env('TEST_USERID2'), $pw);

            $this->addToAssertionCount(1);
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }

    public function testChangeEmail()
    {
        try {
            $email = md5(random_bytes(55)) . '@domain.tld';

            User::changeEMail(env('TEST_USERID2'), $email);

            $value = User::where('email', '=', $email)->count();
            $this->assertEquals(1, $value);
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }

    public function testGetStats()
    {
        try {
            $result = User::getStats(env('TEST_USERID'));
            $this->assertIsObject($result);
            $this->assertTrue(isset($result->posts));
            $this->assertTrue(isset($result->comments));
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }
}
