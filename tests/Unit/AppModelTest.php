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

    public function testGetWelcomeContent()
    {
        $result = AppModel::getWelcomeContent();
        $this->assertIsString($result);
    }

    public function testGetFormattedProjectName()
    {
        $result = AppModel::getFormattedProjectName();
        $this->assertTrue(is_null($result) || is_string($result));
    }

    public function testGetDefaultTheme()
    {
        $result = AppModel::getDefaultTheme();
        $this->assertIsString($result);
    }

    public function testGetHeadCode()
    {
        $result = AppModel::getHeadCode();
        $this->assertIsString($result);
    }

    public function testGetAdCode()
    {
        $result = AppModel::getAdCode();
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

    public function testGetSettings()
    {
        $result = AppModel::getSettings();
        $this->assertIsObject($result);
    }

    public function testSaveSetting()
    {
        $orig = AppModel::getAdCode();
        $new = md5(random_bytes(55));
        AppModel::saveSetting('adcode', $new);
        \Artisan::call('cache:clear');
        $saved = AppModel::getAdCode();
        $this->assertEquals($saved, $new);
        AppModel::saveSetting('adcode', $orig);
    }

    public function testGetRandomPassword()
    {
        $result = AppModel::getRandomPassword(10);
        $this->assertIsString($result);
        $this->assertTrue(strlen($result) === 10);
    }

    public function testGetLanguageList()
    {
        $langs = [
            'en',
            'de'
        ];

        $result = AppModel::getLanguageList();
        $this->assertIsArray($result);

        foreach ($langs as $lang) {
            $this->assertTrue(in_array($lang, $result));
        }
    }

    public function testGetShortExpression()
    {
        $result = AppModel::getShortExpression('abcdefghijklmnopqrstuvwxyz');
        $this->assertIsString($result);
        $this->assertTrue(strlen($result) === AppModel::MAX_EXPRESSION_LENGTH + 3);
    }
}
