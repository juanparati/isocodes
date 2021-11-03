<?php

namespace Juanparati\ISOCodes\Models;

use Illuminate\Support\Collection;
use Juanparati\ISOCodes\Contracts\ISOModel;
use Juanparati\ISOCodes\Exceptions\ISONodeAttributeMissing;
use Juanparati\ISOCodes\ISOCodes;


/**
 * Base class used by the models.
 */
abstract class ModelBase implements ISOModel
{

    /**
     * Subnode resolution formats.
     */
    const NODE_AS_CODE      = 'code';
    const NODE_AS_NAME      = 'name';
    const NODE_AS_ALL       = 'all';


    /**
     * Define node resolution formats.
     *
     * @var array
     */
    protected array $nodeResolution = [
        'currencies'  => self::NODE_AS_CODE,
        'continents'  => self::NODE_AS_CODE,
        'languages'   => self::NODE_AS_CODE,
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
     * @param string $format
     * @return $this
     * @throws ISONodeAttributeMissing
     */
    public function setResolution(string $node, string $format = self::NODE_AS_CODE) {

        if (!isset($this->nodeResolution[$node]))
            throw new ISONodeAttributeMissing('Node attribute is missing');

        $this->nodeResolution[$node] = $format;
        $this->cache = [];

        return $this;
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
    ) : array {

        $list = [];

        foreach($codes as $code) {
            if ($this->nodeResolution[$node] === self::NODE_AS_CODE)
                $list[] = $code;
            else if ($this->nodeResolution[$node] === self::NODE_AS_NAME)
                $list[] = $modelData[$code] ?? null;
            else
                $list[$code] = $modelData[$code] ?? null;
        }

        return $list;
    }


    /**
     * Set into cache.
     *
     * @param string $key
     * @param $values
     */
    protected function setCache(string $key, $values) {
        $this->cache[$this->getCacheKey() . '_' . $key] = $values;
    }


    /**
     * Get data from cache.
     *
     * @param string $key
     * @return Collection|null
     */
    protected function getCache(string $key) {
        return $this->cache[$this->getCacheKey() . '_' . $key] ?? null;
    }


    /**
     * Obtain the cache key.
     *
     * @return string
     */
    protected function getCacheKey() : string {
        return md5(serialize($this->options));
    }
}