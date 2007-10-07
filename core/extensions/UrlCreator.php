<?php 

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
                $def->parameters = array( "name", "[params]");

                return $def;
        }

        return false;
    }


    public static function geturl( $name, $params = null)
    {
		
		if($params)
		{
			return ezcUrlCreator::getUrl($name, $params);
		}
		else
		{
			return ezcUrlCreator::getUrl($name);
		}
    }
}

?>