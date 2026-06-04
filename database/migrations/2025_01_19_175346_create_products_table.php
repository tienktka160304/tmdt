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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('id_category')->unsigned();
            $table->integer('id_brand')->unsigned();
            $table->integer('id_discount')->unsigned()->nullable();
            $table->string('name', 150); // ten_san_pham (VARCHAR 150, NULL)
            $table->string('slug', 100)->unique();
            $table->longText('description')->nullable(); // mo_ta (TEXT, NULL)
            $table->unsignedInteger('views')->default(0);
            $table->tinyInteger('status')->default(1)->comment('Product status: 0 - hidden, 1 - active');
            $table->dateTime('import_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Ngày nhập');
            $table->tinyInteger('hot_product')->default(0)->comment('Hot product status: 0 - no, 1 - yes'); // san_pham_hot ( DEFAULT 0)
            // Khóa ngoại
            $table->foreign('id_category')->references('id')->on('sub_categories')->onDelete('cascade');
            $table->foreign('id_brand')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('id_discount')->references('id')->on('discounts')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes(); // Thêm deleted_at (NULL mặc định)
        });
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
