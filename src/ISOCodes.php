<?php

namespace Juanparati\ISOCodes;


use Juanparati\ISOCodes\Contracts\ISODatabase;
use Juanparati\ISOCodes\Contracts\ISOModel;
use Juanparati\ISOCodes\Exceptions\ISOModelNotFound;
use Juanparati\ISOCodes\Models\ByCountryModel;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;


/**
 * Provide the main ISOCodes objects.
 */
class ISOCodes
{

    /**
     * Registered databases.
     *
     * @var ISODatabase[]
     */
    protected array $databases = [
        'countryCodes'   => \Juanparati\ISOCodes\Databases\Countries\CountryCodes::class,
        'countries'      => \Juanparati\ISOCodes\Databases\Countries\CountriesEN::class,
        'currencies'     => \Juanparati\ISOCodes\Databases\Currencies\CurrenciesEN::class,
        'currencyNumbers'=> \Juanparati\ISOCodes\Databases\Currencies\CurrencyNumbers::class,
        'languages'      => \Juanparati\ISOCodes\Databases\Languages\LanguagesEN::class,
        'continents'     => \Juanparati\ISOCodes\Databases\Continents\ContinentsEN::class,
    ];


    /**
     * Loaded models.
     *
     * @var ISOModel[]
     */
    protected array $modelInstances = [];


    /**
     * Loaded databases.
     *
     * @var ISODatabase[]
     */
    protected array $databaseInstances = [];


    /**
     * Constructor.
     *
     * @param array $databases
     * @param CacheInterface|null $cache
     */
    public function __construct(array $databases = []) {
        $this->databases = array_merge($this->databases, $databases);
    }


    /**
     * Call an ISO model.
     *
     * @param string $name
     * @param array $args
     * @return ISOModel
     * @throws ISOModelNotFound
     */
    public function __call(string $name, array $args) {

        $model = '\\Juanparati\\ISOCodes\\Models\\' . ucfirst($name) . 'Model';

        if (!isset($this->modelInstances[$model])) {
            if (class_exists($model))
                $this->modelInstances[$model] = new $model($this);
            else
                throw new ISOModelNotFound($model . ' not found');
        }

        return $this->modelInstances[$model];
    }


    /**
     * Return the list of loaded databases.
     *
     * @param string
     * @return ISODatabase
     */
    public function getDatabaseInstance(string $key) : ISODatabase {
        if (!isset($this->databaseInstances[$key]))
            $this->databaseInstances[$key] = new $this->databases[$key];

        return $this->databaseInstances[$key];
    }
}