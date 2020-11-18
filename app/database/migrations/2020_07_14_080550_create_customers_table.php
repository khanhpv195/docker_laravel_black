<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name',50);
            $table->string('phone',20);
            $table->string('email',150)->nullable();
            $table->integer('sex')->nullable();
            $table->integer('cmt')->nullable();
            $table->integer('age')->nullable();
            $table->string('country',150)->nullable();
            $table->string('address',150)->nullable();
            $table->string('note',150)->nullable();
            $table->softDeletes('deleted_at', 0);	
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
        Schema::dropIfExists('customers');
    }
}
