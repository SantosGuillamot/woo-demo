<?php
/**
 * Server-side rendering for the `woodemo/price-filter` block.
 *
 * @package woodemo
 */

// TODO: Get values from query.
$min_price        = 0;
$max_price        = 150;
$min_price_filter = 15;
$max_price_filter = 100;
$max_range        = $max_price - $min_price;
$left_percentage  = 100 * $min_price_filter / $max_range . '%';
$right_percentage = 100 * ( 1 - $max_price_filter / $max_range ) . '%';

wp_interactivity_state(
	'woodemo',
	array(
		'minPrice'           => $min_price,
		'maxPrice'           => $max_price,
		'minPriceFilter'     => $min_price_filter,
		'maxPriceFilter'     => $max_price_filter,
		'minPricePercentage' => $left_percentage,
		'maxPricePercentage' => $right_percentage,
		'maxRange'           => $max_range,
	)
);
?>

<div
	<?php echo get_block_wrapper_attributes(); ?>
	data-wp-interactive="woodemo"
>
	<h3>Filter by price</h3>
	<div class="slider">
		<div
			class="progress"
			data-wp-style--left="state.minPricePercentage"
			data-wp-style--right="state.maxPricePercentage"
		></div>
	</div>
	<div class="range-input">
		<input
			type="range"
			class="range-min"
			data-wp-bind--min='state.minPrice'
			data-wp-bind--max='state.maxPrice'
			data-wp-bind--value='state.minPriceFilter'
			data-wp-on--input='actions.setMinPrice'
		>
		<input
			type="range"
			class="range-max"
			data-wp-bind--min='state.minPrice'
			data-wp-bind--max='state.maxPrice'
			data-wp-bind--value='state.maxPriceFilter'
			data-wp-on--input='actions.setMaxPrice'
		>
	</div>
	<div class='text'>
		<input
			type='text'
			data-wp-bind--value='state.minPriceFilter'
			data-wp-on--change='actions.setMinPrice'
		>
		<input
			type='text'
			data-wp-bind--value='state.maxPriceFilter'
			data-wp-on--change='actions.setMaxPrice'
		>
	</div>
	<button data-wp-on--click='actions.reset'>Reset</button>
</div>
