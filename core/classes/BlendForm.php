<?php
/**
 * @package TrevorCore
 */
 

/**
 * @package TrevorCore
 */
class BlendForm
{
    private $ezForm;
    private $definition;
    public $errors=array();
    public $messages;
    
    function __construct($inputSource, $definition, $messages=array())
    {
        $form = $this->ezForm=new ezcInputForm($inputSource, $definition);
        $this->messages=$messages;
        $this->definition=$definition;
        foreach ($definition as $name => $def)
        {
            if ($form->hasValidData($name))
            {
                $this->$name=$form->$name;
            }
            else
            {
                $this->errors[$name]=$this->messages[$name];
                $this->$name=$form->getUnsafeRawData($name);
            }
        }
        
    }
    
    function isValid()
    {
        return !count($this->errors);
    }
    
    function setAttributes(&$object)
    {
        foreach($this->definition as $name => $def)
        {
            $object->$name = $this->$name;
        }

    }
}
?>