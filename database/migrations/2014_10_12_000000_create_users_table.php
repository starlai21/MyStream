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
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('email_login_users', function (Blueprint $table) {
            $table->increments('id'); 
            $table->integer('user_id');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('activation_code')->nullable();
            $table->string('password_reset_code')->nullable();
            $table->boolean('activated')->default(false);
            $table->timestamps();
        });

        Schema::create('social_login_users', function (Blueprint $table) {
            $table->increments('id'); 
            $table->integer('user_id');
            $table->enum('provider',['github'])->default('github');
            $table->integer('provider_user_id');
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
        Schema::dropIfExists('social_login_users');
        Schema::dropIfExists('email_login_users');
    }
}
