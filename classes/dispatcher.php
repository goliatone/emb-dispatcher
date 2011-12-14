<?php defined('SYSPATH') or die('No direct script access.');
/**
 * The <code>Dispatcher</code> class extends <code>[Core_Dispatcher](api/Core_Dispatcher)</code> 
 *
 * You can create individual instances of the dispatcher for private event
 * handling or you can use a global channed, Dispatcher::isntance()
 *
 *
 * @package    	Dispatcher
 * @author 		Emiliano Burgos <hello@goliatone.com>
 * @copyright  	(c) 20011 Emiliano Burgos
 * @license    	http://kohanaphp.com/license
 */
class Dispatcher extends Core_Dispatcher
{
	/**
	 * Gets the global dispatcher instance
	 * 
	 * @return Dispatcher
	 */
	static function instance()
	{
		static $instance = NULL;

		if($instance === NULL)
		{
			$instance = new Dispatcher();
		}

		return $instance;
	}
	
	/**
	 * Returns a factory instance of Dispatcher
	 * 
	 * @return Dispatcher
	 */
	static function factory()
	{
		return new Dispatcher();
	}
}