<?php

class CommentsController extends ApplicationController
{
    function index()
    {
        $this->redirect('/posts');
    }
    
    function show()
    {
        $comment = Comment::load('Comment',$this->parameters['id']);
        $this->redirect('/posts/' . $comment->post_id . '#' . $comment->id );
    }
    
    function newobj()
    {
    }
    
    function create()
    {
        $comment = Comment::createFromArray('Comment',$this->parameters['comment']);
        
        if($comment->save())
        {
            $this->redirect('/posts/' . $comment->post_id);
            return;
        }
        $this->vars['comment']=$comment;
    }
    
    function edit()
    {
    }
    
    function update()
    {
    }
    
}

?>