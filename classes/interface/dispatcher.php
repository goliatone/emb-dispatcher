<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package    	Dispatcher
 * @category	Interface
 * @author 		Emiliano Burgos <hello@goliatone.com>
 * @copyright  	(c) 20011 Emiliano Burgos
 * @license    	http://kohanaphp.com/license
 */
Interface Interface_Dispatcher
{

	/**
	 * Registers a listener
	 *
	 * @param string 	$event     	Event to listen for
	 * @param mixed	    $callback  	Callback to trigger
	 * @param priority  $priority  	Listener priority in the queue.
	 * 
	 * @throws	Kohana_Exception	If not valid callback provided.
	 */
	public function add_listener($event, $callback, $priority = 0);

	/**
	 * Triggers an event
	 *
	 * @param Dispatcher_Event $event            Event object
	 */
	public function dispatch_event(Event $event);
	
	/**
	 * Will return TRUE if there is any registered listener for a given
	 * event->type.
	 */
	public function will_trigger($event_name);
}