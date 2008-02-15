<?php
/**
 * @package TrevorCore
 */

/**
 * Dispatcher handles inbound HTTP requests and decides what action to invoke, and whether a template should be rendered.
 * The Dispatcher is the main entry point into the application, and routes requests to all other parts of the app.
 * Example: 
 * <code>
 * <?php 
 * require_once 'core/common.php';
 * $dispatch = new Dispatcher();
 * $dispatch->dispatch();
 * ?>
 * </code>
 * @package TrevorCore
 */
class Dispatcher
{
    /**
     * An array of all active modules, in the 
     order that they should be evaluated.
     * 
     * @var array(BlendModule)
     * @access protected
     */

    protected $urlConfigs = array();
    protected $url;

    function __construct()
    {
        session_start();
        //Retrieve a list of active modules
        //and load them
    }

    /**
     * The dispatch() method processes the initial request. 
     * This is the entry point of the framework.
     */
    function dispatch()
    {
        $parameters = array();

        //Populate the parameters list
        //$this->parseUrl($parameters);

        $controller = null;

        //Determine which controller should handle the request.
        $router = new BlendRouter;
        $route = $router->parse($_SERVER['REQUEST_URI']);

        if (!$route)
        {
            echo "File not found.";
            return;
        }
        
        $parameters = array_merge( $_GET, $_POST, $route);

        $controller=$this->loadController($parameters);

        if ($controller)
        {
            $controller->invoke($parameters);

            switch($controller->result_code)
            {
                case BC_REDIRECT:
                    $this->redirect($controller);
                break;
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

    private function loadController(&$parameters)
    {
        $controllerName=ucfirst($parameters['controller']) . 'Controller';
        $path = SITE_ROOT . '/app/controllers/' . $controllerName . '.php';

        require_once($path);
        //$controllerClassName = ucfirst($controller) . 'Controller';
        $controller = new $controllerName();
        return $controller;
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
      
      if(!$urlConfig)
      {
        $urlConfig = $this->urlConfigs['notfound'];
        $url=new ezcURL('/notfound/notfound/notfound', $urlConfigRoot);
      }
        $url->applyConfiguration($urlConfig);
       // var_dump($url->params);
        
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
        header('Status: ' . $controller->status_code);
        $debug = ezcDebug::getInstance();
        $tplConfig = new ezcTemplateConfiguration( SITE_ROOT . "/app",
                                                   SITE_ROOT . "/tmp" );
		$tplConfig->addExtension( "CustomDate" );
		$tplConfig->addExtension( "CustomMath" );
		$tplConfig->addExtension( "MoneyFormat" );
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
            
        $path = 'layouts/' . $controller->layout . '.ezt';
        echo $layoutTpl->process($path);
        
        //$output = $debug->generateOutput();
        //echo $output;      
    }
}

?>