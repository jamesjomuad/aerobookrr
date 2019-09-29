<?php namespace Aeroparks\Store\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCarBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('aeroparks_carrental_booking', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->integer('car_id')->nullable();
            $table->string('number')->unique()->nullable();
            $table->string('status')->nullable();
            $table->timestamp('date_in')->nullable();
            $table->timestamp('date_out')->nullable();
            $table->integer('passenger')->nullable();
            $table->integer('phone')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aeroparks_carrental_booking');
    }
}
