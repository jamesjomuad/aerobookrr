<?php namespace Bookrr\Booking\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateBookingTable extends Migration
{
    public function up()
    {
        Schema::table('bookrr_booking', function(Blueprint $table){
            $table->string('barcode',50)->change();
            $table->integer('ticket_id')->nullable()->after('vehicle_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookrr_booking');
    }
}