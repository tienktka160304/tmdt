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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_user");
            $table->unsignedBigInteger("id_product");
            $table->string('content', 600);
            $table->tinyInteger('status')->default(1)->comment('0 - hidden, 1 - display');
            $table->foreign("id_user")->references('id')->on('users')->onDelete('cascade');
            $table->foreign("id_product")->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
