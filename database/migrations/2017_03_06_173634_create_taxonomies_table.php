<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxonomiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxonomies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('content_type_id')->unsigned();
            $table->string('name');
            $table->string('name_singular');
            $table->string('placeholder')->default('Select');
            $table->string('slug')->nullable();
            $table->integer('order')->unsigned()->default(0);
            $table->timestamps();

            $table->foreign('content_type_id')->references('id')->on('content_types')->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
