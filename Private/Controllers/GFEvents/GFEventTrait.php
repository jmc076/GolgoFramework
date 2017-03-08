<?php
/**
 *
 * @author Diego Lopez Rivera (forgin50@gmail.com)
 *
 */
namespace Controllers\GFEvents;


trait GFEventTrait {

	private static $listeners = array();


	/**
	 * {@inheritDoc}
	 * @see \Controllers\Events\EventControllerInterface::on()
	 */
	public static function on($event, callable $callback) {
		self::$listeners[$event][] = $callback;

	}

	/**
	 * {@inheritDoc}
	 * @see \Controllers\Events\EventControllerInterface::once()
	 */
	public static function once($event, callable $callback) {


		$wrapper = null;
		$wrapper = function() use ($event, $callback, &$wrapper) {
			self::removeListener($event, $wrapper);
			return call_user_func_array($callback, func_get_args());
		};
		self::on($event, $wrapper);

	}

	/**
	 * {@inheritDoc}
	 * @see \Controllers\Events\EventControllerInterface::dispatch()
	 */
	public static function dispatch($event, array $params) {
		if(isset(self::$listeners[$event]) && EVENTS_SYSTEM_ENABLED) {
			$continue = true;
			foreach (self::$listeners[$event] as $listener ) {
				if($continue){
					$continue = call_user_func_array( $listener, $params);
				}
			}
		}

	}

	/**
	 * {@inheritDoc}
	 * @see \Controllers\Events\EventControllerInterface::getEvents()
	 */
	public static function getEvents() {
		return array_keys(self::$listeners);

	}

	/**
	 * {@inheritDoc}
	 * @see \Controllers\Events\EventControllerInterface::removeEvent()
	 */
	public static function removeEvent($event) {
		if(($key = array_search($event, self::$listeners)) !== false) {
		    unset(self::$listeners[$key]);
		}

	}

	/**
	 * {@inheritDoc}
	 * @see \Controllers\Events\EventControllerInterface::removeListener()
	 */
	public static function removeListener($event, callable $callBack) {
		if (!isset(self::$listeners[$event])) {
            return false;
        }
        $index = array_search($callBack, self::$listeners[$event], true);
        if ($index !== false) {
        	unset(self::$listeners[$event][$index]);
        }
        return true;

	}

}
