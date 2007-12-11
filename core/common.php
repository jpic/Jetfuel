<?php
//require_once "ezc/Base/base.php";
//session_set_cookie_params(time() + (86400 * 90));

require_once "ezc/Base/src/base.php";
require_once "settings/config.php";

//require_once "core/classes/Dispatcher.php";


define('DIR_DEFINITIONS',SITE_FILE_ROOT . 'app/model/definitions');

//require_once('core/classes/Action.php');

function __autoload( $className )
{
    ezcBase::autoload( $className );
}
$dbInstance = ezcDbFactory::create( DB_DSN );
ezcDbInstance::set( $dbInstance );

require_once "settings/log.php";


$session = new ezcPersistentSession( $dbInstance,
        new ezcPersistentCacheManager( new ezcPersistentCodeManager( DIR_DEFINITIONS ) ) );
        
ezcPersistentSessionInstance::set( $session ); // set default session
 
ezcBase::addClassRepository( '.', SITE_FILE_ROOT . 'core/autoloads' );
// retrieve the session
//$session = ezcPersistentSessionInstance::get();
?>