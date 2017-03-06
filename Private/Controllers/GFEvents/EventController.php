<?php
/**
 *
 * @author Diego Lopez Rivera (forgin50@gmail.com)
 *
 */
namespace Controllers\Events;

use Controllers\Events\Interfaces\EventControllerInterface;

class EventController implements EventControllerInterface {

	private static $listeners = array();


	/**
	 * {@inheritDoc}
	 * @see \Controllers\Events\EventControllerInterface::listen()
	 */
	public static function listen($event, callable $callback) {
		self::$listeners[$event][] = $callback;

	}

	/**
	 * {@inheritDoc}
	 * @see \Controllers\Events\EventControllerInterface::dispatch()
	 */
	public static function dispatch($event, $params) {
		if(isset(self::$listeners[$event]) && EVENTS_SYSTEM_ENABLED) {
			$continue = true;
			foreach (self::$listeners[$event] as $listener ) {
				if($continue){
					$continue = call_user_func_array( $listener, array($params));
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
        foreach (self::$listeners[$event] as $function) {
            if ($function === $callBack) {
                unset(self::$listeners[$event]);
                return true;
            }
        }
        return false;

	}

}
