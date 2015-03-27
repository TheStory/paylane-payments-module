API
===

Configuration
-------------

    return [
        'store_payments' => true, // enable/disable storage of payments info in db
    ];

Services
--------

### payments.taxes

EU taxes operations.

Methods:

- **getTaxForCountry($countryCode)** - Get VAT tax for country
    - _countryCode_ - ISO country code eg. PL