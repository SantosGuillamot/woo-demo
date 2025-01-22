<?php
/**
 * Plugin Name:       Woo Demo
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Description:       Plugin that demoes the usage of the Interactivity API in Woo.
 * Author:            Automattic
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       woo-demo
 * Requires Plugins:  woocommerce
 */
function extend_query_loop_for_product_post_type( $query ) {
	if ( ! is_admin() && $query->is_main_query() ) {
		// Always set the post type to 'product'
		$query->set( 'post_type', 'product' );
	}
}
add_action( 'pre_get_posts', 'extend_query_loop_for_product_post_type' );
