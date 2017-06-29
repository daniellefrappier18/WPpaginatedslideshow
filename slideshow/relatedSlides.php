<?php
$relatedQuery = get_RelatedSlideshows( $post );
// define outside of the related post loop, as get_the_ID won't return the original ID
$oldId = $post->ID;	
$count = 1;

if ( $relatedQuery->have_posts() ) {
	?>
	<h2>
		Related Slideshows
	</h2>
	<?php

	while ( $relatedQuery->have_posts() ) {
		$relatedQuery->the_post();
		if ( get_the_ID() !== $oldId && $count < 5 ) {
			?>
			<div class="related-slides col__6-12 col__md-6-12 col__sm-12-12">
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
				<a class="slideshow-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</div>
			<?php
			$count ++;
		}
	}
	wp_reset_postdata();
}
?>