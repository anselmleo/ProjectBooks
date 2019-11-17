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
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(false); // This is for active users
            $table->boolean('is_confirmed')->default(false); // This will be for email confirmation
            $table->boolean('is_ban')->default(false); // This is to ban a user from using the system
            $table->boolean('profile_updated')->default(false); // This is to check if the user's basic profile is updated
            $table->softDeletes();
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
