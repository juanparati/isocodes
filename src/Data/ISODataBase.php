<?php

namespace Juanparati\ISOCodes\Data;

use Juanparati\ISOCodes\Contracts\ISODataContract;

/**
 * Base class used for databases.
 */
abstract class ISODataBase implements ISODataContract
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
