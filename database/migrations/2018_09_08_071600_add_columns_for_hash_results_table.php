<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsForHashResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hash_results', function (Blueprint $table) {
            $table->string('request_type');
            $table->string('request_data', 2550)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hash_results', function (Blueprint $table) {
            $table->dropColumn('request_type');
            $table->dropColumn('request_data');
        });
    }
}
