<?php
set_include_path( __FILE__ . "/../../../ezc/trunk:" . ini_get( "include_path" )  );




require_once '../../core/common.php';
require_once '../../core/error_handler.php';

//ezcExecution::init( 'BlendExecutionHandler' );
$debug = ezcDebug::getInstance();

$dispatch = new Dispatcher();
$dispatch->dispatch();

//ezcExecution::cleanExit();

?>
