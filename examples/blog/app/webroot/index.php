<?php

require_once '../../core/common.php';
require_once '../../core/error_handler.php';

$debug = ezcDebug::getInstance();

$dispatch = new Dispatcher();
$dispatch->dispatch();

?>
