<?php namespace Bookrr\Booking\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('bookrr_booking_tickets', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookrr_booking_tickets');
    }
}
