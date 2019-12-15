<?php namespace Bookrr\Store\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('bookrr_product_coupon', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->integer('savings')->nullable();
            $table->char('unit',10)->nullable();
            $table->integer('min_cost')->nullable();
            $table->integer('num_usage')->nullable();
            $table->timestamp('expiration')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookrr_product_coupon');
    }
}