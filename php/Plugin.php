<?php
/**
 * Plugin class.
 *
 * @package SiteCounts
 */

namespace XWP\SiteCounts;

/**
 * WordPress plugin interface.
 */
class Plugin {

	/**
	 * Absolute path to the root directory of this plugin.
	 *
	 * @var string
	 */
	protected $dir;

	/**
	 * Sets up the plugin.
	 *
	 * @param string $plugin_file_path Absolute path to the main plugin file.
	 */
	public function __construct( $plugin_file_path ) {
		$this->dir = dirname( $plugin_file_path );
	}

	/**
	 * Initiates the class.
	 */
	public function init() {
		( new Block( $this ) )->init();
	}

	/**
	 * Gets the absolute path to the plugin directory.
	 *
	 * @return string This plugin's directory.
	 */
	public function dir() {
		return $this->dir;
	}
}
