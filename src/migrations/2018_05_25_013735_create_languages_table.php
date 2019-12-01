<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code',5)
                ->index()
                ->nullable(false)
                ->comment('language code');
            $table->string('name',20)
                ->nullable(false)
                ->default('')
                ->comment('language name');
            $table->unsignedBigInteger('image_id')
                ->nullable(true);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            /**
             * foregin key
             */

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');
            $table->foreign('image_id')
                ->references('id')
                ->on('files')
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
        Schema::dropIfExists('languages');
    }
}
