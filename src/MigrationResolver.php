<?php

namespace Klever\Laravel\MigrationCache;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Migrations\Migrator;

class MigrationResolver
{
    /**
     * @var Migrator
     */
    protected $migrator;

    public function __construct(Migrator $migrator)
    {
        $this->migrator = $migrator;
    }

    public function resolve($migrationPath)
    {
//        $migrations = $this->migrator->getMigrationFiles('../tests/migrations');
//        $this->migrator->requireFiles($migrations);
//        $queries = [];

//        foreach ($migrations as $migration) {
        $migration = $this->migrator->resolve($this->migrator->getMigrationName($migrationPath));

        $queries = $this->extractQueries($migration);

        return new ResolvedMigration($migrationPath, $queries);
    }

    /**
     * Get the SQL queries that the migration will execute.
     *
     * @param Migration $migration
     * @return array
     */
    protected function extractQueries(Migration $migration)
    {
        $db = $this->migrator->resolveConnection(null);

        return array_column($db->pretend(function () use ($migration) {
            $migration->up();
        }), 'query');
    }
}
