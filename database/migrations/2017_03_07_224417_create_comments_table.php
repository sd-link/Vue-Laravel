<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->string('commentable_type');
            $table->biginteger('commentable_id')->unsigned();
            $table->string('title')->nullable();
            $table->text('body');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->integer('review')->nullable();
            $table->biginteger('parent')->unsigned()->nullable();
            $table->tinyInteger('status')->default(1);
            $table->ipAddress('visitor_ip');
            $table->text('meta')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
