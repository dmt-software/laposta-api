# Fields 

## Field Entity

The field object in the API is handled by the Field Entity.

> see the [documentation](https://api.laposta.nl/doc/index.en.php#fields)

### Numeric fields

> NOTE: Numeric fields are considered to be a float by default within the generated custom fields objects.
> To force the field to be an integer set the `defaultvalue` for that field to an integer.

### Select fields 

> NOTE: optionsFull are only available on update, not when creating a field.
> On update the options array will be ignored, to ensure a more predictable result.

### Date fields

> NOTE: Within the generated entities the date type fields will be handling DateTime objects.
 
## Usage

* `all(list_id)` - to retrieve all fields within a list
* `get(list_id, field_id)` - to retrieve one field from a list
* `create(field)` - to add a field to a list
* `update(field)` - to modify a list's field
* `delete(list_id, field_id)` - to delete a field from list

### Create Field

```php
use DMT\CommandBus\Validator\ValidationException;
use DMT\Laposta\Api\Clients\Fields;
use DMT\Laposta\Api\Entity\Field;
use Psr\Http\Client\ClientExceptionInterface;

$field = new Field();
$field->name = 'First name';
$field->listId = 'BaImMu3JZA';

try {
    /** @var Fields $client */
    $client->create($field);
    
    // after creation the Field instance is updated
    echo $field->customName; // will show firstname 
} catch (ValidationException $exception) {
     // input was wrong 
} catch (ClientExceptionInterface $exception) {
    // error response
}
```

### Update Field

Always use the options_full when the options need to change for a field.
To add one option to the existing options of a field, you need to post the existing options and add an extra option.
For this option the id can be empty. (Currently it is not possible to add more than one option at a time)

```php
use DMT\Laposta\Api\Clients\Fields;
use DMT\Laposta\Api\Exceptions\NotFoundException;
use Psr\Http\Client\ClientExceptionInterface;

try {
    /** @var Fields $client */
    $field = $client->get('BaImMu3JZA', 'GeVKetES6z');
    $field->required = true;
} catch (NotFoundException $exception) {
    // not found
}

try {
    $client->update($field);
} catch (ClientExceptionInterface $exception) {
    // error response
}
```