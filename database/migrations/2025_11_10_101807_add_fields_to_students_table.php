<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Adds additional optional columns to students table
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'roll_no')) {
                $table->string('roll_no')->nullable()->after('student_id');
            }
            if (!Schema::hasColumn('students', 'dob')) {
                $table->date('dob')->nullable()->after('address');
            }
            if (!Schema::hasColumn('students', 'notes')) {
                $table->text('notes')->nullable()->after('dob');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'notes')) $table->dropColumn('notes');
            if (Schema::hasColumn('students', 'dob')) $table->dropColumn('dob');
            if (Schema::hasColumn('students', 'roll_no')) $table->dropColumn('roll_no');
        });
    }
};
