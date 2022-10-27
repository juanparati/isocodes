<?php

namespace Juanparati\ISOCodes;

use Illuminate\Support\Pluralizer;
use Juanparati\ISOCodes\Contracts\ISODataContract;
use Juanparati\ISOCodes\Contracts\ISOModelContract;
use Juanparati\ISOCodes\Exceptions\ISOModelNotFound;

/**
 * Provide the main ISOCodes objects.
 *
 * @method \Juanparati\ISOCodes\Models\ContinentModel continents
 * @method \Juanparati\ISOCodes\Models\CountryModel countries
 * @method \Juanparati\ISOCodes\Models\CurrencyModel currencies
 * @method \Juanparati\ISOCodes\Models\CurrencyNumberModel currencyNumbers
 * @method \Juanparati\ISOCodes\Models\LanguageModel languages
 */
class ISOCodes
{
    /**
     * Registered data.
     *
     * @var ISODataContract[]
     */
    protected array $datasets = [
        'countryCodes'   => \Juanparati\ISOCodes\Data\Countries\CountryCodes::class,
        'countries'      => \Juanparati\ISOCodes\Data\Countries\CountriesEN::class,
        'currencies'     => \Juanparati\ISOCodes\Data\Currencies\CurrenciesEN::class,
        'currencyNumbers'=> \Juanparati\ISOCodes\Data\Currencies\CurrencyNumbers::class,
        'languages'      => \Juanparati\ISOCodes\Data\Languages\LanguagesEN::class,
        'continents'     => \Juanparati\ISOCodes\Data\Continents\ContinentsEN::class,
    ];


    /**
     * Loaded models.
     *
     * @var ISOModelContract[]
     */
    protected array $modelInstances = [];


    /**
     * Loaded databases.
     *
     * @var ISODataContract[]
     */
    protected array $databaseInstances = [];


    /**
     * Constructor.
     *
     * @param array $datasets
     */
    public function __construct(array $datasets = [])
    {
        $this->datasets = array_merge($this->datasets, $datasets);
    }


    /**
     * Call an ISO model.
     *
     * @param string $name
     * @param array $args
     * @return ISOModelContract
     * @throws ISOModelNotFound
     */
    public function __call(string $name, array $args)
    {
        $model = '\\Juanparati\\ISOCodes\\Models\\' . ucfirst(Pluralizer::singular($name)) . 'Model';

        if (!isset($this->modelInstances[$model])) {
            if (class_exists($model)) {
                $this->modelInstances[$model] = new $model($this);
            } else {
                throw new ISOModelNotFound($model . ' not found');
            }
        }

        return $this->modelInstances[$model];
    }


    /**
     * Return the list of loaded databases.
     *
     * @param string
     * @return ISODataContract
     */
    public function getDatabaseInstance(string $key): ISODataContract
    {
        if (!isset($this->databaseInstances[$key])) {
            $this->databaseInstances[$key] = new $this->datasets[$key]();
        }

        return $this->databaseInstances[$key];
    }
}
