<?php
// Autogenerated PersistentObject definition

$def = new ezcPersistentObjectDefinition();
$def->table = 'comment';
$def->class = 'comment';

$def->properties['body']               = new ezcPersistentObjectProperty();
$def->properties['body']->columnName   = 'body';
$def->properties['body']->propertyName = 'body';
$def->properties['body']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;


$def->properties['created_at']               = new ezcPersistentObjectProperty();
$def->properties['created_at']->columnName   = 'created_at';
$def->properties['created_at']->propertyName = 'created_at';
$def->properties['created_at']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;


$def->properties['email']               = new ezcPersistentObjectProperty();
$def->properties['email']->columnName   = 'email';
$def->properties['email']->propertyName = 'email';
$def->properties['email']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;


$def->idProperty               = new ezcPersistentObjectIdProperty();
$def->idProperty->columnName   = 'id';
$def->idProperty->propertyName = 'id';
$def->idProperty->generator    = new ezcPersistentGeneratorDefinition( 'ezcPersistentSequenceGenerator' );


$def->properties['name']               = new ezcPersistentObjectProperty();
$def->properties['name']->columnName   = 'name';
$def->properties['name']->propertyName = 'name';
$def->properties['name']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_STRING;


$def->properties['post_id']               = new ezcPersistentObjectProperty();
$def->properties['post_id']->columnName   = 'post_id';
$def->properties['post_id']->propertyName = 'post_id';
$def->properties['post_id']->propertyType = ezcPersistentObjectProperty::PHP_TYPE_INT;

$def->relations["Post"] = new ezcPersistentManyToOneRelation("comment","post");
$def->relations["Post"]->columnMap = array(new ezcPersistentSingleTableMap('post_id','id'));

return $def;

?>