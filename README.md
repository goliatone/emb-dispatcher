# Dispatcher

The dispatcher module enables to have an event driven application flow.
It has a simple api and a light weight implementation, yet it has features such as [listener priority](core/listeners#priority) or a mechanism to prevent event propagation.

The usage is straight forward. Enable the module in the bootstrap file and go. There are no config options.  

A simple use case. In your application, prepare a `$body` variable and dispatch an event:

    $event = new Event('render_body', array($this,"handle_render_body");
    $event->bind("body",$body);
    $event->dispatch();
    
Then, in the same context, define a handler method such as:

    public function handle_render_body(Event $e)
    {
        $e->body .= "<p>Added in the event handler</p>;"
    }

---

##Dispatcher

The Dispatcher class is the base class to dispatch events. It provides a global dispatcher available through the `Dispatcher ::instance()` method.

To get a private channel- _new instance_- we can use `Dispatcher::factory()`, standard style in Kohana.

---

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
    


---
##Listeners

The listeners are registered through a [Dispatcher](core/dispatcher) instance for a certain event type,
and will handle an event once it's triggered.
They need to accept one parameter of type [Event](core/event).

    Dispatcher::instance->add_listener($type,$listener, $priority);

Internally, the dispatcher will handle the listener with a `call_user_func` so the provided listener ultimately needs to be converted to a valid `callback`.

The regular syntax to create a valid `callback` would be any of the following:
    
    $type = "event_type";
    $handler = new Event_Handler();
    $dispatcher = Dispatcher::instance();
    
    $dispatcher->add_listener($type,array($handler, "handler_method"));
    
    $dispatcher->add_listener($type,array('Event_Handler', "static_method"));
    
    class Event_Handler
    {
        public function handler_method(Event $e)
        {
            ...
        }
        
        static function static_method(Event $e)
        {
            ...
        }
    }

The method also has some syntactic [sugar](#sugar).


### Priority

The `add_listener` third argument specifies the order in which the callback will be executed.
The default value is 0. We can pass any integer value- _negative values as well_.
The queue will be ordered based on the priority value, negative values being last.

If we don't specify a priority value, the listeners will be called in the order in which were added.

    $dispatcher->add_listener('some_event_type',$handler1);
    $dispatcher->add_listener('some_event_type',$handler2);
    $dispatcher->add_listener('some_event_type',$handler3);
    
    //Order:
    //$handler1::some_event_type($e)
    //$handler2::some_event_type($e)
    //$handler3::some_event_type($e)

    $dispatcher->add_listener('some_event_type',$handler1,-2);
    $dispatcher->add_listener('some_event_type',$handler2);
    $dispatcher->add_listener('some_event_type',$handler3,10);
    
    //Order:
    //$handler3::some_event_type($e)
    //$handler2::some_event_type($e)
    //$handler1::some_event_type($e)


---

### Sugar

If we have a method that matches the event type, we pass an instance directly. This way we get rid of the array.

    $dispatcher->add_listener('some_event_type',$handler);
    $dispatcher->add_listener('static_event_type','Event_Handler');
    

    class Event_Handler
    {
        public function some_event_type(Event $e)
        {
            ...
        }
        
        static public static_event_type(Event $e)
        {
            ...
        }        
    }

We also have a shortcut for static methods by providing a string:

    $dispatcher->add_listener('event_type','Event_Handler::static_handler');

    class Event_Handler
    {
        static public static_handler(Event $e)
        {
            ...
        }        
    }