<?php $pageTitle = "New Post"; ?>
<h1>New Post</h1>
<form action="/posts" method="POST">
<?php include(APP_ROOT . '/views/posts/form.php'); ?>
or <a href="/posts">Cancel</a>
</form>