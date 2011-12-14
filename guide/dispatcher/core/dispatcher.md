##Dispatcher

The Dispatcher class is the base class to dispatch events. It provides a global dispatcher availabl through the `Dispacher::instance()` method. 

To get a private channel- _new instance_- we can use `Dispatcher::factory()`, standard style in Kahana.
