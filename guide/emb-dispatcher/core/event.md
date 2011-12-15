##Event

The Event class is used as the base class for the creation of Event objects, which are passed as parameters to event handlers when an event is triggered.

The properties of the event carry basic information about an event.

An Event can carry information and state around the application. We can bind- _&referenced_- variables so that they can be modified through the event's live cycle.

If the dispatcher enables so, we can also stop the event propagation from an event handler [listener](core/listeners) through the event's `$stop_propagation` property.

    //We need to specifically allow stop propagation.
    $allow_stop_propagation = TRUE;
    $dispatcher->dispatch_event($event, $allow_stop_propagation);
    static public function event_handler(Event $e)
    {
        //...modify event's payload.
        $e->stop_propagation = TRUE;
    }
Dispatcher has a conservative approach an sets `$allow_stop_propagation` to `FALSE` by default.


###Payload

With our event, we can send out a payload of information. There are two different methods `set` and `bind`, similar to those in Kohana's `View` class.

Whilst `bind` will assign a value by reference, `set` will do it by name. Later, on the handler method, they can be accessed as event properties.

Any variables added through the Event's constructor method, will be `set`.

    $event = new Event('event_type',array("foo" => "foo value","bar" => "bar value"));
    $event->bind($fuz,"fuz value");
    
    static public function event_handler(Event $e)
    {
        echo $e->foo; // "foo value"
        echo $e->bar; // "bar value"
        echo $e->fuz; // "fuz value"
    }

### Dispatching from Event
There is a convenience method `dispatch` to trigger events from an Event instance. If we are going to use a private dispatcher
we should pass it to the method.

    $event = new Event('event_type',$arguments);
    //if $dispatcher is null, we use global channel.
    $event->dispatch($allow_stop_propagation,$dispatcher);
    


    
 