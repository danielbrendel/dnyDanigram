<?php

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
            $table->text('description');
            $table->string('hashtags', 512);
            $table->integer('userId');
            $table->integer('hearts')->default(0);
            $table->integer('reports')->default(0);
            $table->boolean('locked')->default(false);
            $table->boolean('nsfw')->default(false);
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
