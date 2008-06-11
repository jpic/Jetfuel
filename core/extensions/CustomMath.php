<?php 
/**
 * @package JetFuelCore
 */

/**
 * @package JetFuelCore
 */
class CustomMath implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition( $name )
    {
        switch ($name )
        {
            case "round":
                $def = new ezcTemplateCustomFunctionDefinition();
                $def->class = "CustomMath";
                $def->method = "RoundToDigits";

                // Deprecated:
                $def->parameters = array( "NumToRound", "[DigitsTo]");

                return $def;
        }

        return false;
    }


    public static function RoundToDigits( $NumToRound, $DigitsTo = "isOptional")
    {
		if($DigitsTo)
		{
			$NumToReturn = round($NumToRound, $DigitsTo);
		}
		else
		{
			$NumToReturn = round($NumToRound);
		}
		return $NumToReturn;
    }
}

?>