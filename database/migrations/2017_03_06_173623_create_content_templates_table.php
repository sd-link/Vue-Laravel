<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('content_type_id')->unsigned()->nullable();
            $table->integer('default')->default(1);
            $table->timestamps();

            $table->foreign('content_type_id')->references('id')->on('content_types')->onDelete('cascade');
        });
    }
}
