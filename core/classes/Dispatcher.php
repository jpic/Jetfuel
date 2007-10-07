<?php

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
    protected $urlConfigs = array();
    protected $url;

    function __construct()
    {
        global $active_modules;
        session_start();
        //Retrieve a list of active modules
        //and load them
        foreach ($active_modules as $module)
        {
         //echo "<hr>$module<hr>";
            require_once('app/modules/' . strtolower($module) . '/' . $module . 'Module.php');
            $moduleClass=$module . 'Module';
            $moduleObj = new $moduleClass();
            $this->urlConfigs[$moduleObj->identifier]=$moduleObj->urlConfig;
            $this->modules[]=$moduleObj;
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
      $urlConfigRoot = new ezcUrlConfiguration();
      $urlConfigRoot->script='index.php';
      $urlConfigRoot->addOrderedParameter('module');
      
      $url=new ezcUrl($_SERVER['REQUEST_URI'], $urlConfigRoot);

      $moduleIdentifier = $url->getParam('module');
      
      $urlConfig = $this->urlConfigs[$moduleIdentifier];
      $url->applyConfiguration($urlConfig);
      //var_dump($url->params);
      
      $parameters = array_merge($url->getQuery(), $_POST);
      $parameters['url'] = $url;
      $parameters['method'] = strtolower($_SERVER['REQUEST_METHOD']);  
      $parameters['client_ip'] = $_SERVER['REMOTE_ADDR'];
      $parameters['client_port'] = $_SERVER['REMOTE_PORT'];
       //            var_dump($url->path);

    }
    
    protected function redirect($controller)
    {
        header('Location: ' . $controller->redirect_url);
        
    }
    
    protected function renderView($controller)
    {
        $debug = ezcDebug::getInstance();
        $tplConfig = new ezcTemplateConfiguration( "app",
                                                    "tmp" );
		$tplConfig->addExtension( "CustomDate" );
		$tplConfig->addExtension( "CustomMath" );
		$tplConfig->addExtension( "UrlCreator" );
        $tplConfig->disableCache=true;
        $tplConfig->checkModifiedTemplates=true;
        $tpl = new ezcTemplate();
        $tpl->configuration = $tplConfig;
        $send = new ezcTemplateVariableCollection($controller->vars);
        $tpl->send = $send;
        $receive = $tpl->receive;
        
        //var_dump($controller->vars);
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