<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorBookTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // only create if the pivot does not already exist
        if (! Schema::hasTable('author_book')) {
            Schema::create('author_book', function (Blueprint $table) {
                $table->id();
                $table->foreignId('book_id')->constrained()->cascadeOnDelete();
                $table->foreignId('author_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                // avoid duplicate pairs
                $table->unique(['book_id', 'author_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('author_book')) {
            Schema::dropIfExists('author_book');
        }
    }
}
