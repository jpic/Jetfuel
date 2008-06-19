<?php
/**
 * @package JetFuelCore
 * Routes.php defines how URL's are routed to controller actions within the app
 */

 //Default routes. Define after all others
 //$route->addRule('/',array('controller'=>'workitem','action'=>'index'));

 //$route->addResource('client');
 //$route->addRule('/foo/bar',array('controller'=>'test','action'=>'list'));

 //$route->addRule('/baz/:test',array('controller'=>'test2','action'=>'index'));

$route->addRule('/',array('controller'=>'posts','action'=>'index'));

$route->addResource('posts');
$route->addResource('comments');

$route->addRule('/:controller/:action/:id');
$route->addRule('/:controller/:action');
$route->addRule('/:controller',array('action'=>'index'));

?>
