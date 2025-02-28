<?php
/**
 * Enqueue iAPI code needed for the demo.
 */
function enqueue_interactivity_api_store() {
	$assets = include plugin_dir_path( __FILE__ ) . 'build/index.asset.php';
	wp_enqueue_script_module(
		'woocommerce-interactive-product-search',
		// TODO: Use the build file (not working right now).
		plugin_dir_url( __FILE__ ) . 'src/index.js',
		array( '@wordpress/interactivity', '@wordpress/interactivity-router' ),
		$assets['version'],
	);
}
add_action( 'wp_enqueue_scripts', 'enqueue_interactivity_api_store' );
