<?php
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
		$p->set_attribute( 'data-wp-interactive', 'woocommerce/product-collection' );
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
		$p->set_attribute( 'data-wp-bind--value', 'context.queryTerm' );
		$p->set_attribute( 'data-wp-on--input', 'actions.updateSearch' );
	}
	return $p->get_updated_html();
}
add_filter( 'render_block_core/search', 'woodemo_add_directives_to_product_search', 10, 2 );
