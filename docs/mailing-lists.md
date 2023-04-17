# MailingLists

## MailingList Entity

The list object in the API is handled by the MailingList Entity.

> see the [documentation](https://api.laposta.nl/doc/index.en.php#lists)

## Usage

* `all()` - to retrieve all lists
* `get(list_id)` - to retrieve a list
* `create(mailinglist)` - to add a mailing list
* `update(mailinglist)` - to update a list
* `delete(list_id)` - to delete a list
* `purge(list_id)` - to empty a mailing list
* `bulk(list_id, subscribers, flags)` - to upsert members into a mailing list

### Create Mailing List

```php
use DMT\CommandBus\Validator\ValidationException;
use DMT\Laposta\Api\Clients\MailingLists;
use DMT\Laposta\Api\Entity\MailingList;
use Psr\Http\Client\ClientExceptionInterface;

$mailingList = new MailingList();
$mailingList->name = 'Customers mailing';

try {
    /** @var MailingLists $client */
    $client->create($mailingList);

    echo $mailingList->id; // will show an id like "BaImMu3JZA" 
} catch (ValidationException $exception) {
     // input was wrong 
} catch (ClientExceptionInterface $exception) {
    // error response
}
```

### Get Mailing List

```php
use DMT\Laposta\Api\Clients\MailingLists;
use DMT\Laposta\Api\Entity\MailingList;
use DMT\Laposta\Api\Exceptions\NotFoundException;
use Psr\Http\Client\ClientExceptionInterface;

$mailingList = new MailingList();
$mailingList->name = 'Customers mailing';

try {
    /** @var MailingLists $client */
    $mailingList = $client->get('BaImMu3JZA');
} catch (NotFoundException $exception) {
     // list not found
}
```

### Update Mailing List

```php
use DMT\Laposta\Api\Clients\MailingLists;
use DMT\Laposta\Api\Entity\MailingList;
use Psr\Http\Client\ClientExceptionInterface;

try {
    /** @var MailingList $mailingList */
    $mailingList->remarks = 'Contains only subscriptions after march 2013';
    
    /** @var MailingLists $client */
    $client->update($mailingList);
} catch (ClientExceptionInterface $exception) {
    // error response
}
```

### Delete Mailing List

```php
use DMT\Laposta\Api\Clients\MailingLists;
use DMT\Laposta\Api\Exceptions\NotFoundException;

try {
    /** @var MailingLists $client */
    $client->delete('BaImMu3JZA');
} catch (NotFoundException $exception) {
     // list not found
}
```

### Purge Mailing List

```php
use DMT\Laposta\Api\Clients\MailingLists;
use DMT\Laposta\Api\Exceptions\NotFoundException;

try {
    /** @var MailingLists $client */
    $client->purge('BaImMu3JZA');
} catch (NotFoundException $exception) {
     // list not found
}
```

## Bulk Mailing List

```php
use DMT\Laposta\Api\Clients\MailingLists;
use DMT\Laposta\Api\Entity\Subscriber;

try {
    /** @var array<Subscriber> $subscriptions */
    /** @var MailingLists $client */
    $report = $client->bulk(
        'BaImMu3JZA', 
        $subscriptions, 
        MailingLists::BULK_INSERT | MailingLists::BULK_UPDATE
    );
    
    if ($report->summary->errors) {
        var_dump($report->errors);
    }
} catch (ClientExceptionInterface $exception) {
    // error response, nothing precessed
}
```
