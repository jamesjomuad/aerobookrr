<?php namespace Aeroparks\Store\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCarRentsTable extends Migration
{
    public function up()
    {
        Schema::create('aeroparks_carrental', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('rate')->nullable();
            $table->string('mpg')->nullable();
            $table->string('capacity')->nullable();
            $table->string('luggages')->nullable();
            $table->string('transmission')->nullable();
            $table->longText('description');
            $table->string('status')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aeroparks_carrental');
    }
}
