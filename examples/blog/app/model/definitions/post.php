<?php
// Autogenerated PersistentObject definition

$def = new ezcPersistentObjectDefinition();
$def->table = 'post';
$def->class = 'post';

$def->properties['body']               = new ezcPersistentObjectProperty();
$def->properties['body']->columnName   = 'body';
$def->properties['body']->propertyName = 'body';
$def->properties['body']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;


$def->properties['created_at']               = new ezcPersistentObjectProperty();
$def->properties['created_at']->columnName   = 'created_at';
$def->properties['created_at']->propertyName = 'created_at';
$def->properties['created_at']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;


$def->idProperty               = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName   = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator    = new ezcPersistentGeneratorDefinition( 'ezcPersistentSequenceGenerator' );


$def->properties['summary']               = new ezcPersistentObjectProperty();
$def->properties['summary']->columnName   = 'summary';
$def->properties['summary']->propertyName = 'summary';
$def->properties['summary']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;


$def->properties['title']               = new ezcPersistentObjectProperty();
$def->properties['title']->columnName   = 'title';
$def->properties['title']->propertyName = 'title';
$def->properties['title']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;

$def->relations["Comment"] = new ezcPersistentOneToManyRelation("post","comment");
$def->relations["Comment"]->columnMap = array(new ezcPersistentSingleTableMap('id','post_id'));

return $def;

?>
