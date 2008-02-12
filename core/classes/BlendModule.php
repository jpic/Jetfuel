<?php
/**
 * @package TrevorCore
 */
/** 
 * The BlendModule class encapsulates an extensible portion of the system. 
 * It serves as a facade that outside components, like the 
 * dispatcher, use to access the functionality of the module
 * @package TrevorCore
 */ 
class BlendModule
{

    /**
     * Name of the module. Should be a single-word, lowercase string
     *
     * @var string
     */ 
    public $identifier = null;
    public $urlConfig = null;
    
    function __construct()
    {
      $urlConfig=new ezcUrlConfiguration();
      $urlConfig->addOrderedParameter('module');
      $urlConfig->addOrderedParameter('controller');
      $urlConfig->addOrderedParameter('action');
      $urlConfig->addOrderedParameter('id');
      $this->urlConfig = $urlConfig;
    }

    /** 
     * The retrieveController function evaluates the 
     * input parameters and selects a controller to handle
     * the request. If the request is not handled by the 
     * module, it will return false.
     *
     * The default implementation selects a controller based on a url structure
     * like '/module/controller/action/etc'
     * 
     * If controller isn't specified, it will look for a controller with the same 
     * name as the module.
     * 
     * @return mixed BlendController if the request is to be handled, false otherwise 
     * @access public
     */
    public function retrieveController(&$parameters)
    {
        $url=$parameters['url'];
        //list($root,$module,$controller,$action)=explode('/',$url);
        $module = $url->getParam('module');
        $controller = $url->getParam('controller');
       // print_r(explode('/',$url));
       //     echo $path . ":$url:";   
        
        //this is what should be setting the controller to the same name as the modules (this wasn't in it originally) 
        //-- added 1/17/08
        $controller=($controller ? $controller : $module);

        if($module==$this->identifier)
        {

            $path = 'app/modules/' . $module . '/' . ucfirst($controller) . 'Controller.php';
            
            require_once($path);
            $controllerName = ucfirst($controller) . 'Controller';
            $controller = new $controllerName();
            return $controller;
        }
        else
        {
            return false;
        }
    }
    
    
}

?>