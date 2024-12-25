<?php

namespace Juanparati\ISOCodes;

use Illuminate\Support\Pluralizer;
use Juanparati\ISOCodes\Contracts\ISODataContract;
use Juanparati\ISOCodes\Contracts\ISOModelContract;
use Juanparati\ISOCodes\Enums\NodeResolution;
use Juanparati\ISOCodes\Exceptions\ISOModelNotFound;

/**
 * Provide the main ISOCodes objects.
 *
 * @method \Juanparati\ISOCodes\Models\ContinentModel continents()
 * @method \Juanparati\ISOCodes\Models\CountryModel countries()
 * @method \Juanparati\ISOCodes\Models\CurrencyModel currencies()
 * @method \Juanparati\ISOCodes\Models\CurrencyNumberModel currencyNumbers()
 * @method \Juanparati\ISOCodes\Models\LanguageModel languages()
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
     * Default resolutions.
     *
     * @var array
     */
    protected array $defaultResolutions = [
        'currencies'  => NodeResolution::NODE_AS_CODE,
        'continents'  => NodeResolution::NODE_AS_CODE,
        'languages'   => NodeResolution::NODE_AS_CODE,
    ];


    /**
     * Default options.
     *
     * @var array|false[]
     */
    protected array $defaultOptions = [
        'currencyAsNumber' => false
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
     * @param array $defaultResolutions
     * @param array $defaultOptions
     */
    public function __construct(
        array $datasets = [],
        array $defaultResolutions = [],
        array $defaultOptions = [],
    )
    {
        $this->datasets = array_merge($this->datasets, $datasets);
        $this->defaultResolutions = array_merge($this->defaultResolutions, $defaultResolutions);
        $this->defaultOptions = array_merge($this->defaultOptions, $defaultOptions);
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


        foreach ($this->defaultResolutions as $node => $resolution) {
            $this->modelInstances[$model]->setResolution($node, $resolution);
        }

        return $this->modelInstances[$model]
            ->setCurrencyAsNumber($this->defaultOptions['currencyAsNumber'] ?? false);
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
