<?php
/*This is the template for individual slides*/
get_header();
// display edit post link
echo '<div>';
edit_post_link('Edit'); 
echo '</div>';
?>
<article>
	<div class="back-to-article">
		Back to article:
		<h1><a href="<?php echo get_BackToArticleLink(); ?>"><?php the_title(); ?></a></h1> 
	</div>

	<?php get_template_part('partials/slideshow/slide-nav'); ?>

	<?php displayCurrentSlide(); ?>

	<?php get_template_part('partials/slideshow/slide-nav'); ?>

	<?php

	$allThumbs = get_SlideshowMap();
	foreach ( $allThumbs as $thisThumb ) { ?>
		<a href="<?php echo $thisThumb['url']; ?>"><img class="slideshow-nav-thumbs" src="<?php echo $thisThumb['thumb']; ?>" /></a>
	<?php } ?>
</article>

<?php

get_sidebar();
get_footer(); 