<?php

$image = isset($GLOBALS['eh_slide_resourceUrl']) ? $GLOBALS['eh_slide_resourceUrl'] : '';
$caption = isset($GLOBALS['eh_slide_caption']) ? $GLOBALS['eh_slide_caption'] : '';

?>
<div class="slideshow-wrapper">
	<img src="<?php echo $image; ?>" />
	<p class="slideshow-caption"><?php echo $caption; ?></p>
</div>