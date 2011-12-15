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

###Examples
Refer to the [examples](examples) page for more use cases.