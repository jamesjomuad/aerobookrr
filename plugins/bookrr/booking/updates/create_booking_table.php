<?php namespace Aeroparks\Booking\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('aeroparks_booking', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->integer('vehicle_id')->nullable();
            $table->string('slot')->nullable();
            $table->string('number')->unique()->nullable();
            $table->string('status')->nullable();
            $table->string('barcode')->nullable();
            $table->timestamp('date_in')->nullable();
            $table->timestamp('date_out')->nullable();
            $table->timestamp('park_in')->nullable();
            $table->timestamp('park_out')->nullable();
            $table->string('flight_number_arrive')->nullable();
            $table->string('flight_number_depart')->nullable();
            $table->integer('guest_out')->nullable();
            $table->integer('guest_in')->nullable();
            $table->string('destination_in')->nullable();
            $table->string('destination_out')->nullable();
            $table->string('agent_reference')->nullable();
            $table->string('promo_code')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aeroparks_booking');
    }
}
