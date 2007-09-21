<?php
//require_once "ezc/Base/base.php";
require_once "ezc/Base/src/base.php";
require_once "settings/config.php";

require_once "core/classes/Dispatcher.php";


define('DIR_DEFINITIONS','app/model/definitions');

require_once('core/classes/Action.php');

function __autoload( $className )
{
    ezcBase::autoload( $className );
}

$dbInstance = ezcDbFactory::create( DB_DSN );
ezcDbInstance::set( $dbInstance );

$session = new ezcPersistentSession( $dbInstance,
        new ezcPersistentCacheManager( new ezcPersistentCodeManager( DIR_DEFINITIONS ) ) );
        
ezcPersistentSessionInstance::set( $session ); // set default session
 
// retrieve the session
//$session = ezcPersistentSessionInstance::get();

?>