<?php namespace Aeroparks\Store\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateAeroparksProduct extends Migration
{
    public function up()
    {
        Schema::create('aeroparks_product', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->string('status')->nullable();
            $table->text('hash')->nullable();
            $table->string('type',10);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('aeroparks_product');
    }
}
