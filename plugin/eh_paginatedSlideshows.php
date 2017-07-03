<?php
/*
Plugin Name: Paginated Slideshows
Plugin URI: http://www.commercialintegrator.com/
Description: Paginated slideshows for WordPress!

NOTES:
To use, install & activate plugin.
You will then need to create a new WordPress template that handles all individual "slides" - slideshow.php




/*
Activation / Deactivation
*/
	/*
	on activation, copy the ACF file into the current theme's acf-json folder, creating it if necesssary
	also, we need to flush rewrite rules to avoid /slideshow/ not returning anything. this plugin adjusts them, so we need to flush rules for this to take effect.
	*/
	function Slides_Activate()
	{
		//Slides_MoveAcfFile();
		flush_rewrite_rules();
	}
	/*
	on deactivation, remove the ACF file from the current thtme's acf-json folder.
	flush rewrite rules, as we will no longer be interpreting /slideshow/[0-9]+ as anything.
	*/
	function Slides_Deactivate()
	{
		//Slides_RemoveAcfFile();
		flush_rewrite_rules();
	}

	// this will delete the ACF file from the template directory - the file will remain intact in the plugin folder
	function Slides_RemoveAcfFile()
	{
		$pluginAcfFileName = 'group_580a5c473e2b2.json';
		$themeDir = get_template_directory();
		$acfFolder = $themeDir . '/acf-json';

		unlink ($acfFolder . '/' . $pluginAcfFileName );
	}
	// this copies the ACF file from the plugin folder into the currently active theme directory, creating the folder if necessary
	function Slides_MoveAcfFile()
	{
		$pluginAcfFileName = 'group_580a5c473e2b2.json';
		$pluginAcfFile = ABSPATH . '/wp-content/plugins/paginatedSlideshows/acf-json/' . $pluginAcfFileName;

		$themeDir = get_template_directory();
		$acfFolder = $themeDir . '/acf-json';

		if ( is_dir( $acfFolder ) !== true )
		{
			mkdir( $acfFolder );
		}

		if ( file_exists( $pluginAcfFile ) )
		{
			copy( $pluginAcfFile, $acfFolder . '/' . $pluginAcfFileName );
		}
		else
		{
			die('ACF JSON file is missing from plugin folder!');
		}
	}

	register_activation_hook( __FILE__, 'Slides_Activate');
	register_deactivation_hook( __FILE__, 'Slides_Deactivate');
/*
Slide thumbnail navigation
*/


/*
URL rewrite rules and template (slideshow.php) inclusion
*/
	// tell WordPress to use /slideshow/ as a url segment
	function slideshow_endpoint()
	{
		add_rewrite_endpoint('slideshow', EP_PERMALINK | EP_PAGES );
	}
	add_action('init', 'slideshow_endpoint');

	// register slideshow.php as a WordPress template
	function include_slideshow_template( $template )
	{
		
		$slideshowQueryVar = get_query_var('slideshow', false);
		$isValid = ctype_digit(  $slideshowQueryVar );

		if ( false === $slideshowQueryVar || !$isValid )
		{
			return $template;
		}

		return get_query_template('slideshow');
	}
	add_filter('template_include','include_slideshow_template');

	// tell WordPress to use the value after /slideshow/ (ex. /slideshow/4) as the index for this slide in the ACF slideshow array
	function rewrite_slideshow_tag()
	{
		add_rewrite_tag('slideshow','([0-9]+)');
	}
	add_action( 'init', 'rewrite_slideshow_tag' );
