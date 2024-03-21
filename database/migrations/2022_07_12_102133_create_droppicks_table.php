<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDroppicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('droppicks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('location_id');
            $table->string('name');
            $table->string('email');
            $table->string('contact');
            $table->string('drop_pick');
            $table->string('dp_date');
            $table->string('time_slot');
            $table->string('product_image');
            $table->text('product_details');
            $table->text('message');
            $table->string('status')->default('Pending');
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
        Schema::dropIfExists('droppicks');
    }
}
