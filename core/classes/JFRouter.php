<?php
/**
 * JFRouter.php
 * @package JetFuelCore
 */
 
/**
 * The JFRouter class is responsible for routing inbound URL's to actions on the 
 * controllers. 
 * @package JetFuelCore
 */
class JFRouter
{
    /**
     * The $rules var builds a list of the routing rules
     * @var array(string=>array)
     */
    public $rules = array();
    
    function __construct()
    {
        $this->addRulesFromConfig();
    }


    /**
     * add allows you to add new routing rules to the routing engine
     * This function adds a new rule to the list.
     * Params: 
     * @param string $urlPattern
     * a pattern used to define the rule
     * @param array $defaults
     * a hash containing default values to assign for elements not provided in the urlPattern
     * @param string $method
     * the method used; valid values are null (no method put in the rule), 'GET', or 'POST'
     */
    public function addRule($urlPattern, $defaults=array(), $method=null)
    {
        if (is_null($method) || empty($method))
        {
            $this->rules[]= array('pattern'=>$urlPattern, 'defaults'=>$defaults, 'tokens'=>explode('/', $urlPattern));
        }
        else
        {
            $this->rules[]= array('pattern'=>$urlPattern, 'defaults'=>$defaults, 'tokens'=>explode('/', $urlPattern), 'method'=>$method);
        }
    }

    public function addResource($resourceName, $options=array())
    {
        $identifier = ':id';
        $groupActions = array();
        $singleActions = array('edit');
        
        $identifier = $options['identifier'] ? $options['identifier'] : $identifier;
        
        $groupActions = $options['groupActions'] ? array_merge($groupActions, $options['groupActions']) : $groupActions;

        $singleActions = $options['singleActions'] ? array_merge($singleActions, $options['singleActions']) : $singleActions;
        
        
        $this->rules[]= array('pattern'=>"/$resourceName", 
            'defaults' => array('controller'=>$resourceName, 'action'=>'index'),
            'tokens' => array('', "$resourceName"),
            'method' => 'GET');

        foreach($groupActions as $groupAction)
        {
            $this->rules[]= array('pattern'=>"/$resourceName/$groupAction", 
                'defaults' => array('controller'=>$resourceName, 'action'=>$groupAction),
                'tokens' => array('', "$resourceName", $groupAction));
        }


        $this->rules[]= array('pattern'=>"/$resourceName/new", 
            'defaults' => array('controller'=>$resourceName, 'action'=>'newobj'),
            'tokens' => array('', "$resourceName", 'new'));

        $this->rules[]= array('pattern'=>"/$resourceName/$identifier/delete", 
            'defaults' => array('controller'=>$resourceName, 'action'=>'delete'),
            'tokens' => array('', "$resourceName", $identifier, 'delete'),
            'method' => 'POST');        


        foreach($singleActions as $singleAction)
        {
            $this->rules[]= array('pattern'=>"/$resourceName/$identifier/$singleAction", 
                'defaults' => array('controller'=>$resourceName, 'action'=>$singleAction),
                'tokens' => array('', "$resourceName", $identifier, $singleAction));        
        }

        $this->rules[]= array('pattern'=>"/$resourceName", 
            'defaults' => array('controller'=>$resourceName, 'action'=>'create'),
            'tokens' => array('', "$resourceName"),
            'method' => 'POST');

        $this->rules[]= array('pattern'=>"/$resourceName/$identifier", 
            'defaults' => array('controller'=>$resourceName, 'action'=>'show'),
            'tokens' => array('', "$resourceName", $identifier),
            'method' => 'GET');

        $this->rules[]= array('pattern'=>"/$resourceName/$identifier", 
            'defaults' => array('controller'=>$resourceName, 'action'=>'update'),
            'tokens' => array('', "$resourceName", $identifier),
            'method' => 'POST');


    }

    private function addRulesFromConfig()
    {
        $route=$this;
        include SITE_ROOT . '/settings/routes.php';
    }

    /**
     * getParams is called by the Dispatcher to determine where an inbound URL should be routed to.
     * It runs through the rules defined in {@link $rules}, stops at the first match, and returns 
     * those parameters to the Dispatcher, which will invoke the proper controller.
     * @param string $url
     * The URL passed in from the user. (As in, this is what it says in their browser's address bar)
     * @returns array
     */
    public function parse($url)
    {
        $route = null;
        
        if(strpos($url,'?'))
        {
            $url = substr($url, 0, strpos($url, '?'));
        }
        

        
        $tokens = explode('/', rtrim($url, " /"));
        
        
//        echo "URL: [$url] <br />";

        if(trim($url)=="/")
        {
//        echo "TRIM";
            $tokens=array('','');
        }
        
//        echo "URL: [$url] <br />";        
        
        
//        echo "<pre>Tokens:"; print_r($tokens); echo "</pre>";

        foreach($this->rules as $rule)
        {
            if($route = $this->matchRule($tokens, $rule))
            {
                break;
            }
        }

//        echo "<pre>Route:"; print_r($route); echo "</pre>";
//        echo "<pre>Rules:"; print_r($this->rules); echo "</pre>";

        return $route;
    }

    private function matchRule($tokens, $rule)
    {
        $method=$_SERVER['REQUEST_METHOD'];

        if (count($tokens) == count($rule['tokens']))
        {
            if ($rule['method'] && $rule['method'] != $method)
            {
                return false;
            }
//            echo "<pre>Tokens:"; print_r($tokens); print_r($rule); echo "</pre>";
            $route = array('controller'=>'', 'action'=>'', 'id'=>'');
            $route = array_merge($route, $rule['defaults']);
            $match=true;
            foreach($rule['tokens'] as $i=>$element)
            {
                if ($element == $tokens[$i])
                {
                    $match=true;
//                    echo "|TEXT MATCH: $element=$tokens[$i]|";
                }
                elseif ($element[0]==':')
                {
                    $match=true;
                    $route[substr($element,1)]=$tokens[$i];
//                    echo "|ROUTE VAR: " . substr($element,1) . "=$tokens[$i]|";
                }
                else
                {
//                    echo "|NO MATCH: ". $element . "=$tokens[$i]|";
                    $match=false;
                    return false;
                }
            }
            return $route;
            
        }
    }
}

?>
