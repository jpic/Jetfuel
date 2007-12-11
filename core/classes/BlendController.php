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
    public $components = array();
    protected $componentObjects = array();
    public $result_code = BC_RENDER_VIEW;
    public $redirect_url = null; 
    public $redirect_status_code;
    public $module = null;
    public $controller = null;
    public $action = null;
    public $layout = 'default';
    public $status_code = 200;

    function __construct()
    {
        $this->vars=array();
        
        //Instantiate the components and store them in an array.
        foreach($this->components as $component)
        {
            $componentClass = $component . 'Component';
            $componentObj = new $componentClass($this);
            $this->componentObjects[$component]=$componentObj;
        }
    }
    
    public function __get($name)
    {
        //See if this is a registered component
        if (isset($this->componentObjects[$name]))
        {
            return $this->componentObjects[$name];
        }
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
        $parameters['id']=$url->getParam('id');
        //list($empty, $module,$controller,$action)=explode('/',$url);
        if (!$this->module)
        {
            $this->module = $url->getParam('module');
        }
        if (!$this->controller)
        {
            $this->controller = $url->getParam('controller');
        }
        $action = $this->action = $url->getParam('action');
        if (!$action)
        {
            $action = $this->action = 'index';
        }
        $this->templateFile = 'modules/' . $this->module . '/templates/' . $this->controller . '/' . $this->action . '.ezt';
        $this->result_code = BC_RENDER_VIEW;
        return $this->$action($parameters);
    }
    
}

?>