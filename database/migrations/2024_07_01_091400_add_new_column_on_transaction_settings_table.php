<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_settings', function (Blueprint $table) {
            $table->unique('slug');
            $table->decimal('agent_fixed_commissions',8,2,true)->default(0);
            $table->decimal('agent_percent_commissions',8,2,true)->default(0);
            $table->boolean('agent_profit')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
