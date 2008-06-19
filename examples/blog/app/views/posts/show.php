<?php $pageTitle = $post->title; ?>
<div id="content">

    <a href="/posts">&laquo; Posts</a>
    
    <h1><?=$post->title?></h1>
    <small><?=date('D, F j, Y h:i A', strtotime($post->created_at))?></small>
    
    <div>
    <?=$post->body?>
    </div>
    
    <div id="comments">
    
    <?php if($post->comments) : ?>
    
    <strong>Comments</strong>
    <ul>
    <?php foreach($post->comments as $com) : ?>
    <li><?=$com->name?> says: "<?=$com->body?>"</li>
    <?php endforeach; ?>
    </ul>
    
    <?php endif; ?>
    
    <form method="post" action="/comments">
    <?php include(APP_ROOT . '/views/comments/form.php'); ?>
    </form>
    
    </div>

</div>
<div id="sidebar">

    <a href="/posts/<?=$post->id?>/edit">Edit Post</a>
    <form action="/posts/<?=$post->id?>/delete" id="deletePost" method="post">
    <a href="#" onclick="if(confirm('Are you sure you want to delete this post?')){document.getElementById('deletePost').submit()}">Delete Post</a>
    </form>
</div>