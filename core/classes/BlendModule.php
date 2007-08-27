<?php

/** 
 * The BlendModule class encapsulates an extensible portion of the 
 * system. It serves as a façade that outside components, like the 
 * dispatcher, use to access the functionality of the module
 */ 
class BlendModule
{

    /**
     * Name of the module. Should be a single-word, lowercase string
     *
     * @var identifier
     */ 
    public $identifier = null;

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
        list($root,$module,$controller,$action)=explode('/',$url);
       // print_r(explode('/',$url));
       //     echo $path . ":$url:";        
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