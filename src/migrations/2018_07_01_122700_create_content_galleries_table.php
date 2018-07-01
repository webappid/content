<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->increments('id');
            $table->integer('content_id')
                ->unsigned();
            $table->integer('file_id')
                ->unsigned();
            $table->integer('user_id')
                ->unsigned();
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
