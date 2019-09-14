<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDashboardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboards', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean("store_open")->default(false); //status of the store whether its opened or closed
            //$table->time('open_time')->default("6:00:00");	
            //$table->time('close_time')->default("23:59:00");	
            $table->float("tax",4,2)->default(19.00); //the generale tax percentage
            $table->float("shipping_price")->default(300);//shipping price with the default price when the shippement is in the same city 
            $table->string("store_name");
            $table->string("store_email");
            $table->string("address1");
            $table->string("address2")->default("");
            $table->string("tel");
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
        Schema::dropIfExists('dashboards');
    }
}
