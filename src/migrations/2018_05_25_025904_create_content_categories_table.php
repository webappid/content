<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateContentCategoriesTable
 */

class CreateContentCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('content_id')
                    ->unsigned()
                    ->nullable(false);
            $table->integer('category_id')
                    ->unsigned()
                    ->nullable(false);
            $table->unsignedBigInteger('user_id')
                    ->nullable(false);
            $table->timestamps();

            $table->foreign('content_id')
                    ->references('id')
                    ->on('contents')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('category_id')
                    ->references('id')
                    ->on('categories')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

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
        Schema::dropIfExists('content_categories');
    }
}
