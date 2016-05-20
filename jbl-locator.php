<?php

/*
Plugin Name: JBL Locator
Plugin URI: https://github.com/JoelLisenby/jbl-locator
Description: A simple WordPress Google Maps locator plugin
Version: 1.0
Author: Joel Lisenby
Author URI: https://www.joellisenby.com/
Text Domain: jbllocator
*/

namespace JBLLocator;

class JBLLocator {
	
	public function __construct() {
		
		require_once 'Options.php';
		require_once 'PostType.php';
		require_once 'Meta.php';
		require_once 'Shortcode.php';
		
		new \JBLLocator\Options;
		new \JBLLocator\PostType;
		new \JBLLocator\Meta;
		new \JBLLocator\ShortCode;
		
	}
	
}

$jbll = new \JBLLocator\JBLLocator;

?>