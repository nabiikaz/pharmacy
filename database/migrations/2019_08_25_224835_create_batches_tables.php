<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_id')->unsigned();
            $table->integer('medicine_id')->unsigned();
            
            $table->foreign('purchase_id')->references('id')->on('purchases')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('medicine_id')->references('id')->on('medicines')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->date("fabrication_date");
            $table->date("expiry_date");
            $table->unsignedMediumInteger("unit_price");
            $table->unsignedMediumInteger("batch_price");
            $table->unsignedMediumInteger("quantity");//this is the quantity purchases 
            $table->unsignedMediumInteger("quantity_stock");//this is the quantity remaining in the stock 
            $table->unsignedMediumInteger("quantity_min");//this is the alert quantity minimum
            $table->integer("refund_rate");

//            $table->timestamps();
            

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batches');
    }
}
