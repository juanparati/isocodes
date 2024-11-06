# 🌐 ISOCodes

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
- [Olson Timezones](https://en.wikipedia.org/wiki/List_of_tz_database_time_zones)
- [Unicode flags](https://en.wikipedia.org/wiki/Regional_indicator_symbol)
- [European Union Members](https://european-union.europa.eu/principles-countries-history/country-profiles_en)

This library provides localized names for countries, currencies and languages. The library allows to create custom/new locales.

RDMS like MySQL or SQLite is not required in order to use this library. All the data is maintained in separate files that are loaded and linked on demand in a way that keeps a low memory footprint.

### Disclaimer

This library data is based on international standards recognized by global organizations, the author is not responsible about how the translations and geopolitical data is represented.

If you feel that this library data doesn't comply with the geopolitical views required by your project, fell free to [register a custom dataset](#custom-dataset-and-locales). 

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

Publish configuration file (Required only when custom dataset or locales are required):

    artisan vendor:publish --provider="Juanparati\ISOCodes\Providers\ISOCodesProvider"


## Usage

The list of results are returned as [Collections](https://laravel.com/docs/9.x/collections).

### Country model examples

Get the list of all country codes as an array:

    (new ISOCodes)->countries()->toArray();

It returns something like this:

    [
    ...
        "ES"=> [
            "alpha2" => "ES",
            "alpha3" => "ESP",
            "numeric" => "724",
            "tld" => ".es",
            "currencies" => [
              "EUR",
            ],
            "languages" => [
              "ES",
              "CA",
              "GL",
              "EU",
            ],
            "continents" => [
              "EU",
            ],
            "capital" => "Madrid",
            "flag" => "🇪🇸",
            "phone_code" => "34",
            "eu_member" => true,
            "name" => "Spain",
            "timezones" => [
                "Europe/Madrid",
                "Africa/Ceuta",
                "Atlantic/Canary",
            ]
        ]
    ...
    ];


Retrieve all the countries as a Collection:

```php
(new ISOCodes)
    ->countries()
    ->all();
```

Retrieve one specific country:

```php
(new ISOCodes)
    ->countries()
    ->firstWhere('alpha2', 'ES');
```

or using the shortcut

```php
(new ISOCodes)
    ->countries()
    ->findByAlpha2('ES');
```

Retrieve all the countries located in Europe:

```php
(new ISOCodes)
    ->countries()
    ->whereContinent('EU');
```

Retrieve all the countries located **only** in Europe:

```php
(new ISOCodes)
    ->countries()
    ->whereContinent('EU', true);
```

Retrieve all the countries located in Europe and Asia:

```php
(new ISOCodes)
    ->countries()
    ->whereContinent(['EU', 'AS'], true);
```

Retrieve all the countries located in Europe **or** Asia

```php
(new ISOCodes)
    ->countries()
    ->whereContinent(['EU', 'AS']);
```

Retrieve all the countries sorted by numeric code descending that uses **only** Euro as currency:

```php
(new ISOCodes)
    ->countries()
    ->all()
    ->where('currencies', ['EUR'])
    ->sortByDesc('numeric');
```

or

```php
(new ISOCodes)
    ->countries()
    ->whereCurrency('EUR', true)
    ->sortByDesc('numeric');
```

Retrieve all the countries that uses **at least** Euro as currency:

```php
(new ISOCodes)
    ->countries()
    ->whereCurrency('EUR');
```

Create a list of countries with their names (useful for generate a listbox options):

```php
(new ISOCodes)
    ->countries()
    ->map(fn ($iso) => [
        'label' => $iso->name . ' (' . $iso->alpha2 . ')',
        'value' => $iso->alpha2
    ])
    ->sortBy('label')
    ->values();
```

Retrieve a list of countries that has Portuguese as one of their official languages:

```php
(new ISOCodes)
    ->countries()
    ->whereLanguage('PT');
```

* Note that most spoken language of each country should be always the first in the list.


### Language model examples
Get the list grouped by language:

```php
(new ISOCodes)->languages()->toArray();
```

It returns something like:

    [
    ...
        "CA" => [
            "code" => "CA",
            "name" => "Catalan",
            "countries" => [
                [
                    "alpha2"     => "AD",
                    "alpha3"     => "AND",
                    "numeric"    => "020",
                    "tld"        => ".ad",
                    "currencies" => [ …1],
                    "languages"  => [ …1],
                    "continents" => [ …1],
                    "name"       => "Andorra",
                    "timezones"  => [
                        "Europe/Andorra"
                    ]
                ],
                ...
            ],
            "currencies" => [
                "EUR",
            ],
            "continents" => [
                "EU",
            ],
        ]
    ...
    ];


### Continent model examples

Get the list grouped by continent.

Example:

```php
(new ISOCodes)->continents()->toArray();
```


### Currency model examples

Get the list grouped by currency.

Example:

```php
(new ISOCodes)->currencies()->toArray();
```

### CurrencyNumber model examples

Get the list grouped by currency number.

Example:

```php
(new ISOCodes)->currencyNumbers()->toArray();
```

### Property access

Each record array member can be accessed using the array and object syntax.

Example:

```php
$spain = (new ISOCodes)
    ->countries()
    ->findByAlpha2('ES');

$spain->name;    // Spain
$spain['name'];  // Spain

$spain->toArray();  // Get record as array
$spain->toJson();   // Get record as Json
```

Each record is serializable, that it make it ideal in order to store the results into a cache.


### Use currency numbers instead of currency codes.

The method `setCurrencyAsNumber` specify if the currency code is returned as a number.

Example:

```php
(new ISOCodes)
    ->countries()
    ->setCurrencyAsNumber(true)
    ->all();
```


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

```php
(new ISOCodes)
    ->countries()
    ->setResolution('currencies', \Juanparati\ISOCodes\Enums\NodeResolution::NODE_AS_ALL)
    ->setResolution('languages', \Juanparati\ISOCodes\Enums\NodeResolution::NODE_AS_ALL)
    ->setResolution('continents', \Juanparati\ISOCodes\Enums\NodeResolution::NODE_AS_ALL)
    ->findByAlpha2('PT')
    ->toArray();
```

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
        "eu_member"  => true,
        "timezones"  => [
            "Europe/Lisbon",
            "Atlantic/Azores",
            "Atlantic/Madeira",
        ],
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
        "eu_member"  => true,
        "timezones"  => [
            "Europe/Lisbon",
            "Atlantic/Azores",
            "Atlantic/Madeira",
        ],
    ]

The node resolutions works with the others models like "currencies", "languages", etc.

#### Node resolutions and immutability

When the resolution is changed it will be back to the previous state in the next model call.

Example:

```php
$iso = new ISOCodes();

echo $iso->countries()
    ->setResolution('currencies', \Juanparati\ISOCodes\Enums\NodeResolution::NODE_AS_ALL)
    ->findByAlpha2('PT')
    ->currencies[0];  // Returns "Euro"

echo $iso->countries()
    ->findByAlpha2('PT')
    ->currencies[0];  // Returns "EUR"
```
In order to keep persistent the resolutions it's possible to pass the resolution values to the constructor. Example:

```php
$iso = new ISOCodes(new ISOCodes(defaultResolutions: [
            'currencies' =>  \Juanparati\ISOCodes\Enums\NodeResolution::NODE_AS_NAME
        ]);
```

## Main language and timezone

- The more spoken language is displayed first in the list.
- The country capital timezone is displayed first in the list.


## Custom dataset and locales

It's possible to register custom datasets and locales during the ISOCodes instantiation.


Example:

```php
new ISOCodes(['countries' => MyCountryTranslation::class])
```

See the following example with the [country names](./src/Data/Countries/CountriesEN.php).


## Macroable models

The models are macroable so it's possible to inject custom methods.

Example:

```php
\Juanparati\ISOCodes\Models\CountryModel::macro('allEUMembers', function () {
    return $this->where('eu_member', true)->all();
});

(new ISOCodes)->countries()->allEUMembers()->count();   // 27
```

## Contributions

Feel free to add new locales to this library and send me a pull request.
