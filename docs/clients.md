# Clients

## API Clients

* [Fields](/docs/mailing-lists.md)
* [Mailing Lists](/docs/mailing-lists.md)
* [Subscribers](/docs/mailing-lists.md)
* [Webhooks](/docs/mailing-lists.md)

## Returning void

The clients do not return the data on create, update or delete actions, instead in most cases they update the object 
send to the server. To mimic returning the server response the handled object can be returned.

```php
use DMT\Laposta\Api\Clients\Subscribers;
use DMT\Laposta\Api\Entity\Subscriber;

class CustomerMailingService
{
    protected Subscribers $client;
  
    /**
     * Subscriber a customer to the 'Customer mailing list' 
     * 
     * @param \DMT\Laposta\Api\Entity\Subscriber $subscriber
     *
     * @return string The added subscriber
     */
    public function createSubscriber(Subscriber $subscriber): Subscriber
    {
        $this->client->insert($subscriber);
        
        return $subscriber;
    }
}
```

## Error Handling

Entities are (only) fully validated on creation. Updating an existing entity will not require all the properties to 
be present, just the ones that are needed to identify it. In case an entity sent to the server (create/update) contains 
errors, it will trigger a validation exception. If for some reason (like an out-dated custom fields implementation) the 
validation did is not triggered but the data is wrong the server will return an error on the invalid 
fields.

See [Error Handling](/docs/error-handling.md) documentation to get more in depth information about how to handle 
exceptions in the clients.