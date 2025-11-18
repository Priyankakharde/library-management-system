<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantityToBooksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // only add column if books table exists and column does not exist
        if (Schema::hasTable('books') && ! Schema::hasColumn('books', 'quantity')) {
            Schema::table('books', function (Blueprint $table) {
                // 'after' works for MySQL; harmless in other DBs
                $table->integer('quantity')->default(1)->after('published_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('books') && Schema::hasColumn('books', 'quantity')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('quantity');
            });
        }
    }
}
