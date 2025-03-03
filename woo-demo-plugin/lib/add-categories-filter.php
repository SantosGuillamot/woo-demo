<?php
/**
 * (THIS WON'T BE NEEDED IF THIS IS INTEGRATED IN A CATEGORIES FILTER BLOCK)
 *
 * Adapt Product Collection to understand sorting by different alternatives.
 */
// Filter the query loop to understand the `filter_categories` URL parameter.
function gutenberg_block_core_query_add_categories_filtering( $query, $block ) {
	$is_product_collection_block = $block->context['query']['isProductCollectionBlock'] ?? false;
	if ( ! $is_product_collection_block ) {
		return $query;
	}

	if ( ! isset( $_GET['filter_categories'] ) ) {
		return $query;
	}
	$term = get_term( $_GET['filter_categories'] );

	$query['tax_query'] = array(
		array(
			'taxonomy' => $term->taxonomy,
			'field'    => 'id',
			'terms'    => array( $term->term_id ),
		),
	);

	return $query;
}
// This filter needs to run after the one used in WooCommerce.
add_filter( 'query_loop_block_query_vars', 'gutenberg_block_core_query_add_categories_filtering', 20, 2 );

function woodemo_add_categories_filtering( $block_content ) {
	$p = new WP_HTML_Tag_Processor( $block_content );
	if ( $p->next_tag( 'ul' ) ) {
		$p->set_attribute( 'data-wp-interactive', 'woocommerce/product-collection' );
	}
	while ( $p->next_tag( 'li' ) ) {
		$category_id = null;
		foreach ( $p->class_list() as $class_name ) {
			if ( preg_match( '/^cat-item-(\d+)$/', $class_name, $matches ) ) {
				$category_id = $matches[1];
				break;
			}
		}
		if ( $p->next_tag( 'a' ) ) {
			$p->set_attribute( 'href', '' );
			$p->set_attribute( 'data-wc-query-id', 'filter_categories' );
			$p->set_attribute( 'data-wc-query-term', $category_id );
			$p->set_attribute( 'data-wp-on--click', 'actions.updateQuery' );
		}
	}
	return $p->get_updated_html();
}
add_filter( 'render_block_core/categories', 'woodemo_add_categories_filtering', 10, 1 );
