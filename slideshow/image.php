<?php

$image = isset($GLOBALS['slide_resourceUrl']) ? $GLOBALS['slide_resourceUrl'] : '';
$caption = isset($GLOBALS['slide_caption']) ? $GLOBALS['slide_caption'] : '';

?>
<div class="slideshow-wrapper">
	<img src="<?php echo $image; ?>" />
	<p class="slideshow-caption"><?php echo $caption; ?></p>
</div>