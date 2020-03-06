<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaance_country', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('cod_uic')->nullable();
            $table->string('cod_iso')->nullable();
            $table->boolean('is_active',true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaance_country');
    }
}
