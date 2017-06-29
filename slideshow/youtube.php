<?php

$videoId = isset($GLOBALS['eh_slide_resourceUrl']) ? $GLOBALS['eh_slide_resourceUrl'] : '';
$caption = isset($GLOBALS['eh_slide_caption']) ? $GLOBALS['eh_slide_caption'] : '';

?>
<div class="slideshow-wrapper">
	<img src="<?php echo $image; ?>" alt="" />
	<div class="video-container">
		<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $videoId; ?>" frameborder="0" allowfullscreen></iframe>
	</div>
	<p class="slideshow-caption"><?php echo $caption; ?></p>
</div>