<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCategoriesTable
 */

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id')
                ->comment('table for collect category data');
            $table->string('code', 20)
                ->unique()
                ->nullable(false)
                ->comment('category code');
            $table->string('name', 50)
                ->nullable(false)
                ->default('')
                ->comment('category name');
            $table->unsignedBigInteger('user_id')
                ->comment('who is the owner of the data');
            $table->timestamps();

            /**
             * Relation to database
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
        Schema::dropIfExists('categories');
    }
}
