<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id')
                ->comment('Table to collect all files');
            $table->string('name')
                ->index();
            $table->string('description')
                ->nullable(true)
                ->comment('Description For SEO Image');
            $table->string('alt', 100)
                ->nullable(true)
                ->comment('alt for image SEO');
            $table->string('path')
                ->nullable(false)
                ->default('uploads');
            $table->integer('mime_type_id')
                ->unsigned()
                ->comment('Relation to mime type');
            $table->unsignedBigInteger('creator_id')
                ->comment('who is creator the file');
            $table->unsignedBigInteger('owner_id')
                ->comment('who is the owner of the file');
            $table->unsignedBigInteger('user_id')
                ->comment('user change the file');
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

            $table->foreign('mime_type_id')
                ->references('id')
                ->on('mime_types')
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
        Schema::dropIfExists('files');
    }
}
