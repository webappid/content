<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 04/11/18
 * Time: 03.22
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCategoryStatusTable extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('status_id')
                ->unsigned()
                ->nullable(true)
                ->comment('relation to category status');
            
            $table->foreign('status_id')
                ->references('id')
                ->on('category_statuses')
                ->onUpdate('cascade');
        });
    }
    
    public function down()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();

        if ($driver != 'sqlite') {
            $columns = [];
            $foreigns = [];

            if (Schema::hasColumn('categories', 'status_id')) {
                array_push($columns, 'status_id');
                array_push($foreigns, 'status_id');
            }

            if (count($columns) > 0) {
                if (config('database')['default'] != "sqlite") {
                    Schema::table('categories', function (Blueprint $table) use ($foreigns) {
                        $table->dropForeign($foreigns);
                    });
                }

                Schema::table('categories', function (Blueprint $table) use ($columns) {
                    $table->dropColumn($columns);
                });
            }
        }
    }
}