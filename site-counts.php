<?php
/**
 * Plugin Name: Site Counts
 * Description: Post and term counts for your WordPress site.
 * Version: 1.0.0
 * Author: XWP
 * Author URI: https://github.com/xwp/site-counts
 * Text Domain: site-counts
 *
 * @package SiteCounts
 */

namespace XWP\SiteCounts;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

add_action( 'plugins_loaded', [ new Plugin( __FILE__ ), 'init' ] );
