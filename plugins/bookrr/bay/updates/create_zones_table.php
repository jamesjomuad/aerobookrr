<?php namespace Aeroparks\Bay\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateZonesTable extends Migration
{
    public function up()
    {
        Schema::create('aeroparks_bay_zone', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->text('description')->nullable();
            $table->string('slug',30);
            $table->longText('options')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aeroparks_bay_zone');
    }
}
