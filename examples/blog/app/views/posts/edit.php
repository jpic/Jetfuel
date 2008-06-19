<?php $pageTitle = $post->title; ?>
<h1>Edit '<?=$post->title?>'</h1>
<form action="/posts/<?=$post->id?>" method="POST">
<?php include(APP_ROOT . '/views/posts/form.php'); ?>
or <a href="/posts/<?=$post->id?>">Cancel</a>
</form>