# Concepts

## Clients

A client is available for each endpoint. Most of these clients contain the following methods:
 * all() - to retrieve a collection of objects 
 * get() - to retrieve one object 
 * create(object) - to add an object  
 * update(object) - to modify an object
 * delete() - to delete an object

## Create\Update

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
