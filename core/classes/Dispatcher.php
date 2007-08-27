<?php
require_once("core/classes/BlendModule.php");
require_once("core/classes/BlendController.php");

/**
 * Dispatcher handles inbound HTTP requests and decides what 
 * action to invoke, and whether a template should be rendered
 */
class Dispatcher
{
    /**
     * An array of all active modules, in the 
     * order that they should be evaluated.
     * 
     * @var array
     * @access protected
     */
    protected $modules=array();

    function __construct()
    {
        global $active_modules;
        //Retrieve a list of active modules
        //and load them
        foreach ($active_modules as $module)
        {
       // echo "<hr>$module<hr>";
            require_once('app/modules/' . strtolower($module) . '/' . $module . 'Module.php');
            $moduleclass=$module . 'Module';
            $this->modules[]=new $moduleclass();
        }
    }

    /**
     * The dispatch() method processes the initial request. 
     * This is the entry point of the framework.
     */
    function dispatch()
    {
        $parameters = array();

        //Populate the parameters list        
        $this->parseUrl($parameters);
        
        $controller = null;
        
        //Run through the active modules and ask for a controller
        //to handle this request. Use the first one that gives us a 
        //controller.
        foreach ($this->modules as $module)
        {
            $controller = $module->retrieveController($parameters);
            if ($controller)
            {
                break;
            } 
        }
        if ($controller)
        {
            $controller->invoke($parameters);
            
            switch($controller->result_code)
            {
                case BC_REDIRECT:
                    $this->redirect($controller);
                case BC_RENDER_VIEW:
                default:
                    $this->renderView($controller);            
            }
        }
        else
        {
            echo "No controller available";
        }
    }  
    
    /**
     * parseUrl sets up the parameters list by breaking up the URL and providing some 
     * default variables.
     */
    private function parseUrl(&$parameters)
    {
       $parameters = array_merge($_GET, $_POST);
       $parameters['url'] = $_SERVER['PATH_INFO'];
       $parameters['method'] = strtolower($_SERVER['REQUEST_METHOD']);  
       $parameters['client_ip'] = $_SERVER['REMOTE_ADDR'];
       $parameters['client_port'] = $_SERVER['REMOTE_PORT'];
       
    }
    
    protected function redirect($controller)
    {
        header('Location: ' . $controller->redirect_url);
        
    }
    
    protected function renderView($controller)
    {
        $debug = ezcDebug::getInstance();
        $tplConfig = new ezcTemplateConfiguration( "app",
                                                    "/tmp/compilation" );
        $tpl = new ezcTemplate();
        $tpl->configuration = $tplConfig;
        $send = new ezcTemplateVariableCollection($controller->vars);
        $tpl->send = $send;
        $receive = $tpl->receive;
        
        
        $result=$tpl->process($controller->templateFile);
        
        //echo $result;
        
        $merged = array_merge($send->getVariableArray(), $receive->getVariableArray(), array('result'=>$result));
        $layoutTpl = new ezcTemplate();
        $layoutTpl->configuration = $tplConfig;
        $layoutTpl->send = new ezcTemplateVariableCollection($merged);
            
        $path = 'design/templates/layout/' . $controller->layout . '.ezt';
        echo $layoutTpl->process($path);
        
        $output = $debug->generateOutput();
        echo $output;      
    }
}

?>