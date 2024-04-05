<?php

namespace Juanparati\ISOCodes\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Juanparati\ISOCodes\Contracts\ISOModelContract;
use Juanparati\ISOCodes\Enums\NodeResolution;
use Juanparati\ISOCodes\Models\Extensions\CollectionMethodCallable;
use Juanparati\ISOCodes\Models\Extensions\NodeSearchable;


class CountryModel extends BasicModelBase implements ISOModelContract
{

    use NodeSearchable, CollectionMethodCallable;

    /**
     * Search by Alpha2.
     *
     * @param string $alpha2
     * @return array|null
     */
    public function findByAlpha2(string $alpha2): ?ModelRecord
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
    public function findByAlpha3(string $alpha3): ?ModelRecord
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
    public function findByNumeric($number): ?ModelRecord
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
    public function findByTld(string $tld): ?ModelRecord
    {
        $tld = Str::of($tld)
            ->start('.')
            ->lower();

        return $this->all()
            ->firstWhere('tld', $tld);
    }


    /**
     * Search by name (Case insensitive).
     *
     * @param string $name
     * @return array|null
     */
    public function findByName(string $name): ?ModelRecord
    {
        $name = (string) Str::of($name)
            ->lower()
            ->trim();

        return $this->all()
            ->filter(fn($country) => $name === Str::lower($country['name']))
            ->first();
    }


    /**
     * Search by phone code.
     *
     * @param $code
     * @return array|null
     */
    public function findByPhoneCode($code) : ?ModelRecord
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
    public function whereLanguage(string|array $language, bool $exact = false) : ?Collection
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
    public function whereCurrency(string|array $currency, bool $exact = false) : ?Collection
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
    public function whereContinent(string|array $continent, bool $exact = false) : ?Collection
    {
        return $this->whereNodeHas('continents', $continent, $exact);
    }


    /**
     * Return the list of all country codes with their nodes.
     *
     * @param bool $asArray
     * @return Collection
     */
    public function all(bool $asArray = false): Collection
    {
        $nodeData = [
            'languages'  => $this->iso->languages()->list(),
            'continents' => $this->iso->continents()->list(),
            'currencies' => $this->iso->currencies()->list()
        ];

        $currencyNumbers = $this->iso->currencyNumbers()->list();

        return $this->list()->map(function ($country) use ($nodeData, $currencyNumbers, $asArray) {
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
                                NodeResolution::NODE_AS_CODE => array_map(fn ($cur) => (string) $currencyNumbers[$cur], $data),
                                NodeResolution::NODE_AS_ALL  => collect($data)->mapWithKeys(fn ($name, $cur) => [(string) $currencyNumbers[$cur] => $name])->toArray(),
                            };
                        }

                        $country[$nodeName] = $data;
                    }
                }
            }

            return $asArray ? $country : new ModelRecord($country);
        });
    }


    /**
     * Return the raw list.
     *
     * @return Collection
     */
    public function list(): Collection
    {
        $countryNames = $this->iso->getDatabaseInstance('countries')->all();

        return collect($this->iso->getDatabaseInstance('countryCodes')->all())
            ->map(function ($country, $code) use ($countryNames) {
                $country['name'] = $countryNames[$code];
                return $country;
            });
    }


    public function toArray() : array {
        return $this
            ->all(true)
            ->toArray();
    }
}
