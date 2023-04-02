# Laposta API 

## Goal

> make the Laposta api client fully object-oriented.

## Concepts

### Create\Update

```php
use DMT\Laposta\Api\Entity\Field;
use DMT\Laposta\Api\Clients\Fields;

$field = new Field();
$field->listId = 'BaImMu3JZA';
$field->name = 'Company';

try {
    /** @var Fields $client */
    $client->create($field);
    
    print($field->id); // outputs something like: GeVKetES6z
} catch (\Exception $exception) {
    die($exception->getMessage());
}
```

## Todo

* Field options have some unpredictable behavior when updating a field.
  Find a way to deal with it and/or post a bug report.
* Create http middleware to throw exceptions when the request ends in a error response.
* Create Member entity generator that represents a member of a list.
* Document stuff.
* (maybe) Add extra functionality to objects that implement Collection interface.
* Create factory/builder or `dummy` container to ease client creation. 