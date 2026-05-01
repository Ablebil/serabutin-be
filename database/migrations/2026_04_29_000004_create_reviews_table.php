<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assignment_id');
            $table->uuid('reviewer_id');
            $table->uuid('reviewee_id');
            $table->integer('rating')->comment('1-5');
            $table->text('comment')->nullable();
            $table->timestampTz('created_at')->useCurrent();

            $table->unique(['assignment_id', 'reviewer_id'], 'idx_reviews_assignment_id_reviewer_id');
            $table->index('assignment_id', 'idx_reviews_assignment_id');
            $table->index('reviewee_id', 'idx_reviews_reviewee_id');
            $table->index(['reviewee_id', 'created_at'], 'idx_reviews_reviewee_id_created_at');

            $table->foreign('assignment_id')->references('id')->on('job_assignments')->cascadeOnDelete();
            $table->foreign('reviewer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('reviewee_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
