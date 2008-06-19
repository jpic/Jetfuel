<?php

class PostsController extends ApplicationController
{
    function index()
    {
        $this->vars['posts']=Post::findAll('Post',array('orderBy'=>'created_at desc'));
    }
    
    function show()
    {
        $this->vars['post']=Post::load('Post',$this->parameters['id']);
    }
    
    function newobj()
    {
    }
    
    function create()
    {
        $post = Post::createFromArray('Post',$this->parameters['post']);
        
        if($post->save())
        {
            $this->redirect("/posts/$post->id");
            return;
        }
        $this->vars['post']=$post;
        $this->template='newobj';
    }
    
    function edit()
    {
        $this->vars['post']=Post::load('Post',$this->parameters['id']);    
    }
    
    function update()
    {
        $post = Post::load('Post',$this->parameters['id']);
        $post->updateAttributes($this->parameters['post']);
        if($post->save())
        {
            $this->redirect('/posts/' + $post->id);
            return;
        }
        $this->vars['post']=$post;
        $this->template='edit';
    }
    
    function delete()
    {
        $post = Post::load('Post',$this->parameters['id']);
        
        if($post)
        {
            $post->delete();
        }
        
        $this->redirect('/posts');
            
    }
    
}

?>