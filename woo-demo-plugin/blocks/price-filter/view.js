/**
 * WordPress dependencies
 */
import { store, getContext } from '@wordpress/interactivity';

const { state } = store( 'woodemo', {
	state: {
		get maxRange() {
			return state.maxPrice - state.minPrice;
		},
		get minPricePercentage() {
			return ( state.minPriceFilter / state.maxRange ) * 100;
		},
		get maxPricePercentage() {
			return ( 1 - state.maxPriceFilter / state.maxRange ) * 100;
		},
	},
	// Add selector to avoid letting min > max or max < min.
	actions: {
		setMinPrice: ( event ) => {
			let value =
				parseFloat( event.target.value ) || state.minPriceFilter;
			if ( value < state.minPrice ) {
				value = state.minPrice;
			}
			if ( value > state.maxPriceFilter ) {
				value = state.maxPriceFilter;
			}
			state.minPriceFilter = value;
		},
		setMaxPrice: ( event ) => {
			let value =
				parseFloat( event.target.value ) || state.maxPriceFilter;
			if ( value > state.maxPrice ) {
				value = state.maxPrice;
			}
			if ( value < state.minPriceFilter ) {
				value = state.minPriceFilter;
			}
			state.maxPriceFilter = value;
		},
		updateProducts: ( event ) => {
			console.log( 'triggered updateProducts' );
		},
		reset: () => {
			state.minPriceFilter = undefined;
			state.maxPriceFilter = undefined;
		},
	},
	callbacks: {},
} );
