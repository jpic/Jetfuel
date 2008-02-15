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
 * 
 * The BlendController forms the 'Controller' portion of the Model/View/Controller pattern. 
 * It's responsible for taking input from the user, performing some action on the Model, and 
 * returning that information to the View to be displayed back to the user. In short, the controllers 
 * are where the actual workings of the app are orchestrated.
 *
 * The {@link Dispatcher} looks for subclasses of BlendController in the app/controllers folder. 
 *
 * As an example, if a user requests the url '/person/index', that might be handled by the 'index' action on 
 * the PersonController class in app/controllers/PersonController.php
 *
 * Usually, an app's controllers are subclasses of {@link ApplicationController}, which is itself a subclass of 
 * BlendController. That way, functions common to all controllers within your app can be placed on ApplicationController.
 *
 * Actions are just defined as methods on the Controller class, so the PersonController in our example might look like this:
 * <code>
 * <?php
 * class PersonController extends ApplicationController
 * {
 *     function index()
 *     {
 *         //list all Person objects on the page
 *         $people = Person::findAll('class'=>'Person');
 *         $this->vars['people']=$people;
 *     }
 * }
 * ?>
 * </code>
 * @package TrevorCore
 */
class BlendController
{

    /**
     * An hash array containing variables to be used in the view.
     *
     * When implementing an action in a controller, you use the 'vars' variable to provide variables to 
     * display in the view.
     * 
     * For example, in your controller, you can set an action like so:
     * <code>
     * class PersonController
     * {
     *    function show()
     *   {
     *        
     *       //...code to select a Person Object
     *       
     *       $this->vars['person']=$person;
     *       $this->vars['name']=$person->name;
     *       $this->vars['woot']="W00T!!!";
     *   }
     * }
     * </code>
     * $person and $name would then be available for use in the template: 
     * <code>
     * {use $person, $name, $woot}
     * <h1>{$name}</h1>
     * <address>{$person->address}</address>
     *      {$woot}
     * </code>
     * @var array(string=>mixed)
     */     
    public $vars=array();

    /** 
     * $result_code determines how the controller will handle output
     * There are currently two options: 
     * BC_RENDER_VIEW will render a view using the template system.
     * BC_REDIRECT will skip view rendering and redirect to a different URL.
     * Use the {@link redirect()} function instead of setting this directly.
     * @see redirect()
     * @access public
     */
    public $result_code = BC_RENDER_VIEW;
    
    /**
     * $redirect_url determines where the browser should be redirected to during a redirect
     * Use the {@link redirect()} function instead of setting this directly.
     * @param string
     * @see redirect()
     * @access public
     */
    public $redirect_url = null; 
    
    /**
     * $redirect_status_code contains the status code to be used during a redirect.
     * Valid codes in HTTP are 301(moved permanently) and 302(moved temporarily, the default).
     * 
     * Use the {@link redirect()} function instead of setting this directly.
     * @param int
     * @see redirect()
     * @access public
     */
    public $redirect_status_code;
    
    public $controller = null;
    public $action = null;
    
    /**
     * $layout determines which layout template is to be used. 
     * By default, the 'default.ezt' layout will be used in the view. 
     * However, controllers or individual actions can override this to 
     * provide a different look and feel for different templates.
     * 
     * <code>
     *  function someAction()
     *  {
     *     $this->vars['foo']='bar';
     *     $this->layout='plain'; // This will use layouts/plain.ezt instead of layouts/default.ezt
     *  }
     *  </code>
     * You can also override the layout for the entire controller:
     * <code>
     *  class PersonController
     *  {
     *    public $layout='person';
     *    
     *    //Controller actions here.
     *  }
     *  </code>
     * @param string
     * @access public
     */
    public $layout = 'default';
    
    /**
     * HTTP Status code that the action call should return.
     * $status_code defines the HTTP Status that will be returned as a result of the request. 
     * Controller actions can decide to override the default 200 (HTTP's OK code) (to return a 404 if 
     * a record isn't found in the database, for example).
     * @param int
     * @access public
     */
    public $status_code = 200;

    function __construct()
    {
        $this->vars=array();
        
        //Instantiate the components and store them in an array.
        /* //Commenting out components for eventual removal
        foreach($this->components as $component)
        {
            $componentClass = $component . 'Component';
            $componentObj = new $componentClass($this);
            $this->componentObjects[$component]=$componentObj;
        }
        */
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