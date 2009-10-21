<?php
/**
 * @package JetFuelCore
 */


/**
 * JFPersistentDefinitionManager provides a mapping between the database and the Model classes for use in development
 *
 * JetFuel's model layer is powered by the eZComponents PersistentObject component. This subsystem needs a way to 
 * map classes and properties to tables and fields. By default, eZ Components uses a code-based definition manager 
 * ( {@link ezcPersistentCodeManager} ) which provides good performance but requires code changes for every model change. 
 * 
 * JFPersistentDefinitionManager is designed for use in development scenarios where performance is not an issue and model 
 * changes occur frequently. This class uses hints on the model classes (children of {@link JFPersistentObject} ) and 
 * database inspection to automatically generate the persistence definition as needed at runtime.
 * 
 * This class is part of the JetFuel internals and need not be called by the developer under normal circumstances. If the 
 * framework is operating in development mode, the JF PersistentDefinitionManager will be used automatically.
 * 
 * @package JetFuelCore
 */
class JFPersistentDefinitionManager extends ezcPersistentDefinitionManager 
{


    /**
     * Returns an array structure providing the definition for the given class
     * 
     * @throws ezcPersistentDefinitionNotFoundException if no such definition can be found.
     * @param string $class
     * @return ezcPersistentObjectDefinition
     * @see ezcPersistentDefinitionManager::fetchDefinition()
     */
    public function fetchDefinition($class) 
	{

        //TODO Add handling for named relations
		//Reflect from the class name
		$refclass = new ReflectionClass($class);
		
		
		$def = new ezcPersistentObjectDefinition();
		$def->table = $class;
		$def->class = $class;
		
		$db = ezcDbInstance::get();
		
		$sql = "SHOW FIELDS FROM $class";

		$stmt = $db->prepare($sql);
		
		$stmt->execute();
		
		$fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$types = array();
		$keys = array();
		
		foreach($fields as $field)
		{
			$types[$field['field']]=$field['type'];
			if($field['key'] == 'PRI')
			{
				$keys[]=$field['field'];
			}			
		}
		
		$fieldMap = null;
		
		try
		{
			$fieldMap = $refclass->getStaticPropertyValue('fieldMap');
		}
		catch(ReflectionException $e)
		{
			foreach($types as $field=>$type)
			{
				$propertyName = $this->camelizeFieldName($field);
				$fieldMap[$propertyName] = $field;				
			}
		
		}
		
		$fieldMap = array_combine(array_values($fieldMap) , array_keys($fieldMap));
		
		//Determine the properties
		foreach($types as $field=>$type)
		{
			if(in_array($field, $keys))
			{
				continue;	
			}
			
			$prop = new ezcPersistentObjectProperty();
			$prop->columnName = $field;
			$prop->propertyName = $fieldMap[$field];
			$prop->propertyType = $this->getType($type);

			$def->properties[$field] = $prop;
		}
		
		//Determine the id property(ies)
		foreach($keys as $key)
		{
			$idProp = new ezcPersistentObjectIdProperty();
			$idProp->columnName = $key;
			$idProp->propertyName = $this->camelizeFieldName($key);
			$idProp->propertyType = $this->getType($types[$key]);
			
			if($idProp->propertyType == ezcPersistentObjectProperty::PHP_TYPE_INT)
			{
				$idProp->generator = new ezcPersistentGeneratorDefinition( 'ezcPersistentSequenceGenerator' );
			}
			else
			{
				$idProp->generator = new ezcPersistentGeneratorDefinition( 'ezcPersistentManualGenerator' );				
			}
			
			$def->idProperty = $idProp;
				
		}
		
		//Determine the relationships
		$relations = $refclass->getStaticPropertyValue('relations');
		
		foreach($relations as $relation)
		{
			
			//TODO: Define all relationship types
			switch($relation['type'])
			{
				case 'manyToOne':
				case 'single':
					$rel = new ezcPersistentManyToOneRelation($class,$relation['class']);
					$rel->columnMap = array(new ezcPersistentSingleTableMap(strtolower($relation['class']) . '_id','id'));
					$def->relations[$relation['class']]=$rel;
				break;
				case 'oneToOne':
					
				break;
				case 'manyToMany':
					
				break;
				case 'oneToMany':
				default:
					$rel = new ezcPersistentOneToManyRelation($class,$relation['class']);
					$rel->columnMap = array(new ezcPersistentSingleTableMap('id', strtolower($class) . '_id'));
					$def->relations[$relation['class']]=$rel;
					
			}
			
		}
		
		$def = $this->setupReversePropertyDefinition( $def );		
		//echo "<pre>"; print_r($relations); echo "</pre>";
		
		//echo "<pre>"; print_r($def); echo "</pre>";
		return $def;
		
    }
	
	protected function camelizeFieldName($input)
	{
		$words = ucwords(str_replace("_",' ',$input));

		$words[0]=strtolower($words[0]);

		return str_replace(' ','',$words);
	}

	protected function getType($type)
	{
		$type = substr($type, 0, strpos($type, '('));
		
		//TODO Make this a comprehensive list
		switch($type)
		{
			case 'int':
			case 'shortint':
			case 'tinyint':
			case 'longint':
				return ezcPersistentObjectProperty::PHP_TYPE_INT;
			break;
			case 'float':
			case 'decimal':
				return ezcPersistentObjectProperty::PHP_TYPE_FLOAT;
			break;
			default:
				return ezcPersistentObjectProperty::PHP_TYPE_STRING;
		}
	}	

}
?>