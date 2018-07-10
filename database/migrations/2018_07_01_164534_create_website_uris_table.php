<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsiteUrisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_uris', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('website_id');
            $table->string('uri');
            $table->string('status');
            $table->timestamps();

            $table->unique(['website_id', 'uri']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('website_uris');
    }
}
