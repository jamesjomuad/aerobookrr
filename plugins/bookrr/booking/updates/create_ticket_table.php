<?php namespace Bookrr\Booking\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTicketTable extends Migration
{
    public function up()
    {
        Schema::create('bookrr_ticket', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('qrcode',50)->nullable();
            $table->string('barcode',50)->nullable();
            $table->string('status')->nullable();
            $table->float('amount')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookrr_ticket');
    }
}
