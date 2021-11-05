<?php

/**
 * Configuration file for ISOCodes.
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Databases
    |--------------------------------------------------------------------------
    |
    */
    'databases' => [
        'countryCodes'   => \Juanparati\ISOCodes\Databases\Countries\CountryCodes::class,
        'countries'      => \Juanparati\ISOCodes\Databases\Countries\CountriesEN::class,
        'currencies'     => \Juanparati\ISOCodes\Databases\Currencies\CurrenciesEN::class,
        'currencyNumbers'=> \Juanparati\ISOCodes\Databases\Currencies\CurrencyNumbers::class,
        'languages'      => \Juanparati\ISOCodes\Databases\Languages\LanguagesEN::class,
        'continents'     => \Juanparati\ISOCodes\Databases\Continents\ContinentsEN::class,
    ],

];