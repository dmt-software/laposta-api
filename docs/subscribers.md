# Subscribers

## Subscriber Entity

The member object in the API is handled by the Subscriber Entity.

> see the [documentation](https://api.laposta.nl/doc/index.en.php#members)

### Default CustomFields

By default, the Subscriber will contain a CustomFields entity that contains several instances of CustomField entities.
These custom fields are the ones set for the list, where the `name` equals the field `custom_name` and the `value` is
set with the value of the subscriber.

```php
use DMT\Laposta\Api\Entity\CustomFields;
use DMT\Laposta\Api\Entity\Subscriber;

$subscriber = new Subscriber();
$subscriber->email = 'user@example.org';
$subscriber->listId = 'BaImMu3JZA';
$subscriber->customFields = new CustomFields();
$subscriber->customFields->field[0] = new CustomField();
$subscriber->customFields->field[0]->name = 'name';
$subscriber->customFields->field[0]->value = 'John Do';
```

### Generated CustomFields

> NOTE: Always check the generated entities before using them.   

To make implementing this client easier a manual generated CustomFields Entity can be used instead of the default 
implementation, where you need to set both field name and value.

These entities can be generated by the `generate:list-fields` command. After generating make sure the generated 
entity is located in the correct directory, or move it there if it is generated elsewhere. Finally, edit your config as 
the output of the script suggests.

```
vendor/bin/laposta generate:list-fields configuration.php -l BaImMu3JZA -c Your\\Space\\CustomFields -d src/Your/Space
```

Once everything is in place the Subscriber entity will use this added code.

```php
use DMT\Laposta\Api\Entity\Subscriber;
use Your\Space\CustomFields;

$subscriber = new Subscriber();
$subscriber->email = 'user@example.org';
$subscriber->listId = 'BaImMu3JZA';
$subscriber->customFields = new CustomFields();
$subscriber->customFields->name = 'John Do';
```

## Usage

* `all(list_id)` - to retrieve all subscribers to a list
* `get(list_id, identifier)` - to retrieve one subscriber from a list, using email or member ID
* `create(subscriber, flags)` - to add a subscriber to a list, the flags can be used to override subscribe behavior
  * `Subscriber::OPTION_SUPPRESS_EMAIL_NOTIFICATION` - to ensure no notification is sent
  * `Subscriber::OPTION_SUPPRESS_EMAIL_WELCOME` - to ensure no welcome mail is sent 
  * `Subscriber::OPTION_IGNORE_DOUBLEOPTIN` - to ignore the subscriber needs to verify their subscription 
* `update(subscriber)` - to modify a list's subscriber
* `delete(list_id, identifier)` - to delete a field from list, using email or member ID

### Create Subscriber

```php
use DMT\CommandBus\Validator\ValidationException;
use DMT\Laposta\Api\Clients\Subscribers;
use DMT\Laposta\Api\Entity\Subscriber;
use Psr\Http\Client\ClientExceptionInterface;

$subscriber = new Subscriber();
$subscriber->email = 'user@example.org';
$subscriber->listId = 'BaImMu3JZA';

try {
    /** @var Subscribers $client */
    $client->create($subscriber);
    
    // after creation the Subscriber instance is updated
    return $subscriber->id;
} catch (ValidationException $exception) {
     // input was wrong 
} catch (ClientExceptionInterface $exception) {
    // error response
}
```

### Get Subscriber

```php
use DMT\Laposta\Api\Clients\Subscribers;
use DMT\Laposta\Api\Exceptions\NotFoundException;

try {
    /** @var Subscribers $client */
    $subscriber = $client->get('BaImMu3JZA', 'user@example.org');
} catch (NotFoundException $exception) {
    // not found
}
```

### Update Subscriber

```php
use DMT\Laposta\Api\Clients\Subscribers;
use DMT\Laposta\Api\Entity\Subscriber;
use DMT\Laposta\Api\Exceptions\NotFoundException;
use Psr\Http\Client\ClientExceptionInterface;

try {
    /** @var Subscribers $client */
    $subscriber = $client->get('BaImMu3JZA', 'user@example.org');
    $subscriber->customFields->name = 'Jane Do'; // assuming generated custom fields
} catch (NotFoundException $exception) {
    // not found
}

try {
    $client->update($subscriber);
} catch (ClientExceptionInterface $exception) {
    // error response
}
```

### Delete Subscriber
```php
use DMT\Laposta\Api\Clients\Subscribers;
use DMT\Laposta\Api\Exceptions\NotFoundException;

try {
    /** @var Subscribers $client */
    $subscriber = $client->delete('BaImMu3JZA', 'user@example.org');
} catch (NotFoundException $exception) {
    // not found
}
```
