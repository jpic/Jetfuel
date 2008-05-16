<?php
/**
 * @package JetFuelCore
 */
 
/**
 * BlendExecutionHandler provides the ability to intercept and handle error messages from the application.
 * @package JetFuelCore
 */
class JFExecutionHandler implements ezcExecutionErrorHandler
{
    public static function onError( Exception $e = NULL )
    {

        echo JFExecutionHandler::BuildUserError($e);


        $mail = new ezcMailComposer();
        $mail->from = new ezcMailAddress( 'errors@blendinteractive.com', 'JetFuel Error Mailer' );
        $mail->addTo( new ezcMailAddress( 'errors@blendinteractive.com', 'Blend Errors' ) );
        $mail->subject = "Error occured on " . $_SERVER['HTTP_HOST'];
        $mail->plainText = JFExecutionHandler::BuildPlainTextEmail($e);
        $mail->htmlText = JFExecutionHandler::BuildEmail($e);

        $mail->build();
        $transport = new ezcMailMtaTransport();
        $transport->send( $mail );

    }


    private function BuildEmail($e)
    {
        $message='';
        $StackCount=0;
        if ( !is_null( $e ) )
        {
            $CallStackItems=array_reverse($e->getTrace());

            $message='<html>'.
                '<style type="text/css">'.
                'table{border:1px solid black;}td{border:1px solid black;}'.
                '</style>'.
                '<b>Exception Code:</b> '.$e->getCode().'<br/>'.
                '<b>Occured At:</b> '.date('Y-m-d H:i:s').'<br/>'.
                
                $e->getMessage().' in '.
                $e->getFile().' on line '.
                '<i>'.$e->getLine().'</i>'.
                '<div style="font-weight:bold; margin:10px 0px 5px;">Call Stack</div>'.
                '<table style="border:1px solid black; padding:3px;"><tr><th>#</th><th style="border:1px solid black; padding:3px;">Function</th><th style="border:1px solid black; padding:3px;">Location</th></tr>';
                
            foreach($CallStackItems as $CallStackItem)
            {
                $StackCount=$StackCount+1;
                $message=$message.'<tr>'.
                   '<td style="border:1px solid black; padding:3px;">'.$StackCount.'</td>'.
                   '<td style="border:1px solid black; padding:3px;">'.$CallStackItem['class'].$CallStackItem['type'].$CallStackItem['function'].'</td>'.
                   '<td style="border:1px solid black; padding:3px;">'.$CallStackItem['file'].':'.$CallStackItem['line'].'</td>'.
                '</tr>';
            }
            $message=$message.'</table>';
           
        }
        else
        {
            
           /* 
           $x=fopen("chris.log","a");
            fwrite($x,"unclean exit ".date("Y-m-d  h:i:s")."\n");
            fclose($x);
            */
            $message = "Unclean Exit - ezcExecution::cleanExit() was not called.";
        }
        return $message;
    }

    private function BuildPlainTextEmail($e)
    {
        $message='';
        $StackCount=0;
        if ( !is_null( $e ) )
        {
            $CallStackItems=array_reverse($e->getTrace());

            $message='Exception Code: '.$e->getCode()."\n".
                'Occured At: '.date('Y-m-d H:i:s')."\n".                
                $e->getMessage().' in '.
                $e->getFile().' on line '.$e->getLine()."\n\n\nCall Stack\n";
                
            foreach($CallStackItems as $CallStackItem)
            {
                $StackCount=$StackCount+1;
                $message=$message.
                    "\n\n#: ".$StackCount.
                    "\nFunction: ".$CallStackItem['class'].$CallStackItem['type'].$CallStackItem['function'].
                   "\nLocation: ".$CallStackItem['file'].':'.$CallStackItem['line'];
            }
            
        }
        else
        {
            $message = "Unclean Exit - ezcExecution::cleanExit() was not called.";
        }
        return $message;

    }

    
    private function BuildUserError($e)
    {
        $message= JFExecutionHandler::GetUserMessage($e);
        return "$message";
    }

    private function GetUserMessage($e)
    {
        $message='The application had an internal error.  We apologize for the inconvenience.';
        return $message;
    }

    


}
?>
