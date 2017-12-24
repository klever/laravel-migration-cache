<?php

namespace Klever\Laravel\MigrationCache;

use Illuminate\Database\Migrations\Migrator;

class MigrationCache
{
    /**
     * @var Migrator
     */
    protected $migrator;

    /**
     * @var MigrationResolver
     */
    protected $resolver;

    /**
     * The name of the cache directory.
     *
     * @var string
     */
    protected $directoryName = '__migration_cache__';

    /**
     * The path to the cache directory.
     *
     * @var string
     */
    private $cachePath;

    public function __construct(Migrator $migrator, $cachePath)
    {
        $this->migrator = $migrator;
        $this->cachePath = $cachePath;
        $this->resolver = new MigrationResolver($this->migrator);
    }

    /**
     * @return string[]
     */
    public function cacheMigrations()
    {
        $migrations = $this->getMigrations();

        $resolvedMigrations = $this->resolveMigrations($migrations);

        $this->createCacheDirectory();

        return $this->extractQueries($resolvedMigrations);
    }

    /**
     * Find the migrations from the registered paths, and require the migration files so they're available for
     * instantiation.
     *
     * @return string[]
     */
    protected function getMigrations()
    {
        $migrations = $this->migrator->getMigrationFiles(
            $this->migrator->paths());

        $this->migrator->requireFiles($migrations);

        return $migrations;
    }

    /**
     * For each migration, get the SQL queries needed to execute.
     *
     * @param string[] $migrations
     * @return ResolvedMigration[]
     */
    protected function resolveMigrations($migrations)
    {
        $resolvedMigrations = [];

        foreach ($migrations as $migration) {
            $resolvedMigrations[] = $this->resolver->resolve($migration);
        }

        return $resolvedMigrations;
    }

    /**
     * Create a single array consisting of all SQL queries from the migrations.
     *
     * @param ResolvedMigration[] $resolvedMigrations
     * @return string[]
     */
    protected function extractQueries($resolvedMigrations)
    {
        return array_reduce($resolvedMigrations, function ($carry, ResolvedMigration $current) {
            return array_merge($carry, $current->getQueries());
        }, []);
    }

    /**
     * Create the cache directory if it doesn't exist.
     *
     * @return void
     */
    protected function createCacheDirectory()
    {
        $path = $this->cachePath . DIRECTORY_SEPARATOR . $this->directoryName;

        if ( ! is_dir($path)) {
            mkdir($path);
        }
    }
}
