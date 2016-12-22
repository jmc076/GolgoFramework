<?php

/**
 * Event Controller Interface
 *
 * @author Diego Lopez Rivera (forgin50@gmail.com)
 *
 */
namespace Controllers\Router\Interfaces;

use Controllers\Router\RouteCollection;
use Controllers\Http\Request;

interface RouterInterface {
    
    
    public function __construct(RouteCollection $routes, Request $request);

    /**
     * Start to parse the routing process.
     *
     * @return boolean (true if match)
     */
	public function parseRequest();
    
	/**
	 * Find the matched route and fill the Request object passed in consctructor
	 *
	 * @param string $url, the request url
	 * @return boolean (true if match)
	 */
    public function match($requestUrl);
    
    /**
     * Builds the URL that matches the Route with the given name.
     *
     * @param string $routeName
     * @param array $params
     * @return string (the url)
     */
    public function generateRoute($routeName, array $params = array());
    

    

}
