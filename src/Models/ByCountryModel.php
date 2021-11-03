<?php

namespace Juanparati\ISOCodes\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ByCountryModel extends ModelBase
{


    /**
     * Search by Alpha2.
     *
     * @param string $alpha2
     * @return array|null
     */
    public function byAlpha2(string $alpha2) : ?array {
        return $this->all()
            ->where('alpha2', strtoupper($alpha2))
            ->first();
    }


    /**
     * Search by Alpha3.
     *
     * @param string $alpha3
     * @return array|null
     */
    public function byAlpha3(string $alpha3) : ?array {
        return $this->all()
            ->where('alpha3', strtoupper($alpha3))
            ->first();
    }


    /**
     * Search by number.
     *
     * @param $number
     * @return array|null
     */
    public function byNumber($number) : ?array {
        return $this->all()
            ->where('number', $number)
            ->first();
    }


    /**
     * Search by TLD.
     *
     * @param string $tld
     * @return array|null
     */
    public function byTld(string $tld) : ?array {
        $tld = Str::of($tld)
            ->start($tld, '.')
            ->lower();

        return $this->all()
            ->where('tld', $tld)
            ->first();
    }


    /**
     * Return the list of all country codes with their nodes.
     *
     * @return Collection
     */
    public function all() : Collection
    {
        if ($collection = $this->getCache('all'))
            return $collection;

        $nodeData = [
            'languages' => $this->iso->byLanguage()->list(),
            'continents' => $this->iso->byContinent()->list(),
        ];

        $list = $this->list();

        if ($this->options['currencyAsNumber']) {
            $nodeData['currencies'] = $this->iso->byCurrencyNumber()->list();

            $list = $list->map(function ($country) use ($nodeData) {
                foreach ($country['currencies'] as &$currencyCode)
                    $currencyCode = $nodeData['currencies'][$currencyCode];

                return $country;
            });
        }
        else
            $nodeData['currencies'] = $this->iso->byCurrency()->list();

        $list = $list->map(function ($country) use ($nodeData) {

            foreach (array_keys($this->nodeResolution) as $nodeName) {
                if (isset($country[$nodeName])) {
                    $country[$nodeName] = $this->resolveNodeData(
                        $nodeName,
                        $nodeData[$nodeName],
                        $country[$nodeName]
                    );
                }
            }

            return $country;
        });

        $this->setCache('all', $list);

        return $list;
    }


    /**
     * Return the raw list.
     *
     * @return Collection
     */
    public function list(): Collection
    {
        if ($collection = $this->getCache('list'))
            return $collection;

        $countryNames = $this->iso->getDatabaseInstance('countries')->all();

        $list = collect($this->iso->getDatabaseInstance('countryCodes')->all())
            ->map(function ($country, $code) use ($countryNames) {
                $country['name'] = $countryNames[$code];
                return $country;
            });

        $this->setCache('list', $list);

        return $list;
    }
}