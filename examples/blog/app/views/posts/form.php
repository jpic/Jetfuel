<p>
<label for="post_title">Title</label><br />
<input type="text" name="post[title]" id="post_title" value="<?=$post->title?>" />
<?=errorMessage($post,'title')?>
</p>

<p>
<label for="post_summary">Summary</label><br />
<textarea name="post[summary]" id="post_summary"><?=$post->summary?></textarea>
<?=errorMessage($post,'summary')?>
</p>

<p>
<label for="post_body">Body</label><br />
<textarea name="post[body]" id="post_sbody"><?=$post->body?></textarea>
<?=errorMessage($post,'body')?>
</p>

<input type="submit" value="Save" />
