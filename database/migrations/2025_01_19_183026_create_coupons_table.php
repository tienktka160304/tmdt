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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->integer('discount_value'); //100% 20% ==100 20
            $table->string('coupon_code', 50)->unique();
            $table->decimal("min_order", 14, 2)->nullable();
            $table->decimal("max_order", 14, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('content', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
