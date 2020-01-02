<?php namespace Bookrr\User\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('bookrr_user_vehicles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->boolean('primary')->nullable();
            $table->string('plate', 10)->nullable();
            $table->string('brand', 50)->nullable();
            $table->string('model', 50)->nullable();
            $table->string('vin', 30)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('size', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookrr_user_vehicles');
    }
}
