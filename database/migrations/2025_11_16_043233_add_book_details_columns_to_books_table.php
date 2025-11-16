<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'quantity')) {
                $table->unsignedInteger('quantity')->default(1)->after('isbn');
            }

            if (!Schema::hasColumn('books', 'publisher')) {
                $table->string('publisher')->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('books', 'edition')) {
                $table->string('edition')->nullable()->after('publisher');
            }
            if (!Schema::hasColumn('books', 'pages')) {
                $table->unsignedInteger('pages')->nullable()->after('edition');
            }
            if (!Schema::hasColumn('books', 'language')) {
                $table->string('language')->nullable()->after('pages');
            }
            if (!Schema::hasColumn('books', 'genre')) {
                $table->string('genre')->nullable()->after('language');
            }
            if (!Schema::hasColumn('books', 'location')) {
                $table->string('location')->nullable()->after('genre');
            }
            if (!Schema::hasColumn('books', 'description')) {
                $table->text('description')->nullable()->after('location');
            }
            if (!Schema::hasColumn('books', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $columns = [
                'cover_image',
                'description',
                'location',
                'genre',
                'language',
                'pages',
                'edition',
                'publisher',
                'quantity',
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('books', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

