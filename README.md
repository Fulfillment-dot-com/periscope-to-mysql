# periscopeToSQL

periscopedata.com is a powerful and useful tool for generating reports, but sometimes I want to put what I write in Periscope to use elsewhere. This package allows you to write once but generate MySQL statements with filters and daterange within PHP.

### Limits

You bet...

* UTC is assumed to be used by your DB
* Always converts to EST
* Unaware of foreign keys = unable to automatically join tables

### Example

```php
use Fulfillment\periscopeToSQL\periscopeToSQL;

$args = [
    'dateStart' => "2016-01-01",
    'dateEnd'   => "2016-03-11",
    'Warehouse' => "1,2,3",
    'State'     => "GA",
];

$sql = 'SELECT
            yourFirstTable.*,
            yourSecondTable.part2
        FROM
            yourFirstTable
            JOIN yourSecondTable ON yourFirstTable.id = yourSecondTable.id
        WHERE
            [yourFirstTable.recordedOn=daterange:est]
            AND [yourFirstTable.warehouse=Warehouse]
            AND [yourSecondTable.state=State]';

$sql = periscopeToSQL::fillTemplate($sql, $args);
```

###  Installation

WARNING: this project is still in development

#### Composer

```sh
composer require fulfillment/periscope-to-mysql
```

```json
"require": {
    "fulfillment/periscope-to-mysql": "dev-master"
}
```

#### Local

```json
"repositories": [
    {
        "type": "path",
        "url": "../periscope-to-mysql"
    }
],
"require": {
    "fulfillment/periscope-to-mysql": "dev-master"
}
```

### Contribute

Feel free, time zone support may be a good place to start.
