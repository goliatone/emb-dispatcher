<?php defined('SYSPATH') or die('No direct script access.');
/**
 * The <code>Event</code> class is used as the base class for the creation of <code>Event</code> objects, which are passed as parameters to event handlers when an event is triggered.
 * The properties of the event carry basic information about an event.
 * An <code>Event</code> can carry information and state around the application. We can bind- _&referenced_- variables so that they can be modified through the event's live cycle. 
 *
 *
 * @package    	Dispatcher
 * @author 		Emiliano Burgos <hello@goliatone.com>
 * @copyright  	(c) 20011 Emiliano Burgos
 * @license    	http://kohanaphp.com/license
 */
 class Event extends Core_Event
{
	/**
	 * Convenience method to trigger this event.
	 * We can set if the event propagation can be stopped. 
	 * If no dispatcher is provided will use global dispatcher [Dispatcher::instance()](api/Dispatcher#instance)
	 * 
	 * @param boolean $allow_stop_propagation Arguments for this event
	 * @param Dispatcher $dispatcher Arguments for this event
	 */
	public function dispatch($allow_stop_propagation = FALSE, $dispatcher = NULL)
	{
		$dispatcher = $dispatcher == NULL ? Dispatcher::instance() : $dispatcher; 
		$dispatcher->dispatch_event($this,$allow_stop_propagation);
	}
}