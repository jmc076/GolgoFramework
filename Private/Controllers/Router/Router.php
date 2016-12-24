<?php
namespace Controllers\Router;

use Controllers\Http\Request;
use Controllers\Http\Response;
use Controllers\Events\EventController;
use Controllers\Router\Interfaces\RouterInterface;

class Router implements RouterInterface {

	private $routes = array();
    private $namedRoutes = null;
    private $basePath = BASE_PATH;
    private $request;
    private $requestUrl;
    
    
    public function __construct(RouteCollection $routes, Request $request) {
        $this->routes = $routes;
        $this->request = $request;
    }


    public function parseRequest() {
    	$this->requestUrl = $this->request->getRequestUrl();
        if (($pos = strpos($this->requestUrl, '?')) !== false) {
           $this->requestUrl = substr($this->requestUrl, 0, $pos);
        }
        return $this->match($this->requestUrl);
    }
    

    public function match($requestUrl) {
    	
    	$allRoutes = $this->routes->getAllRoutes();
    	
        foreach ($allRoutes as $route) {
            if(count($route->getVerbs()) == 0 || in_array($this->request->getVerb(), $route->getVerbs())) {
	            $stringRoute = rtrim($route->getRegex(), '/');
	            if($stringRoute != "" && $stringRoute != "/" && strpos($stringRoute ,"/") !== 0) {
	            	$stringRoute = '/'.$stringRoute;
	            }
	            
	            $pattern = "@^{$this->basePath}{$stringRoute}/?$@i";
	            $matches = array();
	            
	            if (!preg_match($pattern, $requestUrl, $matches)) {
	                continue;
	            }
	            array_shift($matches);
	
	            if (preg_match_all("/:([\w-%]+)/", $route->getUrl(), $argument_keys)) {
	                $argument_keys = $argument_keys[1];
	                if(count($argument_keys) != count($matches)) {
	                    continue;
	                }
	
	               $this->request->parseUrlParams($argument_keys, $matches);
	
	            }
	            
	            $this->request->setNeedCheckCSRF($route->getCheckCSRF());
	            $this->request->setHasMatch(true);
	            $this->request->setMatchedRoute($route);
				
	            EventController::dispatch("Router.Matched", array("request" => $this->request, "route" => $route));
	            
	            return true;
       		}
        }
       $this->request->setHasMatch(false);
       EventController::dispatch("Router.NotMatched", array("request" => $this->request));
       
       return false;
    }
    
    

    public function generateRoute($routeName, array $params = array())  {
        
    	if($this->namedRoutes == null) {
    		$this->namedRoutes = array();
	    	foreach ($this->routes->getAllRoutes() as $route) {
	    		$name = $route->getName();
	    		if (null !== $name) {
	    			$this->namedRoutes[$name] = $route;
	    		}
	    	}
    	}
    	
        if (!isset($this->namedRoutes[$routeName])) {
           return false;
        }

        $route = $this->namedRoutes[$routeName];
        $url = $route->getUrl();
        $param_keys = array();
        if ($params && preg_match_all("/:(\w+)/", $url, $param_keys)) {
            $param_keys = $param_keys[1];
            foreach ($param_keys as $key) {
                if (isset($params[$key])) {
                    $url = preg_replace("/:(\w+)/", $params[$key], $url, 1);
                }
            }
        }

        return $url;
    }
    

}
