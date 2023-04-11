# Laposta API 

An Object-Oriented client to consume the Laposta API.

## Install

```bash
composer require dmt-software/laposta-api
```

After installing this package, edit or add a configuration file or add the configuration to your dependency container.
then run the command to generate list fields, to generate a class for the custom fields of the subscribers.

```
vendor/bin/laposta generate:list-fields <config or bootstrap file> -l <list-id> 
```

## Usage

```php
use DMT\CommandBus\Validator\ValidationException;
use DMT\Laposta\Api\Interfaces\Exception;
use DMT\Laposta\Api\Clients\Subscribers;

try {
    /** @var Subscribers $client */
    $client->get('BaImMu3JZA', 'user@example.com');
} catch (ValidationException $exception) {
     // input was wrong 
} catch (Exception $exception) {
    // not found or error response
} catch (Throwable) {
    // debug and fix or file it.   
}
```
