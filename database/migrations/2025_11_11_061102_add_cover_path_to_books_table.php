<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('books', 'quantity')) {
            Schema::table('books', function (Blueprint $table) {
                $table->integer('quantity')->default(0)->after('title');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('books', 'quantity')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropColumn('quantity');
            });
        }
    }
};
