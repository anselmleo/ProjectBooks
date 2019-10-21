<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrameDimensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frame_dimensions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('frame_type_id')->unsigned();
            $table->string('frame_dimension');
            $table->string('price');
            $table->timestamps();

            $table->foreign('frame_type_id')->references('id')->on('frame_types')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('frame_dimensions');
    }
}
