<?php
/**
 * (THIS WON'T BE NEEDED IF THIS IS INTEGRATED IN THE PRODUCT REVIEWS BLOCK)
 *
 * Adapt Product Reviews block to work in the client.
 */
function woodemo_add_csn_to_product_reviews( $block_content ) {
	wp_enqueue_script_module( 'woocommerce-interactivity-scripts' );
	
	$p = new WP_HTML_Tag_Processor( $block_content );
	if ( $p->next_tag( 'form' ) ) {
		$p->set_attribute( 'data-wp-interactive', 'woocommerce/product-collection' );
		$p->set_attribute( 'data-wp-on--submit', 'actions.submitReview' );
	}
	return $p->get_updated_html();
}
add_filter( 'render_block_woocommerce/product-reviews', 'woodemo_add_csn_to_product_reviews', 10, 1 );
