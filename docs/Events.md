
Event system
============

The `alchemy\event\EventDispatcher` is a implementation of observer pattern. It allows you to take actions when event
is dispatched by given method as well as defining your own events. To get familiar with the framework's event system
I recommand you to visit [event package](http://github.com/dkraczkowski/alchemy/tree/master/alchemy/event) package

Dispatching a custom event
--------------------------

**Defining Event Class**
First we need to define custom event class.
To define a event class you must extend `alchemy\event\Event`.
```php
<?php
namespace app\event;
use alchemy\event\Event;
class OnAddToCart extends Event {}
```

**Dispatching an event**
`alchemy\event\EventDispatcher::dispatch(Event $event)`

```php
<?php
namespace app\model;
use alchemy\event\EventDispatcher;
use alchemy\event\Event;
/**
 * Very simple cart class implementation
 */
class Cart extends EventDispatcher
{
    public function __construct($sessionId = null)
    {
        $this->cartData = &$_SESSION['mycart'];//dont' get too much attached to this example :P
        if (!is_array($this->cartData)) {
            $this->cartData = array();
        }
    }
    public function addToCart($itemId, $count)
    {
        if (!isset($this->cartData[$itemId])) {
            $this->cartData[$itemId] = array(
                'itemId'    => $itemId,
                'count'     => 0,
            );
        }

        $this->cartData[$itemId]['count'] += $count;
        $this->lastInsertedItem = $itemId;

        //here is a dispatching event method
        $this->dispatch(new \app\event\OnAddToCart($this));
    }

    public function getLastInsertedItemId()
    {
        return $this->lastInsertedItem;
    }

    public function removeFromCart($itemId)
    {
        unset($this->cartData[$itemId]);
    }
    protectes $lastInsertedItem;
    protected $sessionKey;
    protected $cartData;
}
```

Attaching listeners
-------------------
`alchemy\event\EventDispatcher::addListener(string $event, $callable)`

```php
$cart = new Cart();
$cart->addListener('app\event\OnAddToCart', function($event){
  $id = $event->getCallee()->getLastInsertedItemId();
  echo 'Added to cart item with id:' . $id;
});
```

Attaching listener to EventHub:
```php
alchemy\event\EventHub::addListener('app\event\OnAddToCart', function($event){
  $id = $event->getCallee()->getLastInsertedItemId();
  echo 'Added to cart item with id:' . $id;
});
```

`alchemy\event\EventHub` is global event centre- here goes all events dispatched by your controllers/models, so you can
simply extend/pluginize your application just by using this special class.

You should also be aware of dispatching to many events from your controllers/models because it can influent on your application robustness.

About listeners
---------------

Listeners simply are php's callables, e.g.
- `array($object, 'method')`
- `array('Class', 'method')`
- `'functionname'`
- `closures`

In my examples I've used closures as event hanlders, you are not limited only to closures use whatever
is a callable object.
You can also attach multiple different listeners to a desired event.

Framework events
----------------

Here is the list of major framework's events:
- `alchemy\app\event\OnError` dispatched when resource was executed with uncatched exceptions (you can use it to build your own error pages)
- `alchemy\app\event\OnShutdown` dispatched when application is going to finish the execution
