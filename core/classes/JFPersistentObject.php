<?php
/**
 * @package JetFuelCore
 */
 
/**
 * JFPersistentObject is a helpful base class for persistent database objects.
 * 
 * JFPersistentObject represents the 'Model' portion of the Model/View/Controller pattern
 * This base class is extended by your application's Model objects, and provides a number of 
 * convenience functions to make it easy to manipulate your data via objects.
 *
 * To use it, you create an object that extends JFPersistenObject, then set variables for 
 * each of the fields in your object (usually, one class represents one table in the database). 
 *
 * <code>
 * <?php
 * class Person extends JFPersistentObject
 * {
 *     public $id=null;
 *     public $name;
 *     public $email;
 *     public $phone;
 *     public $eyeColor;
 * }
 * ?>
 * </code>
 * 
 * This provides a number of convenience methods that you can use in your controller: 
 * <code>
 *    $people=Person::findAll('Person'); //Retrieve all Person records
 *
 *    $bob=Person::load('Person',2); //Retrieve Person record #2
 *
 *    //Create a new Person, tim, and save him to the database.
 *    $tim=Person::createFromArray('Person', array(
 *            'name'=>'Tim', 
 *            'email'=>'tim@example.com', 
 *            'phone'=>'123-123-1234', 
 *            'eyeColor'=>'blue')); 
 *    $tim->save();
 *
 *    
 * </code>
 * 
 * @package JetFuelCore
 * @abstract
 */
class JFPersistentObject
{

    public $errors=array();
    public $isValid=null;
    
    
    /**
     * The relations array provides an easy way to access related tables as an object graph.
     *
     * Example: 
     * <code>
     *    public static $relations = array(
     *       'organization'=>array('class'=>'Organization', 'type'=>'single'),
     *       'calendars'=>array('class'=>'Calendar'));
     * </code>
     */    
    public static $relations = array();
    
    /**
     * The validations array provides for user-friendly validation of the object before its saved
     *
     * Example: 
     * <code>
     *    public static $validations = array(
     *       'name'=>array(
     *           'type'=>'string',
     *           'required'=>true,
     *           'message'=>'Please enter a name.')
     *   );
     * </code>
     */
    public static $validations = array();

    function __construct()
    {
    }
    
    /**
     * createFromArray allows you to create a new database entry by feeding in data from a hash of properties.
     * 
     * <code>
     * $bob = Person::createFromArray('Person', array(
     *          'name'=>'Bob Smith',
     *          'email'=>'bob@example.com',
     *          'phone'=>'555-123-1212',
     *          'eyeColor'=>'green'));
     *
     * $bob->save(); //This adds a new record to the database;
     * </code>
     *
     * A common use is to create a record from a set of HTML form fields.
     *
     * For example, consider this HTML form:
     *
     * <code>
     * <form method="post" action="/person">
     * Name: <input type="text" name="person[name]" />
     * Email: <input type="text" name="person[email]" />
     * Phone: <input type="text" name="person[phone]" />
     * Eye Color: <input type="text" name="person[eyeColor]" />
     * </form>
     * </code>
     * 
     * The controller code to save entries to this form to the database as new records looks like this:
     *
     * <code>
     * $person = Person::createFromArray('Person', $this->parameters['person']);
     * $person->save();
     * </code>
     * @param array(string=>mixed) $params
     * @param string $classname
     * @returns array(JFPersistentObject)
     */
    public static function createFromArray($className, $params=array())
    {

        $object = new $className;
        
        $clz = new ReflectionClass( $className );
        $props = $clz->getProperties();
        
        foreach ($props as $prop)
        {
            $propName = $prop->name;
            $object->$propName = $params[$propName];
        }   

        return $object;
    }

    /**
     * updateAttributes is a function to allow you to update a Model's parameters in bulk.
     * You can pass in a hash of key/value pairs, the keys being the names of the properties 
     * to update, and the values being the values of those new properties.
     * 
     * A common use of this method is to update an object with input from an HTML form
     * @param array(string=>mixed) $params
     * @returns void
     */
    public function updateAttributes($params=array())
    {
        
        $clz = new ReflectionClass( get_class($this) );
        $props = $clz->getProperties();
        
        foreach ($props as $prop)
        {
            $propName = $prop->name;
			if(array_key_exists($propName, $params))
			{
				$this->$propName = $params[$propName];
			}
        }

    }

    /**
     * load retrieves a single object from the database
     *
     * Given a primary key identifier, the load function will return a single 
     * object containing the data from the specified record.
     *
     * <code>
     * $person = Person::load('Person', 2);
     * </code>
     *
     * @param mixed $id The database id of the object to retrieve.
     * @param string $className A string containing the class name to load (PHP is unable to determine this on its own due to a lack of late static binding)
     * @param array(string=>mixed) $params An array containing a set of key/value pairs to be fed as options. 
     * @returns JFPersistentObject
     */
    public static function load( $className, $id, $params=array())
    {

        $dbsession = ezcPersistentSessionInstance::get();
		
		if($params['include'])
		{
			$relationDef = self::buildRelationGraph($params['include']);

			$object = $dbsession->loadWithRelatedObjects($className, $id, $relationDef);
			
		}
		else
		{
        	$object = $dbsession->load($className, $id);
		}
		
        return $object;
    }
	
	protected static function buildRelationGraph($includes, $depth = 0, $idx = 0)
	{
	    //TODO Handle Named Relations
	    
		$includes = explode(',', $includes);
		
		$relationDef = array();
		foreach($includes as $include)
		{
			$include = trim($include);
			
			$deeperDef = array();
			//If this is a deeper-level relation, recurse into it
			if(strpos($include, '/'))
			{
				$includeParts = explode('/', $include);
				$include = array_shift($includeParts);
				
				$deeperDef = self::buildRelationGraph(implode('/', $includeParts), $depth+1, $idx);
			}
			
			$relationDef['r' . $idx . '_' . $depth]=new ezcPersistentRelationFindDefinition($include, null, $deeperDef);
			$idx++;
		}
		
		return $relationDef;
	}
    
    /**
     * findAll retrieves an array containing every object in a given model.
     *
     * This function is the equivalent of 'select * from [table]' with no where clause
     *
     * <code>
     * $people = Person::findAll('Person');
     * </code>
     *
     * @param array(string=>mixed) $params An array containing a set of key/value pairs to be fed as options. 
     * @param string $class A string containing the class name to load (PHP is unable to determine this on its own due to a lack of late static binding)
     * @param array(string=>mixed) $params An array containing a set of key/value pairs to be fed as options. 
     * Valid parameters include 'orderBy', 'limit', and 'offset'
     * @return array(JFPersistentObject)
     */
    public static function findAll($class, $params=array())
    {
    //echo "<pre>"; print_r(apd_callstack()); echo "</pre>";
        if(is_array($class))
        {
            $params=$class;
            $className = $params['class'];
        }
        else
        {
            $className = $class;
        }

        $dbsession = ezcPersistentSessionInstance::get();
        $q = $dbsession->createFindQuery($className);
        
        if ($params['orderBy'])
        {
            $q->orderBy($params['orderBy']);
        }
        if ($params['limit'] || $params['offset'])
        {
            $q->limit($params['limit'], $params['offset']);
        }
        
        $objects = $dbsession->find( $q, $className );
        return $objects;
    }
    
    public static function createFindQuery($className)
    {
        $dbsession = ezcPersistentSessionInstance::get();

        return $dbsession->createFindQuery($className);
    }


    /**
     * findByQuery allows you to retrieve a query object that may be used to define custom queries.
     *
     * @todo Complete 'includes' option functionality to enable eager association loading.
     * @param string $className The name of the class to query
     * @param ezcQuery $query A query object, created with {@link createFindQuery}
     * @param array(string=>mixed) $options A set of options to control the behavior of the find.
     * @return array(JFPersistentObject)
     */
    public static function findByQuery( $className, $query, $options=array())
    {
    
        $objects = null;
        if (!$options['include'])
        {
            $dbsession = ezcPersistentSessionInstance::get();
            $objects = $dbsession->find( $query, $className );
        }
        else
        {
            //The eager load method doesn't really eager load yet.
            $dbsession = ezcPersistentSessionInstance::get();
            $objects = $dbsession->find( $query, $className );

//            $stmt=$query->prepare();
//            $rows=$stmt->execute();
//            echo "<pre>"; print_r($rows); echo "</pre>";
        }
        
        if (!$options['single'])
        {
            return $objects;
        }
        else
        {
            return array_shift($objects);
        }
        
    }


    /**
     * Adds a related object
     */
    public function addRelated($object, $relationName = null)
    {
        $dbsession = ezcPersistentSessionInstance::get();
        $dbsession->addRelatedObject($this, $object, $relationName);
    }
    
    /**
     * Removes a related objects
     */
    public function removeRelated($object, $relationName = null)
    {
        $dbsession = ezcPersistentSessionInstance::get();
        $dbsession->removeRelatedObject($this, $object, $relationName);
    }
     
     

    public function getState()
    {
        $result = array();
        $clz = new ReflectionClass( get_class($this) );
        $props = $clz->getProperties();
        
        foreach ($props as $prop)
        {
            $propName = $prop->name;
//            echo $propName;
            $result[$propName] = $this->$propName;
        }

        $result['id'] = $this->id;

        return $result;
    }
      
    public function setState( array $properties )
    {
    //echo "<pre>"; print_r($properties); echo "</pre>";
        foreach( $properties as $key => $value )
        {
            $this->$key = $value;
        }
    }
    
    /** 
     * stub function to be overriden by children for data manipulation before object save
     */
    public function beforeSave()
    {
    
    }
    
    /** 
     * stub function to be overriden by children for data manipulation after object save
     */
    function afterSave()
    {
    
    }
    
    public function save()
    {
        if ($this->validate())
        {
            $this->beforeSave();
            $dbsession = ezcPersistentSessionInstance::get();
            $dbsession->saveOrUpdate($this);
            $this->afterSave();
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function getValidationRules()
    {
        return array();
    }
    
    public function validate()
    {
        $this->errors=array();
        $this->isValid=true;

        $prop = new ReflectionProperty(get_class($this), 'validations');
        $validRules = $prop->getValue($this);


        if (!$validRules)
        {
            return true;
        }
        
        foreach ($validRules as $field=>$rules)
        {
            $value = $this->$field;
            
//            echo "<pre>$field:"; print_r($rules); echo "</pre>";
            
            if($rules['prefilter'])
            {
                $value=preg_replace($rules['prefilter'],'',$value);
            }
            
            if(!$rules['required'] && ($value == null || $value == ''))
            {
                continue;
            }
            
            if($rules['required'] && ($value == null || $value == ''))
            {
                $this->isValid=false;
                $this->errors[$field]=$rules['message'];
                continue;
            }
            
            if($rules['type'])
            {
                switch($rules['type'])
                {
                    case 'int':
                    case 'integer':
                        if(!ctype_digit($value) && !is_numeric($value))
                        {
                            $this->isValid=false;
                            $this->errors[$field]=$rules['message'];
                            continue;
                        }
                        if($rules['min'] && intval($value) < $rules['min'])
                        {
                            $this->isValid=false;
                            $this->errors[$field]=$rules['message'];
                            continue;
                        }
                        if($rules['max'] && intval($value) > $rules['max'])
                        {
                            $this->isValid=false;
                            $this->errors[$field]=$rules['message'];
                            continue;
                        }
                    break;
                    case 'double':
                    case 'float':
                    case 'real':
                        if(!is_numeric($value))
                        {
                            $this->isValid=false;
                            $this->errors[$field]=$rules['message'];
                            continue;
                        }
                        if($rules['min'] && floatval($value) < $rules['min'])
                        {
                            $this->isValid=false;
                            $this->errors[$field]=$rules['message'];
                            continue;
                        }
                        if($rules['max'] && floatval($value) > $rules['max'])
                        {
                            $this->isValid=false;
                            $this->errors[$field]=$rules['message'];
                            continue;
                        }
                    break;
                    case 'bool':
                    case 'boolean':
                    break;
                    case 'string':
                        if($rules['format'] && !preg_match($rules['format'], $value))
                        {
                            $this->isValid=false;
                            $this->errors[$field]=$rules['message'];
                            continue;                            
                        }
                    break;
                }
            }
        }
        return $this->isValid;
    }
    

    public function delete()
    {
        $dbsession = ezcPersistentSessionInstance::get();
        $dbsession->delete($this);
    }
    
	protected function getRelatedObjects($relation)
	{
        $dbsession = ezcPersistentSessionInstance::get();
        
        if ($relation['orderBy']) //If no orderBy is used, we can use preloaded objects
        {
            $objects = $dbsession->getRelatedObjects($this, $relation['class'], $relation['name']);
        }
        else
        {
            if ($relation['name'])
            {
                $q = $dbsession->createRelationFindQuery($this, $relation['class'], $relation['name']);
            }
            else
            {
                $q = $dbsession->createRelationFindQuery($this, $relation['class']);
            }

            $q->orderBy($relation['orderBy']);
            
            try
            {
                if ($relation['name'])
                {
                    $objects = $dbsession->find($q, $relation['class'], $relation['name']);
                }
                else
                {
                    $objects = $dbsession->getRelatedObjects($this, $relation['class']);
                }
            }
            catch (ezcPersistentRelatedObjectNotFoundException $e)
            {
                return null;
            }            
        }

        if ($relation['type']=='single')
        {
            return array_shift($objects);
        }
        else
        {
            return $objects;
        }
		
	}
	
    public function __get($name)
    {
        $prop = new ReflectionProperty(get_class($this), 'relations');
        $relations = $prop->getValue($this);

        if ($relations[$name])
        {
			return $this->getRelatedObjects($relations[$name]);
        }
        $clz = new ReflectionClass( get_class($this) );

        if ($clz->hasMethod("get_$name"))
        {
            $method = $clz->getMethod("get_$name");
            return $method->invoke($this);
        }
        
    }    

}

?>
