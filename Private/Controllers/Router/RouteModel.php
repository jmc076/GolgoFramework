<?php
namespace Controllers\Router;

class RouteModel
{
    /**
     * URL of this Route
     * @var string
     */
    private $url;


    /**
     * The name of this route, used to get url from route name
     * @var string
     */
    private $name;


    
    /**
     * The method of the class that should be called
     * @var string
     */
    private $targetClassMethod;
    
    /**
     * The class that should be instanciated
     * @var string
     */
    private $targetClass;

    
    /**
     * If CSRF check is needed (Only with POST method i guess)
     * @var boolean
     */
    private $checkCSRF;
    
    
    /**
     * Accepted HTTP verb, empty array for all.
     * @var array() $verbs
     */
    private $verbs;

    /**
     * @param       $resource
     * @param array() $config ("name","parametersToMethod","targetClassMethod","targetClass","verbs","checkCSRF")
     */
    public function __construct($url, array $config)
    {
        $this->url     = $url;
        $this->name    = isset($config['name']) ? $config['name'] : null;
        $this->targetClassMethod = isset($config['targetClassMethod']) ? $config['targetClassMethod'] : null;
        $this->targetClass = isset($config['targetClass']) ? $config['targetClass'] : null;
        $this->verbs = isset($config['verbs']) ? (array) $config['verbs'] : array();
        $this->checkCSRF = isset($config['checkCSRF']) ? $config['checkCSRF'] : true;
    }
    
    public function updateConfig($newConfig) {
    	$this->name    = isset($newConfig['name']) ? $newConfig['name'] : null;
    	$this->targetClassMethod = isset($newConfig['targetClassMethod']) ? $newConfig['targetClassMethod'] : null;
    	$this->targetClass = isset($newConfig['targetClass']) ? $newConfig['targetClass'] : null;
    	$this->verbs = isset($newConfig['verbs']) ? (array) $newConfig['verbs'] : array();
    	$this->checkCSRF = isset($newConfig['checkCSRF']) ? $newConfig['checkCSRF'] : true;
    }
    

    public function getUrl() {
        return $this->url;
    }
    
    public function setUrl($url) {
        $url = (string)$url;
        if (substr($url, -1) !== '/') {
            $url .= '/';
        }
        $this->url = $url;
    }
    

    public function getVerbs() {
        return $this->verbs;
    }
    public function setVerbs(array $methods) {
        $this->verbs = $methods;
    }
    

    public function getName() {
        return $this->name;
    }
    public function setName($name)  {
        $this->name = (string)$name;
    }
    
    
    public function getTargetClassMethod() {
    	return $this->targetClassMethod;
    }
    public function setTargetClassMethod($targetClassMethod) {
    	$this->targetClassMethod = $targetClassMethod;
    }
    
    
    public function getTargetClass() {
    	return $this->targetClass;
    }
    public function setTargetClass($targetClass) {
    	$this->targetClass = $targetClass;
    }
    
    
    public function getRegex()  {
        return preg_replace('/(:\w+)/', '([\w-%]+)', $this->url);
    }
    
    
	public function getCheckCSRF() {
		return $this->checkCSRF;
	}
	public function setCheckCSRF($checkCSRF) {
		$this->checkCSRF = $checkCSRF;
	}
	
	

}
