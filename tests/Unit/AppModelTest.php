<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2021 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace Tests\Feature\Models;

use App\AppModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppModelTest extends TestCase
{
    public function testGetNameParts()
    {
        $_ENV['APP_NAME'] = 'Danigram';
        $_ENV['APP_DIVISION'] = 4;
        $result = AppModel::getNameParts();
        $this->assertIsArray($result);
        $this->assertEquals('Dani', $result[0]);
        $this->assertEquals('gram', $result[1]);
    }

    public function testGetIndexContent()
    {
        $result = AppModel::getIndexContent();
        $this->assertIsString($result);
    }

    public function testGetCookieConsentText()
    {
        $result = AppModel::getCookieConsentText();
        $this->assertIsString($result);
    }

    public function testGetAboutContent()
    {
        $result = AppModel::getAboutContent();
        $this->assertIsString($result);
    }

    public function testGetTermsOfService()
    {
        $result = AppModel::getTermsOfService();
        $this->assertIsString($result);
    }

    public function testGetImprint()
    {
        $result = AppModel::getImprint();
        $this->assertIsString($result);
    }

    public function testGetRegInfo()
    {
        $result = AppModel::getRegInfo();
        $this->assertIsString($result);
    }

    public function testIsValidNameIdent()
    {
        $valid = AppModel::isValidNameIdent('username');
        $this->assertTrue($valid);
        $valid = AppModel::isValidNameIdent('username123');
        $this->assertTrue($valid);
        $valid = AppModel::isValidNameIdent('user_name-34');
        $this->assertTrue($valid);

        $invalid = AppModel::isValidNameIdent('user$name');
        $this->assertFalse($invalid);
        $invalid = AppModel::isValidNameIdent('user#name');
        $this->assertFalse($invalid);
    }

    public function testGetImageType()
    {
        $result = AppModel::getImageType(public_path() . '/gfx/avatars/default.png');
        $this->assertEquals(IMAGETYPE_PNG, $result);

        $result = AppModel::getImageType('does not exist');
        $this->assertEquals(null, $result);
    }

    public function testGetMentionList()
    {
        $string = 'Test Test @test @username @another Test';
        $list = AppModel::getMentionList($string);
        $this->assertCount(3, $list);
        $this->assertEquals('test', $list[0]);
        $this->assertEquals('username', $list[1]);
        $this->assertEquals('another', $list[2]);
    }
}
