import { store, getContext, withSyncEvent } from '@wordpress/interactivity';

const { state } = store( 'university/program-filter', {
	state: {
		selected: 'all',
		loading: false,

		get isHidden() {
			const context = getContext();

			return (
				state.selected !== 'all' &&
				state.selected !== context.type
			);
		},

		get isEmpty() {
			const counts = state.counts || {};

			if ( state.selected === 'all' ) {
				return ( counts.all || 0 ) === 0;
			}

			return ( counts[ state.selected ] || 0 ) === 0;
		},
	},

	actions: {
		filter: withSyncEvent( ( event ) => {
			// Preserve normal browser behavior for modified clicks.
			if (
				event.button !== 0 ||
				event.metaKey ||
				event.ctrlKey ||
				event.shiftKey ||
				event.altKey
			) {
				return;
			}

			event.preventDefault();

			const context = getContext();
			const selected = context.type;
			const url = event.currentTarget.href;

			state.loading = true;

			window.requestAnimationFrame( () => {
				state.selected = selected;
				window.history.pushState(
					{ programType: selected },
					'',
					url
				);
				state.loading = false;
			} );
		} ),

		syncFromUrl() {
			const urls = state.urls || {};
			const currentPath = window.location.pathname.replace( /\/+$/, '' );

			if (
				urls.undergraduate &&
				currentPath === new URL( urls.undergraduate ).pathname.replace( /\/+$/, '' )
			) {
				state.selected = 'undergraduate';
			} else if (
				urls.postgraduate &&
				currentPath === new URL( urls.postgraduate ).pathname.replace( /\/+$/, '' )
			) {
				state.selected = 'postgraduate';
			} else {
				state.selected = 'all';
			}
		},
	},
} );