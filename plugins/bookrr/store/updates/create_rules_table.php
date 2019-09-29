<?php namespace Bookrr\Store\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateAeroparksProductRule extends Migration
{
    public function up()
    {
        Schema::create('aeroparks_product_rule', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('cancel_allow');
            $table->decimal('refund_fee');
            $table->decimal('refund_percent');
            $table->decimal('rate', 10, 2)->nullable();
            $table->timestamp('active')->nullable();
            $table->string('building')->nullable();
            $table->string('type')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aeroparks_product_rule');
    }
}