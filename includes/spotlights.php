<?php

/*
Slideshow / Template Interface

the slideshow plugin contains rewrite rules, but this contains interface functions needed for templating
*/


// handle slide rendering logic. Determine the type of slide, and pass data on to the appropriate template partial
	function displayCurrentSlide()
	{
		$slide = get_CurrentSlide();
		$slideType = get_SlideType($slide);

		if ( $slide )
		{
			if ( $slideType == 'image' )
			{
				$GLOBALS['slide_resourceUrl'] = get_SlideImage( $slide, 'large');
				$GLOBALS['slide_caption'] = get_SlideCaption($slide);

				get_template_part('partials/slideshow/image');
			}
			else if ( $slideType == 'youtube' )
			{
				$GLOBALS['slide_resourceUrl'] = get_SlideVideoId( $slide );
				$GLOBALS['slide_caption'] = get_SlideCaption($slide);

				get_template_part('partials/slideshow/youtube');
			}
			else if ( $slideType == 'vimeo' )
			{
				$GLOBALS['slide_resourceUrl'] = get_SlideVideoId( $slide );
				$GLOBALS['slide_caption'] = get_SlideCaption($slide);

				get_template_part('partials/slideshow/vimeo');
			}
		}
		else
		{
			get_template_part('partials/slideshow/relatedSlides');
		}
	}

// get an array of thumbnails and URLs for all slides in this slideshow (for the mini-nav)
	function get_SlideshowMap()
	{
		$slideshow = get_SlideshowField();
		$thisItem = array();
		$map = array();
		$counter = 0;
		$numOf = count( $slideshow );

		for ( $counter = 0; $counter < $numOf; $counter ++ )
		{
			$thisItem = array();
			$thisItem['url'] = get_permalink() . 'slideshow/' . $counter;
			$thisItem['thumb'] = get_SlideThumbnail( $slideshow[$counter], 'thumbnail', FALSE );

			array_push( $map, $thisItem );
		}

		return $map;
	}
// get two graphics to use as a preview of the slideshow
	function get_SlideshowPreviewThumbs()
	{
		$genericPlaceholder = get_stylesheet_directory_uri() . '/assets/fallbacks/no-slide.jpg';
		$slideshow = get_SlideshowField();
		$thumbs = array();
		$counter = 0;
		$numOf = count( $slideshow );

		for ( $counter = 0; $counter < $numOf; $counter ++ )
		{
			$thisSlide = get_SlideThumbnail( $slideshow[$counter], 'slideshow-preview', TRUE );

			if ( $thisSlide )
			{
				array_push( $thumbs, $thisSlide );
			}

			// check to see if we have enough thumbnails
			if ( count($thumbs) >= 2 )
			{
				break;
			}
		}

		// do a check to make sure we have at least 2 thumbnails. If not, add fake thumbnails until we have 2
		$numOf = count( $thumbs );

		if ( $numOf < 2 )
		{
			$numOf = 2 - $numOf;

			for ( $counter = 0; $counter < $numOf; $counter ++ )
			{
				array_push( $thumbs, $genericPlaceholder );
			}
		}

		return $thumbs;
	}


// determine if this slide is a valid, editor-entered slide, or if the index number is out of bounds
	function get_IsThisSlideValid()
	{
		$allSlides = get_SlideshowField();
		$slideId = get_query_var('slideshow', 0);

		if ( isset( $allSlides[$slideId] ) )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

// return the 'begin slideshow' link
	function get_BeginSlideshowLink()
	{
		if ( get_DoesSlideshowExist() === TRUE )
		{
			$slideId = set_query_var('slideshow',0);
			$link = add_query_arg('slideshow', $slideId, get_permalink() );
			$link .= 'slideshow/0/';

			return $link;
		}
		else
		{
			return NULL;
		}
	}
// return the 'next slide' link
	function get_NextSlideLink()
	{
		$allSlides = get_SlideshowField();
		$slideId = get_query_var('slideshow', 0);

		// does another slide exist in this slideshow
		if ( isset( $allSlides[$slideId + 1] ) == TRUE )
		{
			return get_permalink() . 'slideshow/' . ($slideId + 1) ;
		}
		// is this the last true slide in the slideshow (if so, the 'next' link should still appear )
		else if ( isset( $allSlides[$slideId + 1] ) == FALSE && isset( $allSlides[$slideId] ) == TRUE )
		{
			return get_permalink() . 'slideshow/' . ($slideId + 1);
		}
		// else we somehow ended up on an invalid slide ID that is NOT contiguous to the rest of the slideshow
		else
		{
			return NULL;
		}
	}
// return the 'previous slide' link
	function get_PrevSlideLink()
	{
		$allSlides = get_SlideshowField();
		$slideId = get_query_var('slideshow', 0);

		// does a previous slide exist in this slideshow
		if ( isset( $allSlides[$slideId - 1] ) == TRUE )
		{
			return get_permalink() . 'slideshow/' . ($slideId - 1) ;
		}
		// else we somehow ended up on an invalid slide ID that is NOT contiguous to the rest of the slideshow
		else
		{
			return NULL;
		}
	}
// return the 'back to article' link
	function get_BackToArticleLink()
	{
		
		return get_permalink();
	}
// check if there is a slideshow attached to the current $post
	function get_DoesSlideshowExist()
	{
		if ( get_SlideshowField() )
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
// private-ish. return the acf slideshow field
	function get_SlideshowField()
	{
		$slideshow = get_field('slideshow');

		if ( $slideshow )
		{
			return $slideshow;
		}
		else
		{
			return NULL;
		}
	}

// private-ish. return the current slide
	function get_CurrentSlide()
	{
		$allSlides = get_SlideshowField();
		$slideId = get_query_var('slideshow', 0);

		if ( isset( $allSlides[$slideId]['acf_fc_layout'] ) )
		{
			return $allSlides[$slideId];
		}
		else
		{
			return NULL;
		}
	}
// private-ish. get the slide type of the slide
	function get_SlideType( $slide )
	{
		if ( $slide['acf_fc_layout'] == 'slide_image' )
		{
			return 'image';
		}
		else if ( $slide['acf_fc_layout'] == 'slide_video' )
		{
			if ( $slide['youtube_or_vimeo'] == 'youtube' )
			{
				return 'youtube';
			}
			else if ( $slide['youtube_or_vimeo'] == 'vimeo' )
			{
				return 'vimeo';
			}
		}

		return NULL;
	}
// private-ish. get the caption of the slide
	function get_SlideCaption( $slide )
	{
		if ( isset( $slide['slideshow_caption'] ) )
		{
			return $slide['slideshow_caption'];
		}
		else
		{
			return NULL;
		}
	}
// private-ish. get the image (with an optional size) of the slide
	function get_SlideImage( $slide, $optionalSize = NULL )
	{
		if ( isset($slide['slideshow_image']) )
		{
			if ( $optionalSize !== NULL )
			{
				if ( isset($slide['slideshow_image']['sizes'][$optionalSize]) )
				{
					return $slide['slideshow_image']['sizes'][$optionalSize];
				}
				else
				{
					return NULL;
				}
			}
			else
			{
				return $slide['slideshow_image'];	
			}
		}
		else
		{
			return NULL;
		}
	}
// private-ish. get the video ID of the slide
	function get_SlideVideoId( $slide )
	{
		if ( isset($slide['slideshow_video']) )
		{
			return $slide['slideshow_video'];
		}
		else
		{
			return NULL;
		}
	}
// private-ish. get the thumbnail of the slide
function get_SlideThumbnail( $slide, $thumbnailSize, $isPreview )
{
	$vimeoPlaceholderSmall = get_stylesheet_directory_uri() . '/assets/fallbacks/vimeo-small.jpg';
	$vimeoPlaceholderBig = get_stylesheet_directory_uri() . '/assets/fallbacks/vimeo-big.jpg';

	if ( $slide['acf_fc_layout'] == 'slide_image' )
	{
		return $slide['slideshow_image']['sizes'][$thumbnailSize];
	}
	else if ( $slide['acf_fc_layout'] == 'slide_video' )
	{
		if ( $slide['youtube_or_vimeo'] == 'youtube' )
		{
			if ( $isPreview === TRUE )
			{
				return 'http://img.youtube.com/vi/' . $slide['slideshow_video']  . '/0.jpg';
			}
			else
			{
				return 'http://img.youtube.com/vi/' . $slide['slideshow_video']  . '/1.jpg';
			}
			
		}
		else if ( $slide['youtube_or_vimeo'] == 'vimeo' )
		{
			if ( $isPreview === TRUE )
			{
				return $vimeoPlaceholderBig;
			}
			else
			{
				return $vimeoPlaceholderSmall;
			}
			
		}
	}
}