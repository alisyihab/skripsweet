<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaundryPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laundry_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('unit_type');
            $table->unsignedBigInteger('laundry_type_id');
            $table->integer('price');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('laundry_type_id')
                ->references('id')
                ->on('laundry_types')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->change();
            ;
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->change();
            ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laundry_prices');
    }
}
