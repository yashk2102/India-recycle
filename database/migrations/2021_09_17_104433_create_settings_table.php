<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('favicon')->nullable();
            $table->string('logo')->nullable();
            $table->string('footerlogo')->nullable();
            $table->string('email')->nullable();
            $table->string('contact')->nullable();
            $table->text('address')->nullable();

            $table->string('copyright')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->text('privacy')->nullable();
            $table->text('terms')->nullable();

            $table->text('footer_about')->nullable();

            $table->string('mission_img')->nullable();
            $table->text('mission_des')->nullable();
            $table->string('vision_img')->nullable();
            $table->text('vision_des')->nullable();

            $table->string('about_title')->nullable();
            $table->string('about_img')->nullable();
            $table->text('about_des_one')->nullable();
            $table->text('about_des_two')->nullable();

            $table->string('counter_title_one')->nullable();
            $table->string('counter_number_one')->nullable();

            $table->string('counter_title_two')->nullable();
            $table->string('counter_number_two')->nullable();

            $table->string('counter_title_three')->nullable();
            $table->string('counter_number_three')->nullable();

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
        Schema::dropIfExists('settings');
    }
}
