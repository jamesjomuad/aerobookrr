<?php namespace Bookrr\Stripe\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('bookrr_stripe_transactions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->integer('other_id')->unsigned()->index()->nullable();
            $table->integer('customer_id')->unsigned()->index()->nullable();
            $table->string('amount')->nullable();
            $table->string('email')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('ref_id')->nullable();
            $table->string('refunded')->nullable();
            $table->string('amount_refunded')->nullable();
            $table->string('receipt_url',250)->nullable();
            $table->string('status')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookrr_stripe_transactions');
    }
}
