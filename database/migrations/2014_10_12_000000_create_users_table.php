<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->string('signup_key',32);
            $table->tinyInteger('is_email_verified');
            $table->integer('role');
            $table->tinyInteger('is_admin');
            $table->string('firstname',100);
            $table->string('lastname',100);
            $table->string('email',256)->unique();
            $table->bigInteger('mobile_no',20);
            $table->string('password',100);
            $table->string('last_ip',40);
            $table->dateTime('last_login');
            $table->dateTime('last_password_change');
            $table->string('new_pass_key',32);
            $table->dateTime('new_pass_key_requested');
            $table->tinyInteger('is_active');
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
        Schema::dropIfExists('users');
    }
}
