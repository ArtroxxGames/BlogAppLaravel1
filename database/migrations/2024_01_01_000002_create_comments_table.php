<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('article_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('body');
            $table->timestamps();

            // Cada usuario puede valorar un artículo una sola vez.
            $table->unique(['user_id', 'article_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
