import { store } from '@wordpress/interactivity';

const { state } = store( 'woocommerce/product-collection', {
	actions: {
		updateSearch( e ) {
			const { value } = e.target;
			state.searchTerm = value;
		},
		*updateQuery( e ) {
			e.preventDefault();
			const { actions: routerActions } = yield import(
				'@wordpress/interactivity-router'
			);
			const url = new URL( window.location.href );
			url.searchParams.set( 'filter_search', state.searchTerm );
			routerActions.navigate( url.href );
		},
	},
} );
