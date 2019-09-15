<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned(); //this user_id is the pharmacist id that made the sale
            $table->integer('customer_id')->unsigned(); //this customer_id is the customer that did the purchase
            $table->float("total_price")->unsigned()->default(0);
            $table->boolean('paid')->default(false);
            $table->boolean('delivery')->default(false);
            //$table->integer('dashboard_id')->unsigned();
            
            $table->foreign("user_id")->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
                
            $table->foreign('customer_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

                
           
            $table->timestamps();
        });



        Schema::create('sale_batches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sale_id')->unsigned(); 
            $table->integer('batche_id')->unsigned(); 
            $table->integer('quantity')->unsigned();
            

            $table->foreign('batche_id')->references('id')->on('batches')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')
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
        Schema::dropIfExists('sale_batches');
        Schema::dropIfExists('sales');
    }
}
