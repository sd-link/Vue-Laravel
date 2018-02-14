<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_image', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->integer('gallery_id')->unsigned();
            $table->biginteger('image_id')->unsigned();
            $table->tinyInteger('order')->unsigned()->default(0);

            $table->foreign('gallery_id')->references('id')->on('galleries')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
        });
    }
}
