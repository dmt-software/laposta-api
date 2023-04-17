# Webhooks

## Entities

The webhook object in the API is handled by the Webhook Entity.
When the webhook is called the Event Entity is used.

> see also the Laposta [documentation](https://api.laposta.nl/doc/index.en.php#webhooks) on webhooks 

## Processing 

To process a called webhook the `WebhookProcessingService` can be used. 
Because it is mandatory to process the subscribers who unsubscribe or bounce, the service requires at least a
processor to handle these. Other type of events can be left out.

```php
use DMT\Laposta\Api\Config;
use DMT\Laposta\Api\Factories\SerializerFactory;
use DMT\Laposta\Api\Interfaces\EventProcessor;
use DMT\Laposta\Api\Services\WebhookProcessingService;

/** @var Config $config */
/** @var EventProcessor $deactivateEventProcessor */
$webhookService = new WebhookProcessingService(
    SerializerFactory::create($config),
    $deactivateEventProcessor
);

$webhookService->process('{"data":[ ..... ]}');
```

### EventProcessors

To process a webhook you can implement the `EventProcessor` interface or use the `CallbackEventProcessor` with a 
callback function that processes a single event.

```php
use DMT\Laposta\Api\Entity\Event;
use DMT\Laposta\Api\Entity\EventInformation;
use DMT\Laposta\Api\Interfaces\EventProcessor;
use DMT\Laposta\Api\Services\Processors\CallbackEventProcessor;
use DMT\Laposta\Api\Services\WebhookProcessingService;
use JMS\Serializer\Serializer;

class DeactivateWebhookEventProcessor implements EventProcessor
{
    private object $userRepository;

    public function process(Event $event): void
    {
        $user = $this->userRepository->findByEmail($event->subscriber->email);
        
        $user->newsletter = false;
        if ($event->info->action == EventInformation::DEACTIVATE_ACTION_HARDBOUNCE) {
            $user->deleted = true;
        }
        
        $this->userRepository->save($user);
    }
}

/** @var Serializer $serializer */
$service = new WebhookProcessingService(
    $serializer,
    new DeactivateWebhookEventProcessor(),
    new CallbackEventProcessor(function (Event $event) {
        // process the subscription in event
    })
);
```

## Usage

* `all(list_id)` - to retrieve all webhooks for a list
* `get(list_id, webhook_id)` - to retrieve one webhook from a list
* `create(webhook)` - to add a webhook to a list
* `update(webhook)` - to modify a list's webhook
* `delete(list_id, webhook_id)` - to delete a webhook from list

### Create webhook

```php
use DMT\CommandBus\Validator\ValidationException;
use DMT\Laposta\Api\Clients\Webhooks;
use DMT\Laposta\Api\Entity\Webhook;
use Psr\Http\Client\ClientExceptionInterface;

$webhook = new Webhook();
$webhook->listId = 'BaImMu3JZA';
$webhook->event = Webhook::EVENT_DEACTIVATED;
$webhook->url = 'https://example.net/webhook/';

try {
    /** @var Webhooks $client */
    $client->create($webhook);

    echo $webhook->id; // will show an id like "cW5ls8IVJl" 
} catch (ValidationException $exception) {
     // input was wrong 
} catch (ClientExceptionInterface $exception) {
    // error response
}
```

### Get Webhook

```php
use DMT\Laposta\Api\Clients\Webhooks;
use DMT\Laposta\Api\Exceptions\NotFoundException;

try {
    /** @var Webhooks $client */
    $webhook = $client->get('BaImMu3JZA', 'cW5ls8IVJl');
} catch (NotFoundException $exception) {
    // webhook not found
}
```

### Update Webhook

To update a webhook first retrieve the webhook from the server using (get/all).
Modify the received object and call the update method. The webhook is now modified.  
In the example a webhook is (temporary) deactivated from being used by Laposta. 

```php
use DMT\CommandBus\Validator\ValidationException;
use DMT\Laposta\Api\Clients\Webhooks;
use DMT\Laposta\Api\Entity\Webhook;
use Psr\Http\Client\ClientExceptionInterface;

try {
    /** @var Webhook $webhook */
    $webhook->blocked = true;
    
    /** @var Webhooks $client */
    $client->update($webhook); 
} catch (ClientExceptionInterface $exception) {
    // error response
}
```

### Delete Webhook

```php
use DMT\Laposta\Api\Clients\Webhooks;
use DMT\Laposta\Api\Exceptions\NotFoundException;

try {
    /** @var Webhooks $client */
    $client->delete('BaImMu3JZA', 'cW5ls8IVJl');
} catch (NotFoundException $exception) {
    // webhook not found
}
```
