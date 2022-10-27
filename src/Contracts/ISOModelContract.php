<?php

namespace Juanparati\ISOCodes\Contracts;

use Illuminate\Support\Collection;
use Juanparati\ISOCodes\ISOCodes;

interface ISOModelContract
{
    /**
     * Constructor.
     *
     * @param ISOModelContract $iso
     */
    public function __construct(ISOCodes $iso);


    /**
     * Return the list of all elements with their nodes.
     *
     * @param bool $asArray
     * @return Collection
     */
    public function all(bool $asArray = false): Collection;


    /**
     * Return the list.
     *
     * @return array
     */
    public function list(): Collection;
}
