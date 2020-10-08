<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfoblocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infoblocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('type_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->text('body')->nullable();
            $table->string('button_title')->nullable();
            $table->string('button_link')->nullable();
            $table->string('image')->nullable();

            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('infoblock_types')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infoblocks');
    }
}
