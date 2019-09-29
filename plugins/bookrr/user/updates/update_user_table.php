<?php namespace Bookrr\User\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class UpdateUsersTable extends Migration
{
    public function up()
    {
        Schema::table('aeroparks_user', function(Blueprint $table) {
            $table->string('code',50)->nullable()->unique();
        });
    }

}
