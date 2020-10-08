<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class   CreateInfoblockItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infoblock_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('infoblock_id')->unsigned();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->string('sub_title')->nullable();
            $table->string('image')->nullable();
            $table->string('button_title')->nullable();
            $table->string('button_link')->nullable();
            $table->string('body')->nullable();
            $table->integer('order');

            $table->timestamps();

            $table->foreign('infoblock_id')->references('id')->on('infoblocks')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infoblock_items');
    }
}
