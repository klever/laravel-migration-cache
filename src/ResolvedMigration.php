<?php

namespace Klever\Laravel\MigrationCache;

class ResolvedMigration
{
    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var array
     */
    protected $queries;

    /**
     * ResolvedMigration constructor.
     *
     * @param string $filePath
     * @param array  $queries
     */
    public function __construct(string $filePath, array $queries = [])
    {
        $this->filePath = $filePath;
        $this->queries = $queries;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @return array
     */
    public function getQueries(): array
    {
        return $this->queries;
    }
}
