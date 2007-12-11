<?php
global $model;
$classes=array(
  'Action'=>'core/classes/Action.php',
  'BlendComponent'=>'core/classes/BlendComponent.php',
  'BlendController'=>'core/classes/BlendController.php',
  'BlendModule'=>'core/classes/BlendModule.php',
  'BlendForm'=>'core/classes/BlendForm.php',
  'BlendPersistentObject'=>'core/classes/BlendPersistentObject.php',
  'Dispatcher'=>'core/classes/Dispatcher.php',
  'CustomDate'=>'core/extensions/CustomDate.php',
  'CustomMath'=>'core/extensions/CustomMath.php',
  'UrlCreator'=>'core/extensions/UrlCreator.php',
  'MoneyFormat'=>'core/extensions/MoneyFormat.php'
);

foreach($model as $modelClass)
{
  $classes[$modelClass]='app/model/' . $modelClass . '.php';
}

return $classes;
?>