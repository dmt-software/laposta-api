# Laposta API 

An Object-Oriented client to consume the Laposta API.

## Install

```bash
composer require dmt-software/laposta-api
```

After installing this package, edit or add a configuration file or add the configuration to your dependency container.
then run the command to generate list fields, to generate a class for the custom fields of the subscribers.

> See the [API subscribers](/docs/subscribers.md) documentation.

## Usage

```php
use DMT\CommandBus\Validator\ValidationException;
use DMT\Laposta\Api\Clients\Subscribers;
use Psr\Http\Client\ClientExceptionInterface;

try {
    /** @var Subscribers $client */
    $subscribers = $client->all('BaImMu3JZA', 'user@example.com');
} catch (ValidationException $exception) {
     // input was wrong 
} catch (ClientExceptionInterface $exception) {
    // error response
}
```

> See the [API subscribers](/docs/subscribers.md) for more usage examples.
