<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('full_name');
            $table->string('frame_type');
            $table->string('frame_image')->nullable();
            $table->string('frame_image_path')->nullable();
            $table->string('frame_text')->nullable();
            $table->string('frame_dimension');
            $table->string('shipping_addr');
            $table->string('state');
            $table->string('extra_note')->nullable();
            $table->string('is_paid')->default(false);
            $table->boolean('is_received')->default(false);
            $table->boolean('is_processing')->default(false);
            $table->boolean('is_shipped')->default(false);
            $table->boolean('is_delivered')->default(false);
            $table->boolean('is_completed')->default(false);
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
        Schema::dropIfExists('orders');
    }
}
