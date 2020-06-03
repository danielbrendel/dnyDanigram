<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2020 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use PDO;
use PDOException;

/**
 * Class InstallerModel
 *
 * Interface to product installation
 */
class InstallerModel extends Model
{
    /**
     * Perform installation process
     *
     * @param $attr
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public static function install($attr)
    {
        try {
            $divisor = strlen($attr['project']) / 2;

            $envcontent = '#Danigram Environment configuration' . PHP_EOL;
            $envcontent .= 'APP_NAME=Danigram' . $attr['project'] . PHP_EOL;
            $envcontent .= 'APP_CODENAME=dnyDanigram' . PHP_EOL;
            $envcontent .= 'APP_AUTHOR="Daniel Brendel"' . PHP_EOL;
            $envcontent .= 'APP_CONTACT="dbrendel1988@gmail.com"' . PHP_EOL;
            $envcontent .= 'APP_VERSION="1.0"' . PHP_EOL;
            $envcontent .= 'APP_ENV=local' . PHP_EOL;
            $envcontent .= 'APP_KEY=base64:fBr7/RVVAQlyln5NEVSGyrKmqV9AWEvhnvAcSUtSQzo=' . PHP_EOL;
            $envcontent .= 'APP_DEBUG=false' . PHP_EOL;
            $envcontent .= 'APP_URL=http://localhost' . PHP_EOL;
            $envcontent .= 'APP_PROJECTNAME="' . $attr['project'] . '"' . PHP_EOL;
            $envcontent .= 'APP_DESCRIPTION="The lightweight image sharing community platform system"' . PHP_EOL;
            $envcontent .= 'APP_TAGS=""' . PHP_EOL;
            $envcontent .= 'APP_TITLE="${APP_NAME} - ${APP_DESCRIPTION}"' . PHP_EOL;
            $envcontent .= 'APP_DIVISION=' . $divisor . PHP_EOL;
            $envcontent .= 'APP_POSTPACKLIMIT=15' . PHP_EOL;
            $envcontent .= 'APP_THREADPACKLIMIT=15' . PHP_EOL;
            $envcontent .= 'APP_PUSHPACKLIMIT=25' . PHP_EOL;
            $envcontent .= 'APP_TOPNTAGS=15' . PHP_EOL;
            $envcontent .= 'LOG_CHANNEL=stack' . PHP_EOL;
            $envcontent .= 'DB_CONNECTION=mysql' . PHP_EOL;
            $envcontent .= 'DB_HOST=' . $attr['dbhost'] . PHP_EOL;
            $envcontent .= 'DB_PORT=' . $attr['dbport'] . PHP_EOL;
            $envcontent .= 'DB_DATABASE=' . $attr['database'] . PHP_EOL;
            $envcontent .= 'DB_USERNAME=' . $attr['dbuser'] . PHP_EOL;
            $envcontent .= 'DB_PASSWORD=' . $attr['dbpassword'] . PHP_EOL;
            $envcontent .= 'SMTP_FROMADDRESS="' . $attr['smtpfromaddress'] . '"' . PHP_EOL;
            $envcontent .= 'SMTP_FROMNAME="${APP_NAME}"' . PHP_EOL;
            $envcontent .= 'SMTP_HOST="' . $attr['smtphost'] . '"' . PHP_EOL;
            $envcontent .= 'SMTP_USERNAME="' . $attr['smtpuser'] . '"' . PHP_EOL;
            $envcontent .= 'SMTP_PASSWORD="' . $attr['smtppassword'] . '"' . PHP_EOL;
            $envcontent .= 'GA_ENABLE=' . ((strlen($attr['ga']) > 0) ? 'true' : 'false') . PHP_EOL;
            $envcontent .= 'GA_TOKEN=""' . PHP_EOL;
            $envcontent .= 'BROADCAST_DRIVER=log' . PHP_EOL;
            $envcontent .= 'CACHE_DRIVER=file' . PHP_EOL;
            $envcontent .= 'QUEUE_CONNECTION=sync' . PHP_EOL;
            $envcontent .= 'SESSION_DRIVER=file' . PHP_EOL;
            $envcontent .= 'SESSION_LIFETIME=1440' . PHP_EOL;

            file_put_contents(base_path() . '/.env', $envcontent);

            \Artisan::call('config:clear');

            $dbobj = new PDO('mysql:host=' . $attr['dbhost'], $attr['dbuser'], $attr['dbpassword']);
            $dbobj->exec('CREATE DATABASE IF NOT EXISTS `' . $attr['database'] . '`;');

            Config::set('database.connections.mysql', [
                'host' => $attr['dbhost'],
                'port' => $attr['dbport'],
                'database' => $attr['database'],
                'username' => $attr['dbuser'],
                'password' => $attr['dbpassword'],
                'driver' => 'mysql',
                'url' => env('DATABASE_URL'),
                'unix_socket' => env('DB_SOCKET', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    \PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ]);

            \DB::reconnect();

            \Artisan::call('migrate:install');
            \Artisan::call('migrate:refresh', array('--path' => 'database/migrations', '--force' => true));

            \DB::insert("INSERT INTO app_settings (home_index_content, cookie_consent, about, imprint, tos, reg_info) VALUES('home_index_content', 'cookie_consent', 'about', 'imprint', 'tos', 'reg_info')");

            $user = new User();
            $user->username = 'admin';
            $user->email = $attr['email'];
            $user->password = password_hash($attr['password'], PASSWORD_BCRYPT);
            $user->maintainer = true;
            $user->admin = true;
            $user->account_confirm = '_confirmed';
            $user->avatar = 'default.png';
            $user->bio = 'Project administrator';
            $user->email_on_message = true;
            $user->save();

            unlink(base_path() . '/do_install');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}