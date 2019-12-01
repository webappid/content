<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateMimeTypesTable
 */

class CreateMimeTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mime_types', function (Blueprint $table) {
            $table->increments('id')
                ->comment('References for mime type');
            $table->string('name')
                ->index()
                ->comment('Mime name');
            $table->unsignedBigInteger('user_id')
                ->comment('user is update the data');
            $table->timestamps();

            /**
             * foregin key
             */

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('mime_types');
    }
}
