<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 120);
            $table->string('alt_spellings', 200);
            $table->string('capital', 120);
            $table->string('region', 120);
            $table->string('timezones', 300);
            $table->string('code_2', 120);
            $table->string('code_3', 120);
            $table->string('calling_codes', 120);
            $table->string('currencies', 120);
            $table->string('languages', 200);
            $table->string('flag_location', 200);
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
        Schema::dropIfExists('countries');
    }
}
