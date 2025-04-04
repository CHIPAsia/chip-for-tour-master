<?php
/**
 * Plugin Name: CHIP for Tour Master
 * Plugin URI: https://github.com/CHIPAsia/chip-for-tour-master
 * Description: Integrate CHIP with Tour Master
 * Version: 1.0.0
 * Author: Chip In Sdn Bhd
 * Author URI: https://chip-in.asia
 * License: GNU General Public License v3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Copyright: © 2025 Chip In Sdn Bhd
 *
 * @package Chip for Tour Master
 */

add_action( 'plugins_loaded', 'ctm_load', 20 );

/**
 * Load the plugin.
 *
 * @since 1.0.0
 */
function ctm_load() {
	define_ctm_static();
	require CTM_PLUGIN_DIR . '/includes/class-chip-travel-tour-api.php';
	require CTM_PLUGIN_DIR . '/includes/tour-master-main.php'; // Tour module.
	require CTM_PLUGIN_DIR . '/includes/tour-master-room.php'; // Room module.
}

/**
 * Define constants for the plugin.
 *
 * @since 1.0.0
 */
function define_ctm_static() {
	define( 'CTM_MODULE_VERSION', 'v1.0.0' );
	define( 'CTM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define( 'CTM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
