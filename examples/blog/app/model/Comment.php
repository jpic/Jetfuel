<?php

class Comment extends JFPersistentObject
{

    public $id = null;
    public $name;
    public $email;
    public $body;
    public $created_at;
    public $post_id;
    
    public static $relations = array(
        'post'=>array('class'=>'Post','type'=>'single')
    );
    
    public static $validations = array(
        'name'=>array(
            'type'=>'string',
            'required'=>true,
            'message'=>'Please enter your name.'
        ),
        'email'=>array(
            'type'=>'string',
            'required'=>true,
            'format'=>'/^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i',
            'message'=>'Please enter your email.'
        ),
        'body'=>array(
            'type'=>'string',
            'required'=>true,
            'message'=>'Please enter a body.'
        )
    );
}

?>