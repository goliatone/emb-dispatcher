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