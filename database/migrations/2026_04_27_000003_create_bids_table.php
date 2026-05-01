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
        Schema::create('bids', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('job_id');
            $table->uuid('worker_id');
            $table->decimal('proposed_price', 12, 2);
            $table->integer('estimated_duration_hours');
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'withdrawn'])->default('pending');
            $table->timestampsTz();

            $table->unique(['job_id', 'worker_id'], 'idx_bids_job_id_worker_id');
            $table->index('job_id', 'idx_bids_job_id');
            $table->index('worker_id', 'idx_bids_worker_id');
            $table->index(['job_id', 'status'], 'idx_bids_job_id_status');

            $table->foreign('job_id')->references('id')->on('jobs')->cascadeOnDelete();
            $table->foreign('worker_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('job_assignments', function (Blueprint $table) {
            $table->foreign('bid_id')->references('id')->on('bids')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_assignments', function (Blueprint $table) {
            $table->dropForeign(['bid_id']);
        });

        Schema::dropIfExists('bids');
    }
};
