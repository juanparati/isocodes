<?php

namespace Juanparati\ISOCodes\Contracts;

/**
 * Contract used by database classes.
 */
interface ISODatabase
{
    public function all() : array;
}