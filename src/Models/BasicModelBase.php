<?php

namespace Juanparati\ISOCodes\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Juanparati\ISOCodes\Enums\NodeResolution;
use Juanparati\ISOCodes\Exceptions\ISONodeAttributeMissing;
use Juanparati\ISOCodes\ISOCodes;
use SebastianBergmann\CodeCoverage\Report\Xml\Node;

/**
 * Base class used by the models.
 */
abstract class BasicModelBase
{

    use Macroable;


    /**
     * Define node resolution formats.
     *
     * @var NodeResolution[]
     */
    protected array $nodeResolution = [
        'currencies'  => NodeResolution::NODE_AS_CODE,
        'continents'  => NodeResolution::NODE_AS_CODE,
        'languages'   => NodeResolution::NODE_AS_CODE,
    ];


    /**
     * Cache.
     *
     * @var Collection[]
     */
    protected array $cache = [];


    /**
     * Model options.
     *
     * @var array
     */
    protected array $options = [
        'currencyAsNumber' => false
    ];


    /**
     * Constructor.
     *
     * @param ISOCodes $iso
     */
    public function __construct(protected ISOCodes $iso) {}


    /**
     * Define when the currency is returned as number instead of code.
     *
     * @param bool $state
     * @return $this
     */
    public function setCurrencyAsNumber(bool $state): static
    {
        $this->options['currencyAsNumber'] = $state;
        return $this;
    }


    /**
     * Set the node resolution.
     *
     * @param string $node
     * @param NodeResolution $format
     * @return $this
     * @throws ISONodeAttributeMissing
     */
    public function setResolution(string $node, NodeResolution $format = NodeResolution::NODE_AS_CODE): static
    {
        if (!isset($this->nodeResolution[$node])) {
            throw new ISONodeAttributeMissing('Node attribute is missing');
        }

        if ($this->nodeResolution[$node] !== $format) {
            $this->nodeResolution[$node] = $format;
            $this->cache = [];
        }

        return $this;
    }

    /**
     * Get options.
     *
     * @return array|false[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }


    /**
     * Set options.
     *
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): static
    {
        $this->options = $options;

        return $this;
    }


    /**
     * Clone current model.
     *
     * @return $this
     */
    public function clone() : static
    {
        return clone $this;
    }


    /**
     * Resolve node data.
     *
     * @param string $node
     * @param Collection $modelData
     * @param array $codes
     * @return array
     */
    protected function resolveNodeData(
        string $node,
        Collection $modelData,
        array $codes,
    ): ?array {
        if ($this->nodeResolution[$node] === NodeResolution::NODE_AS_NONE) {
            return null;
        }

        $list = [];

        foreach ($codes as $code) {
            if ($this->nodeResolution[$node] === NodeResolution::NODE_AS_CODE) {
                $list[] = $code;
            } elseif ($this->nodeResolution[$node] === NodeResolution::NODE_AS_NAME) {
                $list[] = $modelData[$code] ?? null;
            } else {
                $list[$code] = $modelData[$code] ?? null;
            }
        }

        return $list;
    }
}
