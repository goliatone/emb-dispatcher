<?php defined('SYSPATH') or die('No direct script access.');

/**
 * The <code>Event</code> class is used as the base class for the creation of <code>Event</code> objects, which are passed as parameters to event handlers when an event is triggered.
 * The properties of the event carry basic information about an event.
 * An <code>Event</code> can carry information and state around the application. We can bind- _&referenced_- variables so that they can be modified through the event's live cycle. 
 *
 * @package    	Dispatcher
 * @category	Core
 * @author 		Emiliano Burgos <hello@goliatone.com>
 * @copyright  	(c) 20011 Emiliano Burgos
 * @license    	http://kohanaphp.com/license
 */
abstract class Core_Event 
{
	
	/*
	 * If set to TRUE, all subsecuent listeners registered
	 * for the same event type will not execute.
	 * 
	 * @var boolean
	 */ 
	public $stop_propagation = FALSE;
	
	/**
	 * Event arguments.
	 * @var array
	 */
	protected $_arguments = array();
	
	/**
	* The event's payload.
	*
	* @var array
	*/
	protected $_data = array();
	
	/**
	 * The event's type.
	 * @var	string
	 */
	protected $_type;
	
	/**
	 * Constructor
	 *
	 * @param array $arguments Arguments for this event
	 */
	public function __construct($type, array $arguments = array(), $stop_immediate_propagation = FALSE)
	{
		$this->_type = $type;
		$this->stop_propagation = $stop_immediate_propagation;
		
		if(!empty($arguments))
		{
			foreach($arguments as $arg => $val)
			{
				$this->set($arg,$val);
				$this->_arguments[$arg] = $val;
			} 
		}
		
	}

	/**
	 * PHP magic method.
	 *
	 * @param  string $var Name of var to get
	 * @return mixed       Variable's value
	 */
	public function &__get($var)
	{
		if($var === 'type') return $this->_type;
		
		if($var === 'arguments') return $this->_arguments;
		
		if($var[0] === '_')
		{
			throw new Kohana_Exception('Cannot access protected member variable :var', array(':var' => $var));
		}
		
		if(isset($this->$var))
		{
			 return $this->$var;
		}
		else if( isset($this->_data[$var]))
		{
			return $this->_data[$var];
		} 
		else 
		{
			//$var = $var.':<br/>'.Kohana_Debug::dump($this->_data);
			$var = '';
			return $var;	
		}
	}
	
	/**
	 * PHP magic method.
	 * @param  string $key Name of var to set.
	 * @param  mixed $value Value of var to set.
	 * 
	 */
	public function __set($key,$value)
	{
		$this->set($key, $value);
	}
	
	/**
	* Returns the current set of data parameters.
	*
	* @param mixed Optionally you can return just one, with the key.
	* @param mixed The value to return if the key wasn't found.
	* @return mixed
	*/
	public function data($key = NULL, $default = NULL)
	{
		return $key === NULL ? $this->_data : arr::get($this->_data, $key, $default);
	}
	
	/**
	* Binds a variable by reference.
	* See [View's bind](api/View#bind) for a similar idea.
	* @param string The key.
	* @param mixed 	The data.
	* @return Event
	*/
	public function bind($key, & $data)
	{
		$this->_data[$key] =& $data;
		
		return $this;
	}
	
	/**
	* Sets the data variable to a given value.
	*
	* @param string The key.
	* @param mixed 	The value.
	* @return Event
	*/
	public function set($key, $data)
	{
		$this->_data[$key] = $data;
		
		return $this;
	}
	
	/**
	 * PHP magic method, returns a formated string representaion
	 * of the event instance.
	 *
	 * @return  string
	 */
	public function __toString()
	{
		$out = '';
		$spc = '';
		//$dat = array_diff($this->_data, $this->_arguments);
		foreach($this->_data as $key => $value)
		{
			$out .= $spc.$key." => ".$value;
			$spc = ", ";
		} 
		if(! empty($out)) $out = ", data( {$out} )";
		
		return "[Event: type: '".$this->_type."'".htmlspecialchars($out)." ]";
	}
}