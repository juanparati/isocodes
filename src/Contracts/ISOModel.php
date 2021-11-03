<?php

namespace Juanparati\ISOCodes\Contracts;

use Illuminate\Support\Collection;
use Juanparati\ISOCodes\ISOCodes;

interface ISOModel
{

    /**
     * Constructor.
     *
     * @param ISOModel $iso
     */
    public function __construct(ISOCodes $iso);


    /**
     * Return the list.
     *
     * @return array
     */
    public function list() : Collection;

}