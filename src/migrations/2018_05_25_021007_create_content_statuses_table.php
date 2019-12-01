<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateContentStatusesTable
 */

class CreateContentStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_statuses', function (Blueprint $table) {
            $table->increments('id')
                ->comment('references of content statuses');
            $table->string('name',20)
                ->index()
                ->nullable(false)
                ->comment('status name');
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('content_statuses');
    }
}
