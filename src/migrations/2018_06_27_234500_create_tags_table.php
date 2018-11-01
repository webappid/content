<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTagsTable
 */

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')
                ->index()
                ->nullable(false);
            $table->integer('user_id')
                ->unsigned()
                ->comment('relation to users table');
            $table->timestamps();

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

    public function down(){
        Schema::dropIfExists('tags');
    }
}