<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('name', 100);
            $table->string('slug', 100)->unique('idx_categories_slug');
            $table->boolean('is_active')->default(true);
            $table->timestampsTz();
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('client_id');
            $table->uuid('category_id');
            $table->string('title', 200);
            $table->text('description');
            $table->decimal('budget_min', 12, 2);
            $table->decimal('budget_max', 12, 2);
            $table->integer('workers_needed')->default(1);
            $table->string('location_district', 100);
            $table->string('location_city', 100);
            $table->enum('status', ['open', 'in_progress', 'completed', 'cancelled'])->default('open');
            $table->timestampTz('start_at');
            $table->timestampTz('deadline_at');
            $table->timestampTz('deleted_at')->nullable();
            $table->timestampsTz();

            $table->index('client_id', 'idx_jobs_client_id');
            $table->index('category_id', 'idx_jobs_category_id');
            $table->index('location_city', 'idx_jobs_location_city');
            $table->index('status', 'idx_jobs_status');
            $table->index('created_at', 'idx_jobs_created_at');
            $table->index('budget_min', 'idx_jobs_budget_min');
            $table->index('budget_max', 'idx_jobs_budget_max');

            $table->index(['status', 'created_at'], 'idx_jobs_status_created_at');
            $table->index(['category_id', 'location_city', 'status', 'created_at'], 'idx_jobs_category_city_status_created_at');
            $table->index(['client_id', 'category_id', 'status'], 'idx_jobs_client_id_category_status');

            $table->foreign('client_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('category_id')->references('id')->on('categories')->restrictOnDelete();
        });

        Schema::create('job_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('job_id');
            $table->uuid('bid_id');
            $table->uuid('worker_id');
            $table->uuid('client_id');
            $table->timestampsTz();

            $table->index('job_id', 'idx_job_assignments_job_id');
            $table->index('worker_id', 'idx_job_assignments_worker_id');

            $table->unique('bid_id', 'idx_job_assignments_bid_id');
            $table->index(['worker_id', 'created_at'], 'idx_job_assignments_worker_id_created_at');

            $table->foreign('job_id')->references('id')->on('jobs')->cascadeOnDelete();
            $table->foreign('worker_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('client_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_assignments');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('categories');
    }
};
