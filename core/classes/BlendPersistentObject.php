<?php

class BlendPersistentObject
{

    function __construct()
    {
    }


    public function getState()
    {
        $result = array();
        
        $clz = new ReflectionClass( get_class($this) );
        $props = $clz->getProperties();
        
        foreach ($props as $prop)
        {
            $propName = $prop['name'];
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

}

?>