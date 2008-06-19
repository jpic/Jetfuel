<p>
<label for="comment_name">Name</label><br />
<input type="text" name="comment[name]" id="comment_name" value="<?=$comment->name?>" />
<?php errorMessage($comment, 'name'); ?>
</p>

<p>
<label for="comment_email">Email</label><br />
<input type="text" name="comment[email]" id="comment_email" value="<?=$comment->email?>" />
<?php errorMessage($comment, 'email'); ?>
</p>

<p>
<label for="comment_body">Comment</label><br />
<textarea name="comment[body]" id="comment_body"><?=$comment->body?></textarea>
<?php errorMessage($comment, 'body'); ?>
</p>

<?php if($post) : ?>
<input type="hidden" name="comment[post_id]" value="<?=$post->id?>" />
<?php else : ?>
<input type="hidden" name="comment[post_id]" value="<?=$comment->post_id?>" />
<?php endif; ?>

<input type="submit" value="Comment" />