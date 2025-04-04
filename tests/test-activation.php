<?php
/**
 * Plugin Activation Test
 */
class ActivationTest extends WP_UnitTestCase {

	public function test_plugin_activation() {
			// Activate the plugin
			activate_plugin( 'chip-for-tour-master/chip-for-tour-master.php' );

			// Check if plugin is active
			$this->assertTrue( is_plugin_active( 'chip-for-tour-master/chip-for-tour-master.php' ) );

			// Add any other activation checks here
	}
}
