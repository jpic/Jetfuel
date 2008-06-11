<?php 
/**
 * @package JetFuelCore
 */

/**
 * @package JetFuelCore
 */
class UrlCreator implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition( $name )
    {
        switch ($name )
        {
            case "geturl":
                $def = new ezcTemplateCustomFunctionDefinition();
                $def->class = "UrlCreator";
                $def->method = "geturl";

                // Deprecated:
                $def->parameters = array( "name", "[params]", "[params2]");

                return $def;
        }

        return false;
    }


    public static function geturl( $name, $params = null, $params2 = null)
    {
        try
        {
            if($params)
            {
                    return ezcUrlCreator::getUrl($name, $params, $params2);
            }
            else
            {
                    return ezcUrlCreator::getUrl($name);
            }
        }
        catch (Exception $e)
        {
            return '#';
        }
    }
}

?>