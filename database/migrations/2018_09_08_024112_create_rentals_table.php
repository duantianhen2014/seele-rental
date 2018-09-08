<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('a_user_id');
            $table->string('a_address');
            $table->integer('b_user_id');
            $table->string('b_address');
            $table->integer('charge')->default(0);
            $table->integer('deposit');
            $table->integer('status')->comment('1a已提交,5b已确认,10a已确认,15a申请完成,20已完成');
            $table->string('reject_reason')->default('')->comment('拒绝');
            $table->string('a_apply_tx_hash')->default('');
            $table->string('b_confirm_tx_hash')->default('');
            $table->string('a_confirm_tx_hash')->default('');
            $table->string('a_complete_apply_tx_hash')->default('');
            $table->string('b_complete_txt_hash')->default('');
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
        Schema::dropIfExists('rentals');
    }
}
