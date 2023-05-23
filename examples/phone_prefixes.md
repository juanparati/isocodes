# Generate a list of phone prefixes by country

```php
return \ISOCodes::countries()
        ->all()
        ->sortBy('name')
        ->map(fn($r) => [
            'label' => "+{$r->phone_code} ({$r->name})",
            'value' => "+{$r->phone_code}"
        ])
        ->values();
```