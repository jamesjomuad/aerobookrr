<?php namespace Aeroparks\Store\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCouponsTable extends Migration
{
    public function up()
    {
        Schema::create('aeroparks_product_coupon', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('discount')->nullable();
            $table->string('code')->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamp('promo_start')->nullable();
            $table->timestamp('promo_end')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aeroparks_product_coupon');
    }
}