<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 
 * @package    	Dispatcher
 * @category	Core
 * @author 		Emiliano Burgos <hello@goliatone.com>
 * @copyright  	(c) 20011 Emiliano Burgos
 * @license    	http://kohanaphp.com/license
 */
final class Core_Listener {
	/**
	 * The higher the priority the higher the precedence.
	 * @var int
	 */
	public $priority = 0;

	/**
	 *
	 * @var mixed
	 */
	protected $_callback;
	
	/**
	 * Constructor
	 * 
	 * @param mixed $callback         Event handler.
	 * @param string $type            Event type.
	 * @param int $priority           Listener's priority in the queue.
	 */
	public function __construct( $callback, $type, $priority =0) {
		$this->priority = $priority;
		
		$this->_create_callback($callback,$type);		
	}
	
	/**
	 * @param mixed $callback         Event handler.
	 * @param string $type            Event type.
	 */
	private function _create_callback($callback,$type)
	{
		/*
		 * Check whether we want to autowire the handler as such:
		 */ 
		if(! is_array($callback))
		{
			
			if(is_string($callback))
			{
				if(is_callable($callback)) $callback = $callback;
				else if(is_callable($callback.'::'.$type)) $callback = $callback.'::'.$type;
			} 
			else if(is_callable(array($callback,$type))) 
			{
				$callback = array($callback,$type);
			}
		}
		
		$this->_callback = $callback;
	}

	public function __get($key) {
		if($key === "callback")
			return $this -> _callback;
	}
	
	/**
	 * Method to sort listeners by priority.
	 * The higher the priority the higher the precedence.
	 * @private
	 */
	static public function compare(Core_Listener $a, Core_Listener $b)
	{
		if(  $a->priority ==  $b->priority ){ return 0 ; } 
  		return ($a->priority > $b->priority) ? -1 : 1;
	}
	
	/**
	 * More involved version, should be also more efficient.
	 * 
	 * @see QuickSorter: http://www.algorithmist.com/index.php/Quicksort_non-recursive.php
	 */
	static public function sort(&$array/*,$prop = 'priority'*/) {
		$cur = 1;
		$stack[1]['l'] = 0;
		$stack[1]['r'] = count($array) - 1;

		do {
			$l = $stack[$cur]['l'];
			$r = $stack[$cur]['r'];
			$cur--;

			do {
				$i = $l;
				$j = $r;
				$tmp = $array[(int)(($l + $r) / 2)];

				// partion the array in two parts.
				// left from $tmp are with smaller values,
				// right from $tmp are with bigger ones
				do {
					//we hardcode the prop value, but we could do 
					//while($array[$i]->{$prop} > $tmp->{$prop})
					while($array[$i]->priority > $tmp->priority)
						$i++;

					//while($tmp->{$prop} > $array[$j]->{$prop})
					while($tmp->priority > $array[$j]->priority)
						$j--;

					// swap elements from the two sides
					if($i <= $j) {
						$w = $array[$i];
						$array[$i] = $array[$j];
						$array[$j] = $w;

						$i++;
						$j--;
					}

				} while( $i <= $j );

				if($i < $r) {
					$cur++;
					$stack[$cur]['l'] = $i;
					$stack[$cur]['r'] = $r;
				}
				$r = $j;

			} while( $l < $r );

		} while( $cur != 0 );
	}

}
