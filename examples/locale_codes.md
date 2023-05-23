# Generate a list of locale codes


```php
\ISOCodes::countries()
    ->setResolution('languages', \Juanparati\ISOCodes\Models\CountryModel::NODE_AS_ALL)
    ->all()
    ->map(function ($r) {
        $codes = [];
    
        foreach ($r->languages as $langCode => $lang) {
            $codes[] = [
                'label' => "$lang ({$r->name})",
                'value' => sprintf("%s_%s", strtolower($langCode), $r->alpha2)
            ];
        }
    
        return $codes;
    })
    ->flatten(1)
    ->sortBy('label')
    ->values();
```
