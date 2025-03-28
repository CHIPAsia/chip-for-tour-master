<?php

/*
Plugin Name: CHIP for Tour Master
Plugin URI: 
Description: Integrate CHIP with Tour Master
Version: 1.0.0
Author: N/A
Author URI: https://www.google.com
License: 
*/

add_action( 'plugins_loaded', 'ctm_load', 20 );

function ctm_load() {
  define_ctm_static();
  require( CTM_PLUGIN_DIR . '/includes/chip-api.php' );
	require( CTM_PLUGIN_DIR . '/includes/travel-tour-main.php' );
	}

function define_ctm_static() {
  define( 'CTM_MODULE_VERSION', 'v1.0.0' );
  define( 'CTM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
  define( 'CTM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}