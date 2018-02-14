<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('section');
            $table->string('key');
            $table->string('value')->nullable();
            $table->string('type')->default('string');
            $table->timestamps();
        });
    }
}
