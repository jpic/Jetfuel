<?php 

class MoneyFormat implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition( $name )
    {
        switch ($name )
        {
            case "money_format":
                $def = new ezcTemplateCustomFunctionDefinition();
                $def->class = "MoneyFormat";
                $def->method = "format";

                // Deprecated:
                $def->parameters = array( "number");

                return $def;
        }

        return false;
    }


    public static function format( $number )
    {
		return money_format('%i',$number);
    }
}

?>