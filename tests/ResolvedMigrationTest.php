<?php

namespace Klever\Laravel\MigrationCache\Tests;

use Klever\Laravel\MigrationCache\ResolvedMigration;

class ResolvedMigrationTest extends TestCase
{
    /** @test */
    public function it_has_a_file_path()
    {
        $querySet = new ResolvedMigration('migrations/some_migration.php');

        $this->assertEquals('migrations/some_migration.php', $querySet->getFilePath());
    }

    /** @test */
    public function it_has_queries()
    {
        $queries = ['SELECT * FROM users', 'SELECT id FROM emails'];
        $querySet = new ResolvedMigration('migration.php', $queries);

        $this->assertEquals($queries, $querySet->getQueries());
    }
}
