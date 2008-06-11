<?php 
/**
 * @package JetFuelCore
 */

/**
 * @package JetFuelCore
 */
class CustomDate implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition( $name )
    {
        switch ($name )
        {
            case "strtotime":
                $def = new ezcTemplateCustomFunctionDefinition();
                $def->class = "CustomDate";
                $def->method = "StrToUnixTimestamp";

                // Deprecated:
                $def->parameters = array( "DateInString");

                return $def;
        }

        return false;
    }


    public static function StrToUnixTimestamp( $DateInString )
    {
		return strtotime($DateInString);
    }
}

?>