<?php
/**
 * Tests for Plugin class.
 *
 * @package SiteCounts
 */

namespace XWP\SiteCounts;

/**
 * Test the WordPress plugin abstraction.
 */
class TestPlugin extends TestCase {

	/**
	 * Test dir.
	 *
	 * @covers \XWP\SiteCounts\Plugin::__construct()
	 * @covers \XWP\SiteCounts\Plugin::dir()
	 */
	public function test_dir() {
		$plugin = new Plugin( '/absolute/path/to/plugin.php' );
		$this->assertEquals( '/absolute/path/to', $plugin->dir() );
	}
}
