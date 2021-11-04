# ISOCodes

## What is it?

A PHP library inspired in that provides a list of structured ISO codes oriented to geography/geopolitical information.

This library provides the following ISOs and codes:
- [ISO 3166-2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2) (Geographical codes)
- [ISO 3166-3](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-3) (Geographical codes)
- [Country codes](https://www.iban.com/country-codes) (Geopolitical codes)
- [TLDs](https://en.wikipedia.org/wiki/Country_code_top-level_domain) (Regional TLD)
- [ISO 4217](https://en.wikipedia.org/wiki/ISO_4217) (Currency codes)
- [ISO 639-1](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes) (Language codes)

This library provides localized names for countries, currencies and languages. The library allows create custom/new locales.

## Composer

    composer require juanparati/isocodes

## Laravel

This library is compatible with Laravel 8.x+ however it can works as a standalone library.

### Facade registration

    'aliases' => [
        ...
        'ISOCodes' => \Juanparati\ISOCodes\Facades\ISOCodesFacade::class,
        ...
    ]


### Configuration

Publish configuration file (Required only when custom locales are required):

    artisan vendor:publish --provider="Juanparati\ISOCodes\Providers\ISOCodesProvider"


## Usage

The collection of results are returned as [Collections](https://laravel.com/docs/8.x/collections#method-flip).


Get the list of all country codes:

    (new ISOCodes)->byCountry()->all()->toArray();

It returns something like:

    [
    ...
        "AL"=> [
            "alpha2" => "AL",
            "alpha3" => "ALB",
            "numeric" => "008",
            "tld" => ".al",
            "currencies" => [
                'ALL'
            ],
            "languages" => [
                "SQ",
            ],
            "continents" => [
                "EU",
            ],
            "name" => "Albania"
        ]
    ...
    ];


Retrieve one specific country:

    (new ISOCodes)
        ->byCountry()
        ->all()
        ->where('alpha2', 'ES')
        ->first();

or

    (new ISOCodes)
        ->byCountry()
        ->byAlpha2('ES');


Retrieve all the countries located in Europe:

    (new ISOCodes)
        ->byCountry()
        ->all()
        ->whereIn('continents', ['EU']);


Retrieve all the countries that uses euro and sorted by numeric code descending:

    (new ISOCodes)
        ->byCountry()
        ->all()
        ->whereIn('currencies', ['EUR'])
        ->sortByDesc('numeric');
    