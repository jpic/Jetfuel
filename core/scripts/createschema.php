<?php
require_once('core/common.php');
// create a database schema from a database connection:
echo DB_DSN;
$db = ezcDbFactory::create( DB_DSN );
$dbSchema = ezcDbSchema::createFromDb( $db );
// save a database schema to an XML file:
$dbSchema->writeToFile( 'xml', 'saved-schema.xml' );


$messages = ezcDbSchemaValidator::validate( $dbSchema );
foreach ( $messages as $message )
{
     echo $message, "\n";
}
?>