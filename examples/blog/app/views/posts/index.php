<?php $pageTitle = "Latest Posts"; ?>
<div id="content">

    <h1>Latest Posts</h1>

    <?php foreach($posts as $post): ?>

    <div class="post">

    <h2><a href="/posts/<?=$post->id?>"><?=$post->title?></a></h2>
    <p><?=$post->summary?></p>

    </div>

    <?php endforeach;?>

</div>

<div id="sidebar">
    
    <a href="/posts/new">New Post</a>
    
</div>