<?php if (isset($twitter->error)) { ?>
<div class="twitter-error">
Twitter is over capacity.<br />
Too many tweets! Please wait a moment and try again.
</div>
<?php } else { ?>
<?php if ( !is_array($twitter->results) || count($twitter->results)<=0 ) { ?>
<div class="ajax_loading">
コメントがありません。
</div>
<?php } else { ?>
<?php foreach ($twitter->results as $tweet) { ?>
<div class="twitter-post">
	<div class="twitter-post-image">
		<?php echo $html->link(
				$html->image($tweet->profile_image_url, array('alt'=> $tweet->from_user, 'border'=>"0")),
				"http://twitter.com/{$tweet->from_user}",
				array('class' => 'author', 'target' => '_blank'),
				false,false
			) ;?>
	</div>
	<div class="twitter-post-body">
		<?php echo $html->link(
			$tweet->from_user,
			'http://twitter.com/' . $tweet->from_user,
			array('class' => 'author', 'target' => '_blank')
		); ?><br />
		<?php echo ($tweet->text); ?><br />
		(<?php echo $html->link(
			$time->relativeTime($tweet->created_at),
			"http://twitter.com/{$tweet->from_user}/status/{$tweet->id}",
			array('class' => 'timestamp', 'target' => '_blank')
		); ?>)<br style="clear: both;" />
	</div>
</div>
<?php } ?>
<?php } ?>
<?php } ?>
