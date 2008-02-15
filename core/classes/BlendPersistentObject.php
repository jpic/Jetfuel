<?php
/**
 * @package TrevorCore
 */
 
/**
 * BlendPersistentObject is a helpful base class for persistent database objects.
 * 
 * BlendPersistentObject represents the 'Model' portion of the Model/View/Controller pattern
 * This base class is extended by your application's Model objects, and provides a number of 
 * convenience functions to make it easy to manipulate your data via objects.
 *
 * To use it, you create an object that extends BlendPersistenObject, then set variables for 
 * each of the fields in your object (usually, one class represents one table in the database). 
 *
 * <code>
 * <?php
 * class Person extends BlendPersistentObject
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
 *    $people=Person::findAll(array('class'=>'Person')); //Retrieve all Person records
 *
 *    $bob=Person::load(2,array('class'=>'Person')); //Retrieve Person record #2
 *
 *    //Create a new Person, tim, and save him to the database.
 *    $tim=Person::createFromArray(array(
 *            'name'=>'Tim', 
 *            'email'=>'tim@example.com', 
 *            'phone'=>'123-123-1234', 
 *            'eyeColor'=>'blue'), 'Person'); 
 *    $tim->save();
 *
 *    
 * </code>
 * 
 * @package TrevorCore
 * @abstract
 */
class BlendPersistentObject
{

    function __construct()
    {
    }
    
    /**
     * createFromArray allows you to create a new database entry by feeding in data from a hash of properties.
     * 
     * <code>
     * $bob = Person::createFromArray(array(
     *          'name'=>'Bob Smith',
     *          'email'=>'bob@example.com',
     *          'phone'=>'555-123-1212',
     *          'eyeColor'=>'green'), 'Person');
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
     * $person = Person::createFromArray($this->parameters['person']);
     * $person->save();
     * </code>
     * @param array(string=>mixed) $params
     * @param string $classname
     * @returns array(BlendPersistentObject)
     */
    public static function createFromArray($params=array(), $className)
    {
        $object = new $className;
        
        $clz = new ReflectionClass( $className );
        $props = $clz->getProperties();
        
        foreach ($props as $prop)
        {
            $propName = $prop->name;
//            echo $propName;
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
			if($params[$propName])
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
     * $person = Person::load(2, array('class'=>'Person'));
     * </code>
     *
     * @param mixed $id The database id of the object to retrieve.
     * @param array(string=>mixed) $params An array containing a set of key/value pairs to be fed as options. 
     * There is currently a required parameter of 'class' due to an object limitation in PHP.
     * @returns BlendPersistentObject
     */
    public static function load($id,$params=array())
    {
        $className = $params['class'];
        $dbsession = ezcPersistentSessionInstance::get();
        $object = $dbsession->load($className, $id);
        
        return $object;
    }
    
    /**
     * findAll retrieves an array containing every object in a given model.
     *
     * This function is the equivalent of 'select * from [table]' with no where clause
     *
     * <code>
     * $people = Person::findAll(array('class'=>'Person'));
     * </code>
     *
     * @param array(string=>mixed) $params An array containing a set of key/value pairs to be fed as options. 
     * There is currently a required parameter of 'class' due to an object limitation in PHP.
     * @return array(BlendPersistentObject)
     */
    public static function findAll($params=array())
    {
    //echo "<pre>"; print_r(apd_callstack()); echo "</pre>";
        $className=$params['class'];

        $dbsession = ezcPersistentSessionInstance::get();
        $q = $dbsession->createFindQuery($className);
        
        if ($params['orderby'])
        {
            $q->orderBy($params['orderby']);
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
     * @todo Complete 'includes' option functionality to enable eager association loading.
     * @param ezcQuery $query A query object, created with {@link createFindQuery}
     * @param string $className The name of the class to query
     * @param array(string=>mixed) $options A set of options to control the behavior of the find.
     * @return array(BlendPersistentObject)
     */
    public static function findByQuery($query, $className, $options=array())
    {
    
        if (!$options['include'])
        {
            $dbsession = ezcPersistentSessionInstance::get();
            $objects = $dbsession->find( $query, $className );
            return $objects;
        }
        else
        {
            //The eager load method doesn't really eager load yet.
            $dbsession = ezcPersistentSessionInstance::get();
            $objects = $dbsession->find( $query, $className );
            return $objects;

//            $stmt=$query->prepare();
//            $rows=$stmt->execute();
//            echo "<pre>"; print_r($rows); echo "</pre>";
        }
        
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
    
    public function save()
    {
        $dbsession = ezcPersistentSessionInstance::get();
        $dbsession->saveOrUpdate($this);
    }
    
    public function delete()
    {
        $dbsession = ezcPersistentSessionInstance::get();
        $dbsession->delete($this);
    }
    
    public function __get($name)
    {
        if ($this->relations[$name])
        {
            
            $session = ezcPersistentSessionInstance::get();
            try {
            $objects = $session->getRelatedObjects($this, $this->relations[$name]);
            }
            catch (ezcPersistentRelatedObjectNotFoundException $e)
            {
                return array();
            }
            return $objects;
        }

    }    

}

?>