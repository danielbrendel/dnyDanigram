<?php

/*
    Danigram (dnyDanigram) developed by Daniel Brendel

    (C) 2019 - 2021 by Daniel Brendel

    Version: 1.0
    Contact: dbrendel1988<at>gmail<dot>com
    GitHub: https://github.com/danielbrendel/

    Released under the MIT license
*/

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_models', function (Blueprint $table) {
            $table->id();
            $table->string('image_full');
            $table->string('image_thumb');
            $table->boolean('video'); // true = a video, false = an image
            $table->text('description');
            $table->string('hashtags', 512);
            $table->integer('userId');
            $table->integer('hearts')->default(0);
            $table->integer('reports')->default(0);
            $table->boolean('locked')->default(false);
            $table->boolean('nsfw')->default(false);
            $table->string('attribution_instagram')->default('');
            $table->string('attribution_twitter')->default('');
            $table->string('attribution_homepage')->default('');
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
        Schema::dropIfExists('post_models');
    }
}
