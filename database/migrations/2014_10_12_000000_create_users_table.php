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
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('username')->unique()->nullable();
            $table->text('bio')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('timezone')->nullable();
            $table->text('meta')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

}
