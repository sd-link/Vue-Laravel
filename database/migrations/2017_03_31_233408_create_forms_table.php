<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->boolean('catpcha')->default(false);
            $table->boolean('success_message')->default(true);
            $table->string('success_message_text')->nullable();
            $table->boolean('redirect')->default(false);
            $table->string('redirect_url')->nullable();
            $table->text('meta')->nullable();
            $table->timestamps();
        });
    }
}
