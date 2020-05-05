<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 05/11/18
 * Time: 10.14
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCategoryParentTable extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('parent_id')
                ->unsigned()
                ->nullable(true)
                ->comment('column to set category parent id');
        });
    }
    
    public function down()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();

        if ($driver != 'sqlite') {
            $columns = [];

            if (Schema::hasColumn('categories', 'parent_id')) {
                array_push($columns, 'parent_id');
            }

            if (count($columns) > 0) {
                Schema::table('categories', function (Blueprint $table) use ($columns) {
                    $table->dropColumn($columns);
                });
            }
        }
    }
}