<?php
/**
 * @package TrevorCore
 */

/**
 * BC_RENDER_VIEW is defined as the 'render' process in the Dispatcher. This tells the Dispatcher to 
 render templates for this action. (this is the default thing to do)
 */
define('BC_RENDER_VIEW',1);
/**
 * BC_REDIRECT tells the Dispatcher to redirect the request instead of rendering a template.
 */
define('BC_REDIRECT',2);


/**
 * BlendController forms the basis for all controllers on the system
 * @package TrevorCore
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
    
    /**
     * Allows you to redirect to another URL from a controller action.
     * Example: 
     * <code>
     * $this->redirect('http://www.cnn.com');
     * return;
     * </code>
     * @param string $url The URL to redirect to
     * @param int $status_code The HTTP status code to use on the redirect. 301=permanently moved, 302=temporarily moved (this is the default)
     */
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

        if (!$this->controller)
        {
            $this->controller = $parameters['controller'];
        }
        $action = $this->action = $parameters['action'];
        if (!$action)
        {
            $action = $this->action = 'index';
        }
        
        $parameters = array_merge($_GET, $_POST, $parameters);
        
        //echo "<pre>"; print_r($parameters); echo "</pre>";

        $this->templateFile = 'views/' . $this->controller . '/' . $this->action . '.ezt';
        $this->result_code = BC_RENDER_VIEW;
        return $this->$action($parameters);
    }
    
}

?>