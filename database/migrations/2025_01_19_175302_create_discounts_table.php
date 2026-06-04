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
        Schema::create('discounts', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->string('name', 150); // ten_san_pham (VARCHAR 150, NULL)
            $table->text('description')->nullable(); // mo_ta (TEXT, NULL)
            $table->integer('value')->nullable()->comment('Phần trăm khuyến mãi');
            $table->tinyInteger('status')->default(1)->comment('status: 0 - hidden, 1 - active');
            $table->timestamp('time_start')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Ngày nhập'); // Thời gian bắt đầu
            $table->timestamp('time_end')->nullable(); // Thời gian kết thúc
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
