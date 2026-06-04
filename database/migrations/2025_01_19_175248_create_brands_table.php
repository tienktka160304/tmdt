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
        Schema::create('brands', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->string('name', 150); // Cột tên danh mục, kiểu VARCHAR
            $table->string('logo', 255)->nullable(); // Cột ảnh danh mục, kiểu VARCHAR, cho phép NULL
            $table->string('slug', 100)->unique();
            $table->unsignedInteger('sort')->nullable(); // Cột thứ tự, cho phép NULL ??

            $table->tinyInteger('status')->default(1)->comment(' 0 - hidden, 1 - active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
