<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
           
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('tel')->default("0555655100");
            $table->date('birthday')->nullable();
            $table->string('address')->nullable();
            $table->string('geo_coord')->default("");
            $table->float("refund_rate",4,2)->default(0);//if this user is a customer then this is his purchase refund rate
            $table->string('Img')->default("man.jpg");
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->string('api_token', 80)
                        ->unique()
                        ->nullable()
                        ->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
