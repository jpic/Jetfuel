<?php
/**
 * @package JetFuelCore
 */
require_once('core/common.php');
$output = new ezcConsoleOutput(); 
$output->formats->info->color = 'blue';

// create a database schema from a database connection:
$db = ezcDbFactory::create( DB_DSN );
$dbSchema = ezcDbSchema::createFromDb( $db );
// save a database schema to an XML file:
$dbSchema->writeToFile( 'xml', 'saved-schema.xml' );

$messages = ezcDbSchemaValidator::validate( $dbSchema );
foreach ( $messages as $message )
{
	$output->outputLine( $message, 'info' );
}
	
$readerClass = ezcDbSchemaHandlerManager::getReaderByFormat( 'xml' );

$reader = new $readerClass();

$schema = ezcDbSchema::createFromFile( 'xml', 'saved-schema.xml' );
$writer = new ezcDbSchemaPersistentWriter( true, null );
$writer->saveToFile( 'app/model/definitions', $schema );

$output->outputLine( "Class files successfully written to app/model/definitions.", 'info' );

?>