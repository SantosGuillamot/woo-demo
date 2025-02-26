import { getContext, getElement, store } from '@wordpress/interactivity';

store( 'woocommerce/product-collection', {
	actions: {
		updateSearch( e ) {
			const { value } = e.target;
			const context = getContext();
			context.queryTerm = value;
		},
		*updateQuery( e ) {
			e.preventDefault();
			const {
				'data-wc-query-id': queryId,
				'data-wc-query-term': queryTerm,
			} = getElement().attributes;
			const { actions: routerActions } = yield import(
				'@wordpress/interactivity-router'
			);
			const url = new URL( window.location.href );
			url.searchParams.set( queryId, queryTerm );
			routerActions.navigate( url.href );
		},
	},
} );
