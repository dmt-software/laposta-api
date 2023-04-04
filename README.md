# Laposta API 

## Goal

> make the Laposta api client fully object-oriented.

## Install

```bash
composer require dmt-software/laposta-api
```

After installing this package, edit or add a configuration file or add the configuration to your dependency container.
then run the command to generate list fields, to generate a class for the custom fields of the subscribers.

```
vendor/bin/laposta generate:list-fields <config or bootstrap file> -l <list-id> 
```


## Concepts

### Numeric fields

> NOTE: Numeric fields are considered to be a float by default.
> To force the field to be an integer set the `defaultvalue` for that field to an integer.

### Create\Update

The clients do not return the data on create, update or delete actions, instead 
they update the object send to the server. 

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

* ~~__[HIGH]__ find a way to serialize http post for child objects.~~
* Field options have some unpredictable behavior when updating a field.
  Find a way to deal with it and/or post a bug report.
* ~~Create http middleware to throw exceptions when the request ends in a error response.~~
* ~~Create custom-fields entity generator that represents the user defined member fields of a list.~~
* Document stuff, document stuff, document stuff.
* Add extra functionality to objects that implement Collection interface.
* ~~Create factory/builder or `dummy` container to ease client creation.~~ 
* Create Laposta SDK package for managing generating entities, webhooks, pages and/or html forms.
* Test post multiple_select http-post serialization.
* ~~Add validation middleware to command bus to validate before calling any client.~~
* Add console commands to check ~~and render custom field~~ entities.
* Write test, test and more test.
