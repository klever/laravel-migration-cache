<?php

namespace Klever\Laravel\MigrationCache\Tests;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Foundation\Application;
use Klever\Laravel\MigrationCache\MigrationCache;
use Klever\Laravel\MigrationCache\Tests\Stubs\AppServiceProvider;

class MigrationCacheTest extends TestCase
{
    /**
     * @var Migrator
     */
    protected $migrator;

    public function setUp()
    {
        parent::setUp();

        $this->migrator = app('migrator');
    }

    /**
     * Get the package providers for the test.
     *
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [AppServiceProvider::class];
    }


    /** @test */
    public function it_caches_migrations()
    {
        $cache = new MigrationCache($this->migrator, __DIR__);

        dd($cache->cacheMigrations());
    }

    /** @test */
    public function it_creates_a_migrations_folder()
    {
        (new MigrationCache($this->migrator, __DIR__))->cacheMigrations();
        $expectedPath = __DIR__ . '/__migration_cache__';

        $this->assertTrue(is_dir($expectedPath));
        rmdir($expectedPath);
    }
}
