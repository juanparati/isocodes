<?php

namespace Juanparati\ISOCodes\Databases;

use Juanparati\ISOCodes\Contracts\ISODatabase;


/**
 * Base class used for databases.
 */
abstract class ISODatabaseBase implements ISODatabase
{
    /**
     * Database data.
     *
     * @var array
     */
    protected array $db = [];

    /**
     * Retrieve all the elements from the database.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->db;
    }
}