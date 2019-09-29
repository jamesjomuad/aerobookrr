<?php namespace Bookrr\Bay\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateBookingTable extends Migration
{
    public function up()
    {
        Schema::table('aeroparks_carrental_booking', function(Blueprint $table){
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('aeroparks_carrental_booking', function(Blueprint $table){
            $table->dropColumn('deleted_at');
        });
    }
}