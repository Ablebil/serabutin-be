<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoriesFlowTest extends TestCase
{
    private string $testSchema;

    protected function setUp(): void
    {
        parent::setUp();

        if (!in_array('pgsql', \PDO::getAvailableDrivers(), true)) {
            $this->markTestSkipped('pdo_pgsql extension is required.');
        }

        $this->configurePostgresConnection();

        if (!$this->canConnectToPostgres()) {
            $this->markTestSkipped('PostgreSQL not available.');
        }

        $this->testSchema = 'test_categories_' . str_replace('-', '', (string) Str::uuid());
        $this->createIsolatedTestSchema();

        config(['database.connections.pgsql.search_path' => $this->testSchema]);
        DB::purge('pgsql');

        $this->artisan('migrate:fresh');
    }

    protected function tearDown(): void
    {
        $this->dropIsolatedTestSchema();
        parent::tearDown();
    }

    private function canConnectToPostgres(): bool
    {
        try {
            DB::connection('pgsql')->getPdo();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    private function configurePostgresConnection(): void
    {
        config([
            'database.default' => 'pgsql',
            'database.connections.pgsql.host' => env('TEST_DB_HOST', '127.0.0.1'),
            'database.connections.pgsql.port' => env('TEST_DB_PORT', '5432'),
            'database.connections.pgsql.database' => env('TEST_DB_DATABASE', 'serabutin_db'),
            'database.connections.pgsql.username' => env('TEST_DB_USERNAME', 'postgres'),
            'database.connections.pgsql.password' => env('TEST_DB_PASSWORD', 'postgres'),
            'database.connections.pgsql.search_path' => 'public',
        ]);
        DB::purge('pgsql');
    }

    private function createIsolatedTestSchema(): void
    {
        DB::connection('pgsql')->statement('CREATE SCHEMA "' . $this->testSchema . '"');
    }

    private function dropIsolatedTestSchema(): void
    {
        try {
            config(['database.connections.pgsql.search_path' => 'public']);
            DB::purge('pgsql');
            DB::connection('pgsql')->statement('DROP SCHEMA IF EXISTS "' . $this->testSchema . '" CASCADE');
        } catch (\Throwable) {}
    }

    public function test_index_returns_active_categories(): void
    {
        Category::query()->insert([
            ['id' => Str::uuid(), 'name' => 'Bebersih', 'slug' => 'bebersih', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'name' => 'Servis AC', 'slug' => 'servis-ac', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Inactive category should not appear
        Category::query()->insert([
            ['id' => Str::uuid(), 'name' => 'Hidden', 'slug' => 'hidden', 'is_active' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $response = $this->getJson('/api/v1/categories');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('message', 'Kategori berhasil diambil.')
            ->assertJsonCount(2, 'data');
    }

    public function test_index_returns_empty_array_when_no_active_categories(): void
    {
        $response = $this->getJson('/api/v1/categories');

        $response->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(0, 'data');
    }
}
