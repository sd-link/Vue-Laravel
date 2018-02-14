<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateContentTableWithFeaturedImageForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content', function (Blueprint $table) {
            $table->foreign('featured_image_id')->references('id')->on('images')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
