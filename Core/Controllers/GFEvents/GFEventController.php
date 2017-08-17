<?php
/**
 *
 * @author Diego Lopez Rivera (forgin50@gmail.com)
 *
 */
namespace Core\Controllers\GFEvents;


class GFEventController implements GFEventControllerInterface, EventManagerInterface {

	private static $listeners = array();
	private static $psrEvents = array();


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
	public static function dispatch($event, array $params = null) {
		if(isset(self::$listeners[$event]) && GF_EVENTS_ENABLED) {
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
	 * @see \Controllers\Events\EventControllerInterface::getEvents()
	 */
	public static function getPsrEvents() {
		return array_keys(self::$psrEvents);

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


	/**
	 * {@inheritDoc}
	 * @see \Core\Controllers\GFEvents\EventManagerInterface::attach()
	 */
	public function attach($event, $callback, $priority = 0) {
		if(isset(self::$psrEvents[$event->getName()])) {
			self::$psrEvents[$event->getName()][$priority][] = $callback;
			return true;
		} else {
			self::$psrEvents[$event->getName()][$priority][] = $callback;
			return true;
		}
		return false;


	}

	/**
	 * {@inheritDoc}
	 * @see \Core\Controllers\GFEvents\EventManagerInterface::detach()
	 */
	public function detach($event, $callback) {
		if(isset(self::$psrEvents[$event->getName()])) {

			foreach (self::$psrEvents[$event->getName()] as $priorities) {
				foreach ($priorities as $key=>$listener) {
					if($listener == $callback){
						unset(self::$psrEvents[$event->getName()][$priorities][$key]);
					}
				}
			}

			return true;
		}
		return false;

	}

	/**
	 * {@inheritDoc}
	 * @see \Core\Controllers\GFEvents\EventManagerInterface::clearListeners()
	 */
	public function clearListeners($event) {
		if(isset(self::$psrEvents[$event->getName()])) {
			self::$psrEvents[$event->getName()] = array();
		}
	}

	/**
	 * {@inheritDoc}
	 * @see \Core\Controllers\GFEvents\EventManagerInterface::trigger()
	 */
	public function trigger($event, $target = null, $argv = []) {
		$max = max(array_keys(self::$psrEvents[$event->getName()]));
		$lastResult = null;

		for ($i = $max; $i == 0; $i--) {
			if(isset(self::$psrEvents[$event->getName()][$i])) {
				foreach(self::$psrEvents[$event->getName()][$i] as $key=>$callbacks) {
					$lastResult = call_user_func_array($callbacks, array(&$event,  $argv, $lastResult));
					if($event->isPropagationStopped()){
						break 2;
					}
				}

			}
		}


	}

}
