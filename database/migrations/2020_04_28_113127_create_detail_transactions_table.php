<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('laundry_price_id');
            $table->unsignedBigInteger('laundry_type_id');
            $table->integer('qty');
            $table->integer('price');
            $table->integer('subtotal');
            $table->timestamps();

            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->change();
            ;
            $table->foreign('laundry_price_id')
                ->references('id')
                ->on('laundry_prices')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->change();
            ;
            $table->foreign('laundry_type_id')
                ->references('id')
                ->on('laundry_types')
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
        Schema::dropIfExists('detail_transactions');
    }
}
