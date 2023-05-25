<?php

/**
 * Configuration file for ISOCodes.
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Datasets
    |--------------------------------------------------------------------------
    |
    */
    'datasets' => [
        'countryCodes'   => \Juanparati\ISOCodes\Data\Countries\CountryCodes::class,
        'countries'      => \Juanparati\ISOCodes\Data\Countries\CountriesEN::class,
        'currencies'     => \Juanparati\ISOCodes\Data\Currencies\CurrenciesEN::class,
        'currencyNumbers'=> \Juanparati\ISOCodes\Data\Currencies\CurrencyNumbers::class,
        'languages'      => \Juanparati\ISOCodes\Data\Languages\LanguagesEN::class,
        'continents'     => \Juanparati\ISOCodes\Data\Continents\ContinentsEN::class,
    ],


    /*
    |--------------------------------------------------------------------------
    | Default resolutions
    |--------------------------------------------------------------------------
    |
    */
    'resolutions' => [
        'currencies'  => \Juanparati\ISOCodes\Enums\NodeResolution::NODE_AS_CODE,
        'continents'  => \Juanparati\ISOCodes\Enums\NodeResolution::NODE_AS_CODE,
        'languages'   => \Juanparati\ISOCodes\Enums\NodeResolution::NODE_AS_CODE,
    ],


    /*
    |--------------------------------------------------------------------------
    | Additional options
    |--------------------------------------------------------------------------
    |
    */
    'options' => [
        'currencyAsNumber' => false   // Specify if the currency code is returned as a number.
    ],

];