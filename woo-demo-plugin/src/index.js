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
		*submitReview( e ) {
			e.preventDefault();
			let response;
			const { ref } = getElement();
			const existingReviews = new Set();
			const reviews = ref.closest(
				'.wp-block-woocommerce-product-reviews'
			);
			reviews
				.querySelectorAll( 'li[id^="li-comment-"]' )
				.forEach( ( review ) => existingReviews.add( review.id ) );
			try {
				e.preventDefault();
				response = yield window.fetch( ref.action, {
					method: 'POST',
					body: new window.FormData( ref ),
				} );
			} catch ( e ) {
				// If something fails at this point, the form hasn't been submitted
				// and we can submit it again manually to the server. This is using
				// the prototype because ref.submit() could be overwritten by an
				// `<input name="submit"> element.
				window.HTMLFormElement.prototype.submit.bind( ref )();
			}

			try {
				const html = yield response.text();
				const dom = new window.DOMParser().parseFromString(
					html,
					'text/html'
				);

				if ( response.status !== 200 || dom.body.id === 'error-page' ) {
					console.log(
						dom.querySelector( '.wp-die-message' ).innerText
					);
				} else {
					const router = yield import(
						'@wordpress/interactivity-router'
					);
					yield router.actions.navigate( response.url, {
						html,
						replace: true,
						force: true,
					} );

					let newReview;
					reviews
						.querySelectorAll( 'li[id^="li-comment-"]' )
						.forEach( ( review ) => {
							if ( ! existingReviews.has( review.id ) ) {
								newReview = review;
							}
						} );

					// Scroll the new review into view.
					newReview.scrollIntoView( true );

					// Add hash to the URL.
					window.history.replaceState(
						{},
						'',
						window.location.pathname +
							window.location.search +
							`#${ newReview.id }`
					);
				}
			} catch ( e ) {
				// If something happens at this point, the form has been submitted
				// but we were not able to show it in the screen, so we can just
				// refresh the page.
				window.location.assign( response.url || window.location );
			}
		},
	},
} );
