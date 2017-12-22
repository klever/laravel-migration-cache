<?php

namespace Klever\Laravel\MigrationCache\Tests;

use Illuminate\Database\Migrations\Migrator;
use Klever\Laravel\MigrationCache\MigrationResolver;

class MigrationResolverTest extends TestCase
{
    /**
     * @var Migrator
     */
    protected $migrator;

    /**
     * @var MigrationResolver
     */
    protected $resolver;

    protected function setUp()
    {
        parent::setUp();

        $this->migrator = app('migrator');
        $this->resolver = new MigrationResolver($this->migrator);
    }


    /** @test */
    public function it_resolves_a_migration_into_queries()
    {
        $path = 'migrations/2014_10_12_000000_create_the_users_table.php';
        $this->migrator->requireFiles([$path]);

        $resolvedMigration = $this->resolver->resolve($path);

        $expectedQueries = [
            'CREATE TABLE `users` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `name` VARCHAR(255) NOT NULL, `email` VARCHAR(255) NOT NULL, `password` VARCHAR(255) NOT NULL, `remember_token` VARCHAR(100) NULL, `created_at` TIMESTAMP NULL, `updated_at` TIMESTAMP NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
            'ALTER TABLE `users` ADD UNIQUE `users_email_unique`(`email`)'
        ];
        $this->assertEquals($path, $resolvedMigration->getFilePath());
        $this->assertEquals(array_map('strtolower', $expectedQueries), $resolvedMigration->getQueries());
    }
}
