<?php

/**
 * @author @DyanGalih
 * @copyright @2018
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AlterOwnerAtContentTable
 */

class AlterOwnerAtContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->integer('creator_id')
                ->unsigned()
                ->nullable(true)
                ->comment('crator user');
            /**
             * foregin key
             */

            $table->foreign('creator_id')
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
        $columns = [];
        if (Schema::hasColumn('contents', 'creator_id')) {
            array_push($columns, 'creator_id');
        }

        if (count($columns)) {
            Schema::table('contents', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
}
