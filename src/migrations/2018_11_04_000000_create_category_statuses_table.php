<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 04/11/18
 * Time: 03.16
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryStatusesTable extends Migration
{
    
    public function up()
    {
        Schema::create('category_statuses', function (Blueprint $table) {
            $table->increments('id')
                ->comment('Table for category statuses');
            $table->string('name')
                ->comment('Status Name');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('category_statuses');
    }
}