<?php

namespace Juanparati\ISOCodes\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Juanparati\ISOCodes\Models\Extensions\NodeSearchable;


class ByCountryModel extends ModelBase
{

    use NodeSearchable;

    /**
     * Search by Alpha2.
     *
     * @param string $alpha2
     * @return array|null
     */
    public function byAlpha2(string $alpha2): ?array
    {
        return $this->all()
            ->firstWhere('alpha2', strtoupper($alpha2));
    }


    /**
     * Search by Alpha3.
     *
     * @param string $alpha3
     * @return array|null
     */
    public function byAlpha3(string $alpha3): ?array
    {
        return $this->all()
            ->firstWhere('alpha3', strtoupper($alpha3));
    }


    /**
     * Search by number.
     *
     * @param $number
     * @return array|null
     */
    public function byNumeric($number): ?array
    {
        return $this->all()
            ->firstWhere('numeric', $number);
    }


    /**
     * Search by TLD.
     *
     * @param string $tld
     * @return array|null
     */
    public function byTld(string $tld): ?array
    {
        $tld = Str::of($tld)
            ->start('.')
            ->lower();

        return $this->all()
            ->firstWhere('tld', $tld);
    }


    /**
     * Search by phone code.
     *
     * @param $code
     * @return array|null
     */
    public function byPhoneCode($code) : ?array
    {
        $code = is_string($code) ? ('+' === $code[0] ? substr($code, 1) : $code) : $code;

        return $this->all()
            ->firstWhere('phone_code', $code);
    }

    /**
     * Search by language.
     *
     * @param string|array $language
     * @param bool $exact
     * @return Collection|null
     */
    public function byLanguage(string|array $language, bool $exact = false) : ?Collection
    {
        return $this->whereNodeHas('languages', $language, $exact);
    }


    /**
     * Search by currency.
     *
     * @param string|array $currency
     * @param bool $exact
     * @return Collection|null
     */
    public function byCurrency(string|array $currency, bool $exact = false) : ?Collection
    {
        return $this->whereNodeHas('currencies', $currency, $exact);
    }


    /**
     * Search by continent.
     *
     * @param string|array $continent
     * @param bool $exact
     * @return Collection|null
     */
    public function byContinent(string|array $continent, bool $exact = false) : ?Collection
    {
        return $this->whereNodeHas('continents', $continent, $exact);
    }


    /**
     * Return the list of all country codes with their nodes.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        if ($collection = $this->getCache('all')) {
            return $collection;
        }

        $nodeData = [
            'languages'  => $this->iso->byLanguage()->list(),
            'continents' => $this->iso->byContinent()->list(),
            'currencies' => $this->iso->byCurrency()->list()
        ];

        $currencyNumbers = $this->iso->byCurrencyNumber()->list();

        $list = $this->list()->map(function ($country) use ($nodeData, $currencyNumbers) {
            foreach (array_keys($this->nodeResolution) as $nodeName) {
                if (isset($country[$nodeName])) {
                    $data = $this->resolveNodeData(
                        $nodeName,
                        $nodeData[$nodeName],
                        $country[$nodeName]
                    );

                    if ($data === null) {
                        unset($country[$nodeName]);
                    } else {
                        if ($this->options['currencyAsNumber'] && $nodeName === 'currencies') {
                            $data = match ($this->nodeResolution['currencies']) {
                                static::NODE_AS_CODE => array_map(fn ($cur) => (string) $currencyNumbers[$cur], $data),
                                static::NODE_AS_ALL  => collect($data)->mapWithKeys(fn ($name, $cur) => [(string) $currencyNumbers[$cur] => $name])->toArray(),
                            };
                        }

                        $country[$nodeName] = $data;
                    }
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
        if ($collection = $this->getCache('list')) {
            return $collection;
        }

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
