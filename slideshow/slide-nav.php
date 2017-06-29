<div class="slideshow-nav-links">
	<?php 
	if ( get_PrevSlideLink() ) { ?>
		<a class="previous" href="<?php echo get_PrevSlideLink() ?>">Previous</a>
	<?php }
	if ( get_NextSlideLink() ) { ?>
		<a class="next" href="<?php echo get_NextSlideLink() ?>">Next</a>
	<?php } ?>
</div>