<?php defined('SYSPATH') or die('No direct script access.');
/**
 * The <code>Dispatcher</code> class is the base class to dispatch events. It provides a global dispatcher availabl through the `Dispacher::instance()` method. 
 *
 * You can also create individual instances of the dispatcher for private event
 * handling
 *
 * Usage:
 *
 * <pre>
 * <code>$dispatcher = Dispatcher::factory();
 * 
 * $event = new Event('render_body', array($this,"handle_render_body");
 * 
 * $event->bind("body",$body);
 * 
 * $dispatcher->dispatch_event($event);//Private channel.
 * 
 * $event->dispatch();//Global channel.
 * </code></pre>
 *
 * @package    	Dispatcher
 * @category	Core
 * @author 		Emiliano Burgos <hello@goliatone.com>
 * @copyright  	(c) 20011 Emiliano Burgos
 * @license    	http://kohanaphp.com/license
 */
abstract class Core_Dispatcher Implements Interface_Dispatcher
{
	/**
	 * Event listeners.
	 *
	 * @var array
	 */
	protected $_listeners = array();
	
	/**
	 * Sets up a listener for the event $type 
	 * 
	 * Listener can be an object instance with a 
	 * method that matches an event's type, i.e:
	 * <pre>
	 * class Listener {
	 * 	
	 *  public function display_menu(Event $e){ echo "Displaying menu!"}
	 * }
	 * $listener = new Listener();
	 * Dispatcher::instance()->add_listener("dislpay_menu",$listener);
	 * Dispatcher::instance()->dispatch_event(new Event("display_menu"));
	 * 
	 * </pre>
	 * Listener can be a valid callback, i.e.:
	 *
	 * <ul>
	 *		<li>'function_name'</li>
	 *		<li>array('class', 'static_method')</li>
	 *		<li>array($instance_of_class, 'instance_method')</li>
	 * </ul>
	 *
	 * Listener must also accept exactly 1 parameter of type Event:
	 *
	 * <dl>
	 *		<dt>$event</dt>
	 *			<dd>The event object</dd>
	 * </dl>
	 * 
	 * @param string $type     		Name of event to listen for
	 * @param mixed  $listener  	Callback for this event
	 * @param priority  $priority  	Listener priority in the queue.
	 * 
	 * @throws	Kohana_Exception	If not valid callback provided.
	 */
	final public function add_listener($type, $listener, $priority = 0)
	{
		if( ! $this->will_trigger($type))
		{
			$this->_listeners[$type] = array();
		}

		if(empty($listener))
		{
			throw new Kohana_Exception('You have to provide a listener for type {$type}. None found.');
		}
		
		$this->_listeners[$type][] = new Core_Listener($listener,$type,$priority);
		
		
		//if($priority !== 0) usort($this->_listeners[$type],'Core_Listener::compare');
		//calling this seems to be a bit faster?
		if($priority !== 0) Core_Listener::sort($this->_listeners[$type]);
	}

	/**
	 * Triggers the specified event and all listeners registerd to the event type will 
	 * be notified.
	 * By passing the second parameter as true we allow the current handler to stop the
	 * propagation of the event and subsequent calls will be lost. 
	 * 
	 * @param  Dispatcher_Event  $event           Event Object
	 * @param  boolean		     $allow_stop_propagation If set to true, handlers can stop propagation by  $event->stop_propagation = TRUE
	 * @return Dispatcher_Event                   The Event object, after modification
	 */
	final public function dispatch_event( Event $event, $allow_stop_propagation = FALSE)
	{
		if($this->will_trigger($event->type))
		{
			foreach($this->_listeners[$event->type] as $listener)
			{
				call_user_func($listener->callback, $event);
				
				if($allow_stop_propagation AND $event->stop_propagation)
				{
					break;
				}
			}
		}
		
		return $event;
	}
	
	/**
	 * 
	 * @return 	boolean	Indicates wether the specified type has any registered listeners.
	 */
	final public function will_trigger($type)
	{
		return isset($this->_listeners[$type]);
	}
}