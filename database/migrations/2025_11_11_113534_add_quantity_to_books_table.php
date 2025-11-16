<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('books', 'quantity')) {
            Schema::table('books', function (Blueprint $table) {
                $table->unsignedInteger('quantity')->default(1)->after('isbn');
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
