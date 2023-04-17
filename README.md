# Laposta API 

An Object-Oriented client to consume the Laposta API.

## Install

```bash
composer require dmt-software/laposta-api
```

After installing this package, add a configuration file or add the configuration to your dependency container.
then run the command to generate an entity for the custom fields for your mailing list. See the  
[API subscribers](/docs/subscribers.md) documentation how to do this.

## Usage

### Subscribe a user to a mailing list

```php
use DMT\CommandBus\Validator\ValidationException;
use DMT\Laposta\Api\Entity\BaseCustomFields;
use DMT\Laposta\Api\Entity\Subscriber;
use DMT\Laposta\Api\Clients\Subscribers;
use Psr\Http\Client\ClientExceptionInterface;

try {
    /** @var BaseCustomFields $customFields The generated entity for your list */
    
    $subscriber = new Subscriber();
    $subscriber->listId = 'BaImMu3JZA';
    $subscriber->email = 'user@example.com';
    $subscriber->customFields = $customFields;
    $subscriber->customFields->name = 'John Do';
    
    /** @var Subscribers $client */
    $client->create(
        $subscriber, 
        Subscribers::OPTION_IGNORE_DOUBLEOPTIN | Subscribers::OPTION_SUPPRESS_EMAIL_NOTIFICATION
    );
} catch (ValidationException $exception) {
     // input was wrong 
} catch (ClientExceptionInterface $exception) {
    // error response
}
```

More on how to use this package can be found in the client [documentation](/docs/clients.md).

