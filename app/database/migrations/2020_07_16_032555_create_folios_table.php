<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoliosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folios', function (Blueprint $table) {
            $table->id();
            $table->integer('confirm_no');
            $table->integer('status');
            $table->dateTime('date_arrival');
            $table->dateTime('date_department');
            $table->integer('customer_id');
            $table->integer('customer_no_adults')->nullable();
            $table->integer('customer_no_young')->nullable();
            $table->integer('customer_no_baby')->nullable();
            $table->integer('room_id');
            $table->integer('rate_code');
            $table->integer('rate_override');
            $table->integer('discount')->nullable();
            $table->string('note',150)->nullable();
            $table->integer('num_nigth')->nullable();
            $table->integer('service')->nullable();
            $table->integer('customer_name')->nullable();
            $table->integer('customer_sex')->nullable();
            $table->integer('price_total')->nullable();
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
        Schema::dropIfExists('folios');
    }
}
