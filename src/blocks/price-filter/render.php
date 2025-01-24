<?php
/**
 * Server-side rendering for the `woodemo/price-filter` block.
 *
 * @package woodemo
 */

wp_interactivity_state(
	'woodemo',
	array()
);
?>

<div
	<?php echo get_block_wrapper_attributes(); ?>
	data-wp-interactive="woodemo"
>
	<p>
		<?php
			esc_html_e( 'Price Filter', 'woo-demo' );
		?>
	</p>
</div>
