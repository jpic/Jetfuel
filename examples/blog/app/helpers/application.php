<?php

function errorMessage($obj, $attr)
{
    if($obj->errors[$attr])
    {
    
        echo "<div class=\"error\">" . $obj->errors[$attr] . "</div>\n";
    
    }

}

?>