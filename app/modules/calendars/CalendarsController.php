<?php
require_once 'app/model/Calendar.php';
class CalendarsController extends BlendController
{
    public function lst($parameters)
    {
        $session = ezcPersistentSessionInstance::get();
        $q = $session->createFindQuery('Calendar');
        $q->orderBy('name desc');
        
        $calendars = $session->find( $q, 'Calendar' );
        $this->vars['calendars']=$calendars;  
       // echo "<pre>TASKS:\n";print_r($tasks);echo "</pre>";  
    }
    
    public function detail($parameters)
    {
        $id = $parameters['id'];
        $session = ezcPersistentSessionInstance::get();
        
        $calendar = $session->load('Calendar', $id);
        $this->vars['calendar']=$calendar;
    }
    
    public function add($parameters)
    {

    
        if($parameters['SubmitButton'])
        {
            $form = new ezcInputForm(INPUT_POST, Calendar::$formdef);
            if (!$form->getInvalidProperties())
            {
                $calendar = new Calendar();
                $calendar->name = $form->name;
                $calendar->protocol = $form->protocol;
                $calendar->url = $form->url;
                $calendar->username = $form->username;
                $calendar->password = $form->password;

                $session = ezcPersistentSessionInstance::get();                
                $session->save($calendar);
                
            }
            else
            {
                $this->vars['errors']=$form->getInvalidProperties();
            }
        }
    }
    
    public function delete($parameters)
    {
    }
}

?>