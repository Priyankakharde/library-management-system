<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->timestamp('issued_at')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();

            $table->index(['book_id','student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('issues');
    }
}
