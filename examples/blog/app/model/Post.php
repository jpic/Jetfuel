<?php

class Post extends JFPersistentObject
{

    public $id = null;
    public $title;
    public $summary;
    public $body;
    public $created_at;
    
    public static $relations = array(
        'comments'=>array('class'=>'Comment','orderBy'=>'created_at desc'),
        'author'=>array('class'=>'Author','type'=>'single')
    );
    
    public static $validations = array(
        'title'=>array(
            'type'=>'string',
            'required'=>true,
            'message'=>'Please enter a title.'
        ),
        'body'=>array(
            'type'=>'string',
            'required'=>true,
            'message'=>'Please enter a body.'
        )
    );
}

?>