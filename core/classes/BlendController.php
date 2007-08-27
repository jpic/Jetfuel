<?php

define('BC_RENDER_VIEW',1);
define('BC_REDIRECT',2);


/**
 * BlendController forms the basis for all controllers on the system
 */
class BlendController
{

    /**
     * container for actions in subclasses to input their 
     * variables to be delivered to the view
     * 
     * @var array
     * @access protected
     */     
    public $vars=array();
    
    public $result_code = BC_RENDER_VIEW;
    public $redirect_url = null; 
    public $redirect_status_code;
    public $module = null;
    public $controller = null;
    public $action = null;
    public $layout = 'default';

    function __construct()
    {
        $this->vars=array();
        
    }
    
    function redirect($url, $status_code = 302)
    {
        $this->result_code = BC_REDIRECT;
        $this->redirect_url = $url;
        $this->redirect_status_code = $status_code;
    }

    /**
     * invoke determines which action to invoke on the template
     * and invokes it. Controller provides a default implementation
     * but subclasses may override it if neccesary
     */    
    function invoke($parameters)
    {
        $this->parameters=$parameters;
        
        $url=$parameters['url'];
        list($empty, $module,$controller,$action)=explode('/',$url);
        $this->module = $module;
        $this->controller = $controller;
        $this->action = $action;
        $this->templateFile = 'modules/' . $module . '/templates/' . $controller . '/' . $action . '.ezt';
        $this->result_code = BC_RENDER_VIEW;
        return $this->$action($parameters);
    }
    
}

?>