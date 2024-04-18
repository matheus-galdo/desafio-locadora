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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedSmallInteger('publication_year');
            $table->timestamps();
        });

        Schema::create('author_book', function (Blueprint $table) {
            $table->foreignId('author_id')->constrained();
            $table->foreignId('book_id')->constrained();
            $table->primary(['author_id', 'book_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('author_book');
        Schema::dropIfExists('books');
    }
};
