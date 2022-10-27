<?php

namespace Juanparati\ISOCodes\Models;

use Illuminate\Support\Collection;
use Juanparati\ISOCodes\Contracts\ISOModelContract;
use Juanparati\ISOCodes\Exceptions\ISONodeAttributeMissing;
use Juanparati\ISOCodes\Models\Extensions\CollectionMethodCallable;

abstract class ModelBase extends BasicModelBase implements ISOModelContract
{

    use CollectionMethodCallable;

    /**
     * Database used by the model.
     *
     * @var string
     */
    protected string $database = '';


    /**
     * Grouped by.
     *
     * @var string
     */
    protected string $groupBy = '';


    /**
     * Nodes to associate.
     *
     * @var array
     */
    protected array $assocNodes = [];


    /**
     * Search by continent code.
     *
     * @param string $code
     * @return ModelRecord|null
     * @throws \Juanparati\ISOCodes\Exceptions\ISONodeAttributeMissing
     */
    public function findByCode(string $code): ?ModelRecord
    {
        return $this
            ->all()
            ->where('code', strtoupper($code))
            ->first();
    }


    /**
     * Return the list of all.
     *
     * @param bool $asArray
     * @return Collection
     * @throws ISONodeAttributeMissing
     */
    public function all(bool $asArray = false): Collection
    {
        $countries = $this->iso->countries()->setOptions($this->options);

        foreach ($this->nodeResolution as $nodeName => $nodeFormat) {
            $countries->setResolution($nodeName, $nodeFormat);
        }

        $list = $this->list();

        return $countries->all()
            ->groupBy($this->database)
            ->map(function ($cur, $code) use ($list, $asArray) {
                $base = [
                    'code'       => $code,
                    'name'       => $list[$code] ?? null,
                    'countries'  => $asArray ? $cur->map(fn($c) => $c->toArray())->toArray() : $cur,
                ];

                foreach ($this->assocNodes as $assocNode) {
                    $base[$assocNode] = $cur
                        ->pluck($assocNode)
                        ->filter()
                        ->collapse()
                        ->unique();

                    if ($asArray)
                        $base[$assocNode] = $base[$assocNode]->toArray();
                }

                return $asArray ? $base : new ModelRecord($base);
            });
    }


    public function toArray() : array {
        return $this
            ->all(true)
            ->toArray();
    }


    /**
     * Return the raw list.
     *
     * @return Collection
     */
    public function list(): Collection
    {
        return collect($this->iso->getDatabaseInstance($this->database)->all());
    }
}
