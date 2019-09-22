<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('forename')->nullable();
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('email',128)->unique();
            $table->integer('address_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedTinyInteger('role')->default(1);
            //$table->foreign('address_id')->references('id')->on('addresses');
            $table->rememberToken();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('users', function(Blueprint $table){
            $table->dropForeign('[address_id]');

        });*/
        Schema::dropIfExists('users');
    }
}
