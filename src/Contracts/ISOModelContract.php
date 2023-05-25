<?php

namespace Juanparati\ISOCodes\Contracts;

use Illuminate\Support\Collection;
use Juanparati\ISOCodes\Enums\NodeResolution;
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


    /**
     * Clone model.
     *
     * @return static
     */
    public function clone(): static;


    /**
     * Set resolution.
     *
     * @param string $node
     * @param NodeResolution $format
     * @return $this
     */
    public function setResolution(string $node, NodeResolution $format): static;


    /**
     * Set currency as number.
     *
     * @param bool $state
     * @return $this
     */
    public function setCurrencyAsNumber(bool $state): static;
}
