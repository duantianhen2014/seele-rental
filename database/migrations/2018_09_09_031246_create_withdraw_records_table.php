<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('before_balance');
            $table->integer('money');
            $table->integer('status')->default(1)->comment('1已提交,9已完成,5失败');
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
        Schema::dropIfExists('withdraw_records');
    }
}
