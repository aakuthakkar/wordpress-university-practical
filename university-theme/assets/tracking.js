/**
 * Send click tracking data without delaying navigation.
 */
document.addEventListener( 'click', function ( event ) {
	if ( ! ( event.target instanceof Element ) ) {
		return;
	}

	const element = event.target.closest( '[data-track]' );

	if ( ! element || ! window.universityTracking?.endpoint ) {
		return;
	}

	let payload;

	try {
		payload = JSON.parse( element.getAttribute( 'data-track' ) );
	} catch ( error ) {
		return;
	}

	const body = JSON.stringify( {
		page_url: window.location.pathname,
		payload: payload,
	} );

	const endpoint = window.universityTracking.endpoint;

	try {
		if ( navigator.sendBeacon ) {
			const blob = new Blob(
				[ body ],
				{ type: 'application/json' }
			);

			if ( navigator.sendBeacon( endpoint, blob ) ) {
				return;
			}
		}

		fetch( endpoint, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: body,
			keepalive: true,
		} ).catch( function () {
			// Tracking must not interrupt the page.
		} );
	} catch ( error ) {
		// Tracking must not interrupt navigation.
	}
} );