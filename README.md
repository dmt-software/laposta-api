# Laposta API 

An Object-Oriented client to consume the Laposta API.

## Install

```bash
composer require dmt-software/laposta-api
```

### Configure

After installing this package you need to configure it. The quickest way to do this is simply add a config file and 
use it to load the config into a `Config` instance.  

```php 
// file: config.php

return [
    'apiKey' => 'JdMtbsMq2jqJdQZD9AHC',
    'customFieldsClasses' => [],
    'httpClient' => \GuzzleHttp\Client::class,
    'requestFactory' => \GuzzleHttp\Psr7\HttpFactory::class,
];
```

### Generate entity

The next step is to generate an entity for the custom fields for the mailing list(s).

```bash
vendor/bin/laposta generate:list-fields config.php -l BaImMu3JZA
```

More in depth information about the custom fields can be found in the subscribers [documentation](/docs/subscribers.md).

## Usage

### Initiate a Client

The easiest way to create a client instance is using the factories in this package. These factories can also be used (as 
guideline) in a dependency injection container. 

```php
use DMT\Laposta\Api\Clients\Subscribers;
use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Factories\CommandBusFactory;

$commandBus = CommandBusFactory::create(Config::load('config.php'));

$client = new Subscribers($commandBus);
```

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
    $client->create($subscriber, Subscribers::OPTION_SUPPRESS_EMAIL_NOTIFICATION);
} catch (ValidationException $exception) {
     // input was wrong 
} catch (ClientExceptionInterface $exception) {
    // error response
}
```

More on how to use this package can be found in the client [documentation](/docs/clients.md).
