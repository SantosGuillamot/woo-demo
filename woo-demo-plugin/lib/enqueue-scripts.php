<?php
/**
 * Enqueue iAPI code needed for the demo.
 */
$assets = include plugin_dir_path( __DIR__ ) . 'build/index.asset.php';
wp_register_script_module(
	'woocommerce-interactivity-scripts',
	// TODO: Use the build file (not working right now).
		plugin_dir_url( __DIR__ ) . 'src/index.js',
	array( '@wordpress/interactivity', '@wordpress/interactivity-router' ),
	$assets['version'],
);
