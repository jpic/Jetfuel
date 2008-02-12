<?php
/**
 * @package TrevorCore
 */
 
/**
 * BlendPersistenObject is a helpful base class for persistent database objects.
 * @package TrevorCore
 */
class BlendPersistentObject
{

    function __construct()
    {
    }
    
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

    public function updateAttributes($params=array())
    {
        
        $clz = new ReflectionClass( get_class($this) );
        $props = $clz->getProperties();
        
        foreach ($props as $prop)
        {
            $propName = $prop->name;
            $this->$propName = $params[$propName];
        }

    }

    public static function load($id,$params=array())
    {
        $className = $params['class'];
        $dbsession = ezcPersistentSessionInstance::get();
        $object = $dbsession->load($className, $id);
        
        return $object;
    }
    
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

    public static function findByQuery($params)
    {
        $className=$params['class'];

        $dbsession = ezcPersistentSessionInstance::get();
        $q = $dbsession->createFindQuery($className);
        $objects = $dbsession->find( $params['query'], $className );
        return $objects;
        
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

}

?>