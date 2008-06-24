<?php
/**
 * @package JetFuelCore
 * common.php bootstraps the eZ Components and JetFuel environment.
 */

require_once dirname(__FILE__) . '/../settings/environment.php';

if(defined('EZC_ROOT'))
{
	set_include_path( EZC_ROOT . ":" . EZC_ROOT . "/trunk:" . ini_get( "include_path" )  );
}

require_once EZC_BASE;

function __autoload( $className )
{
    ezcBase::autoload( $className );
}

define('SITE_ROOT', dirname(__FILE__) . '/..');
define('APP_ROOT', dirname(__FILE__) . '/../app');


$cfg = ezcConfigurationManager::getInstance();
$cfg->init('ezcConfigurationIniReader', SITE_ROOT . '/settings');


define('DIR_DEFINITIONS', SITE_ROOT . '/app/model/definitions');


$driver = $cfg->getSetting('config','Database','Driver');
$host = $cfg->getSetting('config','Database','Host');
$port = $cfg->getSetting('config','Database','Port');
$username = $cfg->getSetting('config','Database','User');
$password = $cfg->getSetting('config','Database','Password');
$database = $cfg->getSetting('config','Database','Database');
$dsn = $driver . '://' . $username . ':' . $password . '@'. $host . '/' . $database;

define('DB_DSN', $dsn);

$dbInstance = ezcDbFactory::create( DB_DSN );
ezcDbInstance::set( $dbInstance );

//require_once "settings/log.php";


$session = new ezcPersistentSession( $dbInstance,
        new ezcPersistentCacheManager( new ezcPersistentCodeManager( DIR_DEFINITIONS ) ) );
        
ezcPersistentSessionInstance::set( $session ); // set default session
 
ezcBase::addClassRepository( '.', SITE_ROOT . '/core/autoloads' );
// retrieve the session
//$session = ezcPersistentSessionInstance::get();
?>
