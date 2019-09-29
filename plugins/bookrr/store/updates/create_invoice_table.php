<?php namespace Bookrr\Store\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateInvoiceTable extends Migration
{
    public function up()
    {
        Schema::create('bookrr_store_invoice', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('number')->nullable();
            $table->string('sent_at')->nullable();
            $table->string('status')->nullable();
            $table->longText('line_item')->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookrr_store_invoice');
    }
}
