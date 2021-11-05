<?php

namespace Juanparati\ISOCodes\Models;

use Illuminate\Support\Collection;

abstract class ByNodeBase extends ModelBase
{
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
     * @return array|null
     * @throws \Juanparati\ISOCodes\Exceptions\ISONodeAttributeMissing
     */
    public function byCode(string $code): ?array
    {
        return $this->all()
            ->where('code', strtoupper($code))
            ->first();
    }


    /**
     * Return the list of all.
     *
     * @return Collection
     * @throws \Juanparati\ISOCodes\Exceptions\ISONodeAttributeMissing
     */
    public function all(): Collection
    {
        $countries = $this->iso->ByCountry()->setOptions($this->options);

        foreach ($this->nodeResolution as $nodeName => $nodeFormat) {
            $countries->setResolution($nodeName, $nodeFormat);
        }

        $list = $this->list();

        return $countries->all()
            ->groupBy($this->database)
            ->map(function ($cur, $code) use ($list) {
                $base = [
                    'code'       => $code,
                    'name'       => $list[$code] ?? null,
                    'countries'  => $cur,
                ];

                foreach ($this->assocNodes as $assocNode) {
                    $base[$assocNode] = $cur->pluck($assocNode)->filter()->collapse()->unique();
                }

                return $base;
            });
    }


    /**
     * Return the raw list.
     *
     * @return Collection
     */
    public function list(): Collection
    {
        return collect(
            $this->iso->getDatabaseInstance($this->database)->all()
        );
    }
}
