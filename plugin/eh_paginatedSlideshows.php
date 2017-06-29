<?php
/*
Plugin Name: EH Paginated Slideshows
Plugin URI: http://www.commercialintegrator.com/
Description: Paginated slideshows for WordPress! A new pageload for each slide in a slideshow, that can be related to a particular post or page.
Version: 1.0
Author: John Brillon, Guy Caiola, Danielle Frappier, Allison Shapanka, Pete Smith, Keri Whoriskey, Zack Worden

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
	function EH_Slides_Activate()
	{
		//EH_Slides_MoveAcfFile();
		flush_rewrite_rules();
	}
	/*
	on deactivation, remove the ACF file from the current thtme's acf-json folder.
	flush rewrite rules, as we will no longer be interpreting /slideshow/[0-9]+ as anything.
	*/
	function EH_Slides_Deactivate()
	{
		//EH_Slides_RemoveAcfFile();
		flush_rewrite_rules();
	}

	// this will delete the ACF file from the template directory - the file will remain intact in the plugin folder
	function EH_Slides_RemoveAcfFile()
	{
		$pluginAcfFileName = 'group_580a5c473e2b2.json';
		$themeDir = get_template_directory();
		$acfFolder = $themeDir . '/acf-json';

		unlink ($acfFolder . '/' . $pluginAcfFileName );
	}
	// this copies the ACF file from the plugin folder into the currently active theme directory, creating the folder if necessary
	function EH_Slides_MoveAcfFile()
	{
		$pluginAcfFileName = 'group_580a5c473e2b2.json';
		$pluginAcfFile = ABSPATH . '/wp-content/plugins/eh_paginatedSlideshows/acf-json/' . $pluginAcfFileName;

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

	register_activation_hook( __FILE__, 'EH_Slides_Activate');
	register_deactivation_hook( __FILE__, 'EH_Slides_Deactivate');
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
