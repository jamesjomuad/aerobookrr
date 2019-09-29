<?php namespace Aeroparks\Bay\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateBookingTable extends Migration
{
    public function up()
    {
        Schema::table('aeroparks_booking', function(Blueprint $table){
            $table->longText('items')->nullable()->after('note');
        });
    }

    public function down()
    {
        Schema::table('aeroparks_booking', function(Blueprint $table){
            $table->dropColumn('items');
        });
    }
}