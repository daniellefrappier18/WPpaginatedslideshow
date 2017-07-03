<?php

$videoId = isset($GLOBALS['slide_resourceUrl']) ? $GLOBALS['slide_resourceUrl'] : '';
$caption = isset($GLOBALS['slide_caption']) ? $GLOBALS['slide_caption'] : '';

?>
<div class="slideshow-wrapper">
	<img src="<?php echo $image; ?>" alt="" />
	<div class="video-container">
		<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $videoId; ?>" frameborder="0" allowfullscreen></iframe>
	</div>
	<p class="slideshow-caption"><?php echo $caption; ?></p>
</div>