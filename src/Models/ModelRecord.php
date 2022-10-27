<?php

namespace Juanparati\ISOCodes\Models;

use Juanparati\ISOCodes\Exceptions\ISORecordAttributeNotFound;
use Juanparati\ISOCodes\Exceptions\ISORecordReadOnlyException;

class ModelRecord implements \ArrayAccess, \JsonSerializable
{
    /**
     * Constructor.
     *
     * @param array $record
     */
    public function __construct(protected array $record) {}


    public function offsetExists(mixed $offset) : bool
    {
        return array_key_exists(strtolower($offset), $this->record);
    }

    public function offsetGet(mixed $offset) : mixed
    {
        return $this->record[strtolower($offset)] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value) : void
    {
        throw new ISORecordReadOnlyException("Attribute $offset is read-only");
    }

    public function offsetUnset(mixed $offset) : void
    {
        throw new ISORecordReadOnlyException("Attribute $offset is cannot be removed");
    }

    /**
     * Getter.
     *
     * @param string $attr
     * @return mixed
     * @throws ISORecordAttributeNotFound
     */
    public function __get(string $attr) : mixed {
        if (!$this->offsetExists($attr))
            throw new ISORecordAttributeNotFound("Attribute $attr is missing");

        return $this->offsetGet($attr);
    }

    public function __serialize() : array
    {
        return $this->record;
    }

    public function __unserialize(array $data) : void
    {
        $this->record = $data;
    }

    public function jsonSerialize() : array
    {
        return $this->record;
    }

    /**
     * Encode record as JSON.
     *
     * @param int $flags
     * @return string
     */
    public function toJson(int $flags = 0) : string {
        return json_encode($this->record, $flags);
    }

    /**
     * Encode record as array.
     *
     * @return array
     */
    public function toArray() : array {
        return $this->record;
    }
}