<?php namespace Bookrr\Bay\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateBookingTable extends Migration
{
    public function up()
    {
        Schema::table('aeroparks_booking', function(Blueprint $table){
            $table->integer('bay_id')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('aeroparks_booking', function(Blueprint $table){
            $table->dropColumn('bay_id');
        });
    }
}