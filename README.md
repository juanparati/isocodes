# ISOCodes

## What is it?

A PHP library that provides a list of structured ISO codes oriented to geography/geopolitical information.

This library provides the following ISOs and codes:
- [ISO 3166-2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2) (Geographical codes)
- [ISO 3166-3](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-3) (Geographical codes)
- [Country codes](https://www.iban.com/country-codes) (Geopolitical codes)
- [TLDs](https://en.wikipedia.org/wiki/Country_code_top-level_domain) (Regional TLD)
- [ISO 4217](https://en.wikipedia.org/wiki/ISO_4217) (Currency codes)
- [ISO 639-1](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes) (Language codes)
- [International dialing codes](https://en.wikipedia.org/wiki/List_of_country_calling_codes)
- [Unicode flags](https://en.wikipedia.org/wiki/Regional_indicator_symbol)

This library provides localized names for countries, currencies and languages. The library allows to create custom/new locales.

RDMS like MySQL or SQLite is not required in order to use this library. All the database files are maintained in separate files that are loaded on demand in a way that keep a low footprint.

### Disclaimer

This library data is based on international standards recognized by global organizations, the author is not responsible about how the translations and geopolitical data is represented.

If you feel that this library data doesn't comply with the geopolitical views required by your project, please [register a custom database](#custom-databases-and-locales). 

## Composer

    composer require juanparati/iso-codes

## Laravel

This library is compatible with Laravel 8.x+ however it can work as a standalone library.

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

The list of results are returned as [Collections](https://laravel.com/docs/8.x/collections#method-flip).

### byCountry

Get the list of all country codes:

    (new ISOCodes)->byCountry()->all()->toArray();

It returns something like:

    [
    ...
        "AL"=> [
            "alpha2"     => "AL",
            "alpha3"     => "ALB",
            "numeric"    => "008",
            "tld"        => ".al",
            "currencies" => Collection [
                'ALL'
            ],
            "languages"  => Collection [
                "SQ",
            ],
            "continents" => Collection [
                "EU",
            ],
            "name"       => "Albania",
            "capital"    => "Tirana",
            "flag"       => "🇦🇱",
            "phone_code" => "355",
        ]
    ...
    ];


Retrieve one specific country:

    (new ISOCodes)
        ->byCountry()
        ->all()
        ->firstWhere('alpha2', 'ES');

or

    (new ISOCodes)
        ->byCountry()
        ->byAlpha2('ES');

Retrieve all the countries located in Europe:

    (new ISOCodes)
        ->byCountry()
        ->byContinent('EU');

Retrieve all the countries located *only* in Europe:

    (new ISOCodes)
        ->byCountry()
        ->byContinent('EU', true);

Retrieve all the countries located in Europe and Asia:

    (new ISOCodes)
        ->byCountry()
        ->byContinent(['EU', 'AS'], true);

Retrieve all the countries located in Europe or Asia

    (new ISOCodes)
        ->byCountry()
        ->byContinent(['EU', 'AS']);

Retrieve all the countries sorted by numeric code descending that uses *only* Euro as currency:

    (new ISOCodes)
        ->byCountry()
        ->all()
        ->where('currencies', ['EUR'])
        ->sortByDesc('numeric');

or

    (new ISOCodes)
        ->byCountry()
        ->byCurrency('EUR', true)
        ->sortByDesc('numeric');

Retrieve all the countries that uses *at least* Euro as currency:

    (new ISOCodes)
        ->byCountry()
        ->byCurrency('EUR');


Create a list of countries with their names (useful for a dynamic listbox):

    (new ISOCodes)
        ->byCountry()
        ->map(fn ($iso) => [
            'label' => $iso['name'] . ' (' . $iso['alpha2'] . ')',
            'value' => $iso['alpha2']
        ])
        ->sortBy('label')
        ->values();

Retrieve a list of countries that has Portuguese as one of their official languages:

    (new ISOCodes)
        ->byCountry()
        ->byLanguage('PT');

* Note that most spoken language should be always the first in the list.


### byLanguage

Get the list grouped by language:

    (new ISOCodes)->byLanguage()->all()->toArray();

It returns something like:

    [
    ...
        "CA" => [
            "code" => "CA",
            "name" => "Catalan",
            "countries" => Collection [
                [
                    "alpha2"     => "AD",
                    "alpha3"     => "AND",
                    "numeric"    => "020",
                    "tld"        => ".ad",
                    "currencies" => [ …1],
                    "languages"  => [ …1],
                    "continents" => [ …1],
                    "name"       => "Andorra",
                ],
                ...
            ],
            "currencies" => Collection [
                "EUR",
            ],
            "continents" => Collection [
                "EU",
            ],
        ]
    ...
    ];


### byContinent

Get the list grouped by continent.


### byCurrency

Get the list grouped by currency.


### byCurrencyNumber

Get the list grouped by currency number.


### Use currency numbers instead of currency codes.

The method `setCurrencyAsNumber` specify if the currency code is returned as a number.

Example:

    (new ISOCodes)
        ->byCountry()
        ->setCurrencyAsNumber(true)
        ->all();


### Node resolution

The method `setResolution` modify how the associated nodes are structured.

The available nodes are:
- currencies
- languages
- continents

The available node formats are:
- NODE_AS_CODE: return the values as codes (It is the default resolution)
- NODE_AS_NAME: return the values as the translated values (Example: Instead of 'DA' it returns 'Danish')
- NODE_AS_ALL: return the values as codes and translated values (Example: `['DA' => 'Danish']`)
- NODE_AS_NONE: the associated values are not included.

Examples:

    (new ISOCodes)
        ->byCountry()
        ->setResolution('currencies', ByCountryModel::NODE_AS_ALL)
        ->setResolution('languages', ByCountryModel::NODE_AS_NAME)
        ->setResolution('continents', ByCountryModel::NODE_AS_NONE)
        ->byAlpha2('PT');

returns the following:

    [
        "alpha2"     => "PT",
        "alpha3"     => "PRT",
        "numeric"    => "620",
        "tld"        => ".pt",
        "currencies" => [
            "EUR" => "Euro",
        ],
        "languages"  => [
            "Portuguese",
        ],
        "name"       => "Portugal",
        "capital"    => "Lisboa",
        "flag"       => "🇵🇹",
        "phone_code" => "351",
    ]

instead of:

    [
        "alpha2"     => "PT",
        "alpha3"     => "PRT",
        "numeric"    => "620",
        "tld"        => ".pt",
        "currencies" => [
            "EUR",
        ],
        "languages"  => [
            "PT",
        ],
        "continents" => [
            "EU",
        ],
        "name"       => "Portugal",
        "capital"    => "Lisboa",
        "flag"       => "🇵🇹",
        "phone_code" => "351",
    ]

The node resolutions works with the others models like "byCurrency", "byLanguage", etc.


## Custom databases and locales

It's possible to register custom databases and locales during the ISOCodes instantiation.


Example:

    new ISOCodes(['countries' => MyCountryTranslation::class])


## Contributions

Feel free to add new locales to this library and send me a pull request.

