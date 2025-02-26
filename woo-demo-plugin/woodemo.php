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

/**
 * (THIS WON'T BE NEEDED IF THIS IS INTEGRATED IN WOO PRODUCT SEARCH BLOCK)
 * Adapt Product Collection to understand filtering by search terms.
 */
// Filter the query loop to understand the `filter_search` URL parameter.
function gutenberg_block_core_query_add_url_filtering( $query, $block ) {
	$is_product_collection_block = $block->context['query']['isProductCollectionBlock'] ?? false;
	if ( ! $is_product_collection_block ) {
		return $query;
	}
	if ( ! isset( $_GET['filter_search'] ) ) {
		return $query;
	}
	$query['s'] = sanitize_text_field( $_GET['filter_search'] );
	return $query;
}
// This filter needs to run after the one used in WooCommerce.
add_filter( 'query_loop_block_query_vars', 'gutenberg_block_core_query_add_url_filtering', 20, 2 );

// Add directives to the Product Search block to filter by search terms.
function woodemo_add_directives_to_product_search( $block_content, $block ) {
	if ( 'woocommerce/product-search' !== $block['attrs']['namespace'] ) {
		return $block_content;
	}
	$p = new WP_HTML_Tag_Processor( $block_content );
	if ( $p->next_tag( 'form' ) ) {
		$p->set_attribute( 'data-wp-on--submit', 'actions.updateQuery' );
	}
	if ( $p->next_tag( 'input' ) && 'search' === $p->get_attribute( 'type' ) ) {
		// TODO: Add server state based on the URL.
		if ( isset( $_GET['filter_search'] ) ) {
			wp_interactivity_state(
				'woocommerce/product-collection',
				array(
					'searchTerm' => $_GET['filter_search'],
				)
			);
		}

		$p->set_attribute( 'data-wp-bind--value', 'state.searchTerm' );
		$p->set_attribute( 'data-wp-on--input', 'actions.updateSearch' );
		$assets = include plugin_dir_path( __FILE__ ) . 'build/index.asset.php';
		wp_enqueue_script_module(
			'woocommerce-interactive-product-search',
			// TODO: Use the build file (not working right now).
			plugin_dir_url( __FILE__ ) . 'src/index.js',
			array( '@wordpress/interactivity', '@wordpress/interactivity-router' ),
			$assets['version'],
		);
	}
	return $p->get_updated_html();
}
add_filter( 'render_block_core/search', 'woodemo_add_directives_to_product_search', 10, 2 );
