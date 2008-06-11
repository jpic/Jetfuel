<?php
/**
 * @package JetFuelCore
 */

/**
 * Available from the view templates, the _t function translates a string into the appropriate language for the user.
 * @param string $text A text string to be translated.
 * @returns string The translated string.
 * @todo Make the _t function use ezcTranslation. Include cached output and optional bork and leet filters
 **/
function _t($text)
{
    return $text;
}

?>