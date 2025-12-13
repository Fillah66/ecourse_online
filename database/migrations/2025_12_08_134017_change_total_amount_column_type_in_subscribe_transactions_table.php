<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscribe_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('total_amount')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscribe_transactions', function (Blueprint $table) {
            $table->unsignedInteger('total_amount')->nullable()->change();
        });
    }
};
