<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2022 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('account_confirm')->nullable()->default('');
            $table->string('password_reset')->nullable();
            $table->string('avatar');
            $table->string('bio', 1024)->default('');
            $table->integer('gender')->default(0); //0 = unspecified, 1 = male, 2 = female, 3 = diverse
            $table->dateTime('birthday')->nullable();
            $table->string('location')->default('');
            $table->boolean('email_on_message')->default(true);
            $table->boolean('newsletter')->default(false);
            $table->string('newsletter_token')->default('');
            $table->boolean('deactivated')->default(false);
            $table->boolean('admin')->default(false);
            $table->boolean('maintainer')->default(false);
            $table->boolean('nsfw')->default(false);
            $table->dateTime('pro_date')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('geo_exclude')->default(false);
            $table->string('device_token', 1024)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
