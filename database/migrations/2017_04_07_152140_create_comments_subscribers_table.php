<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments_subscribers', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->biginteger('content_id')->unsigned();
            $table->biginteger('user_id')->nullable()->unsigned();
            $table->string('name')->nullable();
            $table->string('email');
            $table->tinyInteger('subscribe')->default(0);
            $table->text('meta')->nullable();
            $table->timestamps();
        });
    }
}
