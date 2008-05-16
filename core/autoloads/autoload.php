<?php
/** 
 * @package JetFuelCore
 */

global $model;
$classes=array(
  'JFController'=>'../../core/classes/JFController.php',
  'JFPersistentObject'=>'../../core/classes/JFPersistentObject.php',
  'JFRouter'=>'../../core/classes/JFRouter.php',
  'Dispatcher'=>'../../core/classes/Dispatcher.php',
  'CustomDate'=>'../../core/extensions/CustomDate.php',
  'CustomMath'=>'../../core/extensions/CustomMath.php',
  'UrlCreator'=>'../../core/extensions/UrlCreator.php',
  'MoneyFormat'=>'../../core/extensions/MoneyFormat.php',
  'ApplicationController'=>'../../app/controllers/ApplicationController.php'
);

/*
foreach($model as $modelClass)
{
  $classes[$modelClass]='app/model/' . $modelClass . '.php';
}
*/
return $classes;
?>