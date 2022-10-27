<?php

namespace Juanparati\ISOCodes\Contracts;

/**
 * Contract used by database classes.
 */
interface ISODataContract
{
    public function all(): array;
}
