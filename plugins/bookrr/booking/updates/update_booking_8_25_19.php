<?php namespace Bookrr\Bay\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateBookingTable extends Migration
{
    public function up()
    {
        Schema::table('bookrr_booking', function(Blueprint $table){
            $table->string('ref_num')->nullable()->after('items');
        });
    }

    public function down()
    {
        Schema::table('bookrr_booking', function(Blueprint $table){
            $table->dropColumn('refNum');
        });
    }
}