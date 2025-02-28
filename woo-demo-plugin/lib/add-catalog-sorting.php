<?php
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
		<ul class="wp-block-catalog-sorting" ' . wp_interactivity_data_wp_context( $sorting_list ) . '>
			<template
				data-wp-each--sorting="context.list"
				data-wp-each-key="context.sorting.queryTerm"
			>
				<li>
					<a
						href=""
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
