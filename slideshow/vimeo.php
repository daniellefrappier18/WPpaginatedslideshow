<?php

$videoId = isset($GLOBALS['eh_slide_resourceUrl']) ? $GLOBALS['eh_slide_resourceUrl'] : '';
$caption = isset($GLOBALS['eh_slide_caption']) ? $GLOBALS['eh_slide_caption'] : '';

?>
<div class="slideshow-wrapper">
	<img src="<?php echo $image; ?>" alt="" />
	<div class="video-container">
		<iframe src="https://player.vimeo.com/video/<?php echo $videoId; ?>?badge=0" width="640" height="274" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
	</div>
	<p class="slideshow-caption"><?php echo $caption; ?></p>
</div>