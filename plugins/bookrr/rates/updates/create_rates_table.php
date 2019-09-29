<?php namespace Bookrr\Rates\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateRatesTable extends Migration
{
    public function up()
    {
        Schema::create('bookrr_rates', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name',100)->nullable();
            $table->string('code',50)->nullable();
            $table->decimal('rate', 10, 2)->nullable();
            $table->timestamp('date_start')->nullable();
            $table->timestamp('date_end')->nullable();
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->enum('active', [true, false]);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookrr_rates');
    }
}
