<?php
require_once 'app/model/Task.php';
class TasksController extends BlendController
{
    public function lst($parameters)
    {
        $session = ezcPersistentSessionInstance::get();
        $q = $session->createFindQuery('Task');
        $q->where( $q->expr->eq('parent_id', 0) )
            ->orderBy('name desc');
        
        $tasks = $session->find( $q, 'Task' );
        $this->vars['tasks']=$tasks;  
        $this->vars['test']="Hello!";
       // echo "<pre>TASKS:\n";print_r($tasks);echo "</pre>";  
    }
    
    public function detail($parameters)
    {
        $id = $parameters['id'];
        $session = ezcPersistentSessionInstance::get();
        
        $task = $session->load('Task', $id);
        $this->vars['task']=$task;
    }
    
    public function add($parameters)
    {
        if(array_key_exists('SubmitButton',$parameters))
        {
        
        }
    }
    
    public function delete($parameters)
    {
    }
}

?>