<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateContentsTable
 */

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->bigIncrements('id')
                ->comment('data for all content');
            $table->string('title')
                ->nullable(false)
                ->index()
                ->comment('Title of content');
            $table->string('code')
                ->nullable(false)
                ->index()
                ->unique()
                ->comment('Convert title to code for URI purpose only');
            $table->text('description')
                ->comment('description of the content');
            $table->string('keyword')
                ->nullable(false)
                ->index()
                ->comment('keyword for SEO');
            $table->string('og_title')
                ->nullable(false)
                ->index()
                ->comment('title for SEO');
            $table->string('og_description')
                ->nullable(false)
                ->index()
                ->comment('description for SEO');
            $table->unsignedBigInteger('default_image')
                ->comment('relation to files table to get image');
            $table->integer('status_id')
                ->unsigned()
                ->comment('relation to content statuses');
            $table->integer('language_id')
                ->unsigned();
            $table->integer('time_zone_id')
                ->unsigned()
                ->nullable(true);
            $table->dateTime('publish_date')
                ->nullable(false)
                ->comment('publish date for the content');
            $table->text('additional_info')
                ->comment('additional information of the content like term and condition ect');
            $table->text('content')
                ->comment('content data');
            $table->unsignedBigInteger('creator_id')
                ->comment('who is the creator of the content');
            $table->unsignedBigInteger('owner_id')
                ->comment('who is the owner of the content');
            $table->unsignedBigInteger('user_id')
                ->comment('user change the files');
            $table->timestamps();

            /**
             * foregin key
             */

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');
            
            $table->foreign('owner_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');

            $table->foreign('creator_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');

            $table->foreign('language_id')
                ->references('id')
                ->on('languages')
                ->onUpdate('cascade');

            $table->foreign('status_id')
                ->references('id')
                ->on('content_statuses')
                ->onUpdate('cascade');

            $table->foreign('default_image')
                ->references('id')
                ->on('files')
                ->onUpdate('cascade');

            $table->foreign('time_zone_id')
                ->references('id')
                ->on('time_zones')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contents');
    }
}
