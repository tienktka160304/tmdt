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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->integer('id_category')->unsigned();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->string('title', 250);
            $table->string('short_description', 1000)->nullable();
            $table->tinyInteger('hot')->default(0)->comment('Hot status: 0 - no, 1 - yes'); // san_pham_hot ( DEFAULT 0)
            $table->unsignedInteger('views')->default(0);
            $table->tinyInteger('status')->default(1)->comment(' 0 - hidden, 1 - active');
            $table->string('image', 255)->nullable();
            $table->longText('content')->nullable();
            $table->date('published_date')->default(DB::raw('CURRENT_DATE'));
            $table->string('slug', 100)->unique();
            $table->foreign('id_category')->references('id')->on('post_categories')->onDelete('cascade');
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post');
    }
};
