# Error handling

## Validation Error

When fully configured, the clients will throw a validation exception before the service is called (in most cases).
These exception contain a list of validation errors that can be used to inform the end-user.

```php
use DMT\CommandBus\Validator\ValidationException;
use DMT\Laposta\Api\Clients\Subscribers;
use DMT\Laposta\Api\Entity\Subscriber;
use Psr\Http\Client\ClientExceptionInterface;

$subscriber = new Subscriber();
$subscriber->email = 'user.example.org'; // invalid email address
$subscriber->listId = 'BaImMu3JZA';

try {
    /** @var Subscribers $client */
    $client->create($subscriber);
} catch (ValidationException $exception) {
    echo '<h2>Could not subscribe user</h2>';
    foreach ($exception->getViolations() as $violation) {
        echo '<span class="error">' . $violation->getPropertyPath() . 'was wrong</span>'; // customFields.name
        echo '<span class="error">' . $violation->getMessage() . '</span>'; // name can not be empty
    }
}
```

> see also the [documentation](https://symfony.com/doc/5.4/components/validator.html) for the Symfony validator
> component.

## ClientExceptionInterface

The client use a PSR-18 compliant http client to call a method on the Laposta server.
Errors within this client will be thrown as Exceptions that implement the `ClientExceptionInterface`.
These can be caused by:
* Network errors
* NotFound (status 404)
* ClientException (status 400, 401 ... 500)

```php
use DMT\Laposta\Api\Clients\Subscribers;
use DMT\Laposta\Api\Exceptions\NotFoundException;
use DMT\Laposta\Api\Exceptions\ClientException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\NetworkExceptionInterface;

try {
    /** @var Subscribers $client */
    $client->get('BaImMu3JZA', '9978ydioiZ');
} catch (NotFoundException $exception) {
    print('alert("member does not exist");');
} catch (ClientException $exception) {
    echo '<h2>Error processing request</h2>';
    $error = $exception->getError();
    if ($error) {
        echo '<span class="error">' . $error->message . '</span>';
        echo '<span class="error">' . $error->code . '</span>';
    }
} catch (NetworkExceptionInterface|ClientExceptionInterface $exception) {
    trigger_error($exception->getMessage(), E_ERROR);
}
```
> see also the [documentation](https://api.laposta.nl/doc/index.en.php#auth) on error messages.
