<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfoblockTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infoblock_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('blade_path');
            $table->boolean('public');
            $table->boolean('with_items');
            $table->boolean('with_item_parent');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infoblock_types');
    }
}
