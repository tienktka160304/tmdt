<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            // $table->unsignedBigInteger('id_coupon')->nullable();
            $table->integer('id_payment')->unsigned();
            $table->tinyInteger('status')->default(1)->comment('0 - huy, 1 - xu ly ,2 - dang giao, 3 - ok');
            $table->tinyInteger('thanh_toan')->default(0)->comment('0 - unpaid, 1 - paid');
            $table->string('note', 200)->nullable();
            $table->string('phone', 100);
            $table->string('address', 255);
            $table->dateTime('order_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Ngày đặt');
            $table->decimal('total_price', 14, 2);
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('id_coupon')->references('id')->on('coupons')->onDelete('set null');
            $table->foreign('id_payment')->references('id')->on('payments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
