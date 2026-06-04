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
        Schema::create('banners', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();

            $table->tinyInteger('position')->default(0)->comment("0- unde 1:banner lon 2:banner trong section san pham ");
            $table->string("image", 255);
            $table->string('link', 255)->comment('Banner link URL'); // Link column, VARCHAR type, length 255
            $table->string('title1', 100)->comment('Primary title'); // Title1 column, VARCHAR type, length 50
            $table->string('title2', 100)->nullable()->comment('Secondary title'); // Title2 column, VARCHAR type, length 50, nullable
            $table->unsignedInteger('sort')->nullable(); // Cột thứ tự, cho phép NULL ??
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
