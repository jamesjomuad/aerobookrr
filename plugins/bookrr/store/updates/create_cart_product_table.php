<?php namespace Bookrr\Store\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreatePivotCartItemTable extends Migration
{
    public function up()
    {
        Schema::create('bookrr_cart_product', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('cart_id');
            $table->integer('product_id');
            $table->integer('quantity')->nullable();
            $table->primary(['cart_id','product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookrr_cart_product');
    }
}
