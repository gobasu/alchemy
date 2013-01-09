
Event system
============

`alchemy\app\Application`, `alchemy\app\Controller`, `alchemy\storage\db\Model` extends `alchemy\event\EventDispatcher`.
The `alchemy\event\EventDispatcher` is a implementation of observer pattern. It allows you to take actions when event
is dispatched by given object as well as defining your own events. To get familiar with the framework's event system
I recommand you to visit [event package](http://github.com/dkraczkowski/alchemy/tree/master/alchemy/event)

Dispatching a custom event
--------------------------

To define a event class you must extend `alchemy\event\Event` class and give it a meaningfull name. Let's assume
we want to dispatch an event meaning that item was added to cart, e.g.
```php
<?php
namespace app\event;
use alchemy\event\Event;
class OnAddToCart extends Event {}
```

Now lets build our Cart model class:
```php
<?php
namespace app\model;
use alchemy\storage\db\Model;
use alchemy\event\Event;
/**
 * Very simple cart class implementation
 */
class Cart extends Model
{
    public function __construct($sessionId = null)
    {
        parent::__construct($sessionId);
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

    public function updateCart($itemId, $count)
    {
        $this->cartData[$itemId]['count'] = $count;
    }
    /**
     * Called when object is get from db
     */
    public function onGet()
    {
        $this->cartData = json_decode($this->cartData, true);
    }
    /**
     * Called when framework is trying to save object to DB
     */
    public function onSave()
    {
        $this->cartData = json_encode($this->cartData);
    }
    protectes $lastInsertedItem;
    protected $sessionKey;
    protected $cartData;
}
```
As you can see to dispatch ane event you need to use `$this->dispatch` method which accepts one parameter of `alchemy\event\Event` instance.
I have also used in the example some builded in model's methods to encode/decode cart data to json when it is needed.
We will revisit framework events later on.

The `dispatch` function does two things:
- dispatches an event in object scope
- pass an event to global event hub (this is helpfull for plugins)

Attaching listeners
-------------------

You can attach listeners strict to an object or to the `alchemy\event\EventHub`.

Attaching listener to an object:
```php
$cart = Cart::get($sessionId);
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

`alchemy\event\EventHub` is global event center- here goes all events dispatched by your controllers/models, so you can
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
