<?php namespace Bookrr\Store\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCartsTable extends Migration
{
    public function up()
    {
        Schema::create('bookrr_carts', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('book_id');
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('status')->nullable();
            $table->string('order_number')->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('fee', 10, 2)->nullable();
            $table->string('ref_num')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookrr_carts');
    }
}
