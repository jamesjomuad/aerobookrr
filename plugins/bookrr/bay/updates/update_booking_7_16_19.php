<?php namespace Bookrr\Bay\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateBookingTable71619 extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bookrr_booking')) {
            Schema::table('bookrr_booking', function(Blueprint $table){
                $table->integer('bay_id')->nullable()->after('id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bookrr_booking')){
            Schema::table('bookrr_booking', function(Blueprint $table){
                if(Schema::hasColumn('bookrr_booking', 'bay_id')){
                   $table->dropColumn('bay_id'); 
                }
            });
        }
    }
}