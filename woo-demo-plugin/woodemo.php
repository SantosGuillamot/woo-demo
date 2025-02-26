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
		$form_context = array(
			'queryId' => 'filter_search',
		);
		if ( isset( $_GET['filter_search'] ) ) {
			$form_context['queryTerm'] = sanitize_text_field( $_GET['filter_search'] );
		}
		$p->set_attribute( 'data-wp-context', json_encode( $form_context ) );
		$p->set_attribute( 'data-wp-bind--data-wc-query-id', 'context.queryId' );
		$p->set_attribute( 'data-wp-bind--data-wc-query-term', 'context.queryTerm' );
		$p->set_attribute( 'data-wp-on--submit', 'actions.updateQuery' );
	}
	if ( $p->next_tag( 'input' ) && 'search' === $p->get_attribute( 'type' ) ) {
		// TODO: Add server state based on the URL.

		$p->set_attribute( 'data-wp-bind--value', 'context.queryTerm' );
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

/**
 * (THIS WON'T BE NEEDED IF THIS IS INTEGRATED IN WOO CATALOG SORTING)
 *
 * Adapt Product Collection to understand sorting by different alternatives.
 */
// Filter the query loop to understand the `sort_by` URL parameter.
function gutenberg_block_core_query_add_catalog_sorting( $query, $block ) {
	$is_product_collection_block = $block->context['query']['isProductCollectionBlock'] ?? false;
	if ( ! $is_product_collection_block ) {
		return $query;
	}

	if ( ! isset( $_GET['sort_by'] ) ) {
		return $query;
	}

	switch ( $_GET['sort_by'] ) {
		case 'relevance':
			$query['orderby'] = 'relevance';
			$query['order']   = 'DESC';
			break;
		case 'lowest-price':
			$query['orderby']  = 'meta_value_num';
			$query['meta_key'] = '_price';
			$query['order']    = 'ASC';
			break;
		case 'highest-price':
			$query['orderby']  = 'meta_value_num';
			$query['meta_key'] = '_price';
			$query['order']    = 'DESC';
			break;
		case 'sales':
			$query['orderby']  = 'meta_value_num';
			$query['meta_key'] = 'total_sales';
			$query['order']    = 'DESC';
			break;
		case 'rating':
			$query['orderby']  = 'meta_value_num';
			$query['meta_key'] = '_wc_average_rating';
			$query['order']    = 'DESC';
			break;
	}
	return $query;
}
// This filter needs to run after the one used in WooCommerce.
add_filter( 'query_loop_block_query_vars', 'gutenberg_block_core_query_add_catalog_sorting', 20, 2 );

function woodemo_catalog_sorting() {
	$sorting_list = array(
		'queryId' => 'sort_by',
		'list'    => array(
			array(
				'queryTerm' => 'relevance',
				'label'     => 'Relevance',
			),
			array(
				'queryTerm' => 'lowest-price',
				'label'     => 'Lowest price',
			),
			array(
				'queryTerm' => 'highest-price',
				'label'     => 'Highest price',
			),
			array(
				'queryTerm' => 'sales',
				'label'     => 'Sales',
			),
			array(
				'queryTerm' => 'rating',
				'label'     => 'Rating',
			),
		),
	);
	return '
		<ul ' . wp_interactivity_data_wp_context( $sorting_list ) . '>
			<template
				data-wp-each--sorting="context.list"
				data-wp-each-key="context.sorting.queryTerm"
			>
				<li>
					<a
						data-wp-text="context.sorting.label"
						data-wp-bind--data-wc-query-id="context.queryId"
						data-wp-bind--data-wc-query-term="context.sorting.queryTerm"
						data-wp-on--click="actions.updateQuery"
					></a>
				</li>
			</template>
		</ul>
	';
}
add_filter( 'render_block_woocommerce/catalog-sorting', 'woodemo_catalog_sorting', 10 );
