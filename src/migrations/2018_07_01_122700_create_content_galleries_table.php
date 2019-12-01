<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateContentGalleriesTable
 */

class CreateContentGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_galleries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('content_id');
            $table->unsignedBigInteger('file_id');
            $table->unsignedBigInteger('user_id');
            $table->string('description',100)
                ->comment('alt description')
                ->nullable(false)
                ->default('');
            $table->timestamps();

            $table->foreign('content_id')
                ->on('contents')
                ->references('id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            $table->foreign('file_id')
                ->on('files')
                ->references('id')
                ->onUpdate('cascade');

            $table->foreign('user_id')
                ->on('users')
                ->references('id')
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
        Schema::dropIfExists('content_galleries');
    }
}
