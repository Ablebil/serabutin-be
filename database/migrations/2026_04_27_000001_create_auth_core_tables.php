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
        DB::statement('CREATE EXTENSION IF NOT EXISTS pgcrypto');

        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('email', 255)->unique('idx_users_email');
            $table->string('password_hash', 255);
            $table->string('full_name', 100);
            $table->enum('role', ['client', 'worker']);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestampsTz();

            $table->index('role', 'idx_users_role');
        });

        Schema::create('user_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('user_id');
            $table->text('bio')->nullable();
            $table->string('location_district', 100)->nullable();
            $table->string('location_city', 100)->nullable();
            $table->string('avatar_url', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->float('avg_rating')->default(0);
            $table->integer('total_jobs_posted')->default(0);
            $table->integer('total_jobs_completed')->default(0);
            $table->timestampsTz();

            $table->unique('user_id', 'idx_user_profiles_user_id');
            $table->index('location_city', 'idx_user_profiles_location_city');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::create('refresh_tokens', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('user_id');
            $table->string('token_hash', 255);
            $table->timestampTz('expires_at');
            $table->timestampTz('created_at')->useCurrent();

            $table->unique('token_hash', 'idx_refresh_tokens_token_hash');
            $table->index('user_id', 'idx_refresh_tokens_user_id');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refresh_tokens');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('users');
    }
};
