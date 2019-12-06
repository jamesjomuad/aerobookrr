<?php namespace Bookrr\Bay\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateCarrentalTable9219 extends Migration
{
    public function up()
    {
        Schema::table('bookrr_carrental_booking', function(Blueprint $table){
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        if(Schema::hasTable('bookrr_carrental_booking'))
        {
            Schema::table('bookrr_carrental_booking', function(Blueprint $table){
                $table->dropColumn('deleted_at');
            });
        }
        
    }
}