<?php
/**
 * Register REST-enabled program custom fields.
 */
function university_register_program_meta() {
	// Required for post meta to be available through the REST API.
	add_post_type_support( 'program', 'custom-fields' );

	$text_fields = array(
		'academic_year',
		'duration',
	);

	foreach ( $text_fields as $field ) {
		register_post_meta(
			'program',
			$field,
			array(
				'type'              => 'string',
				'single'            => true,
				'default'           => '',
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => 'university_can_edit_program_meta',
			)
		);
	}

	register_post_meta(
		'program',
		'content_sections',
		array(
			'type'         => 'array',
			'single'       => true,
			'sanitize_callback' => 'university_sanitize_program_sections',
			'default'      => array(),
			'show_in_rest' => array(
				'schema' => array(
					'type'  => 'array',
					'items' => array(
						'type'       => 'object',
						'properties' => array(
							'title'       => array(
								'type' => 'string',
							),
							'description' => array(
								'type' => 'string',
							),
							'image'       => array(
								'type' => 'integer',
							),
						),
						'additionalProperties' => false,
					),
				),
			),
			'auth_callback' => 'university_can_edit_program_meta',
		)
	);
}
add_action( 'init', 'university_register_program_meta', 20 );

/**
 * Register the Programs post type and Program Type taxonomy.
 */
function university_register_programs() {
	register_post_type(
		'program',
		array(
			'labels' => array(
				'name'          => 'Programs',
				'singular_name' => 'Program',
				'add_new_item'  => 'Add New Program',
				'edit_item'     => 'Edit Program',
			),
			'public'       => true,
			'show_in_rest' => true,
			'has_archive'  => 'programmes',
			'rewrite'      => array(
				'slug'       => 'programmes',
				'with_front' => false,
			),
			'supports'     => array(
				'title',
				'editor',
				'thumbnail',
			),
			'menu_icon' => 'dashicons-welcome-learn-more',
		)
	);

	register_taxonomy(
		'program-type',
		array( 'program' ),
		array(
			'labels' => array(
				'name'          => 'Program Types',
				'singular_name' => 'Program Type',
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => false,
		)
	);
}
add_action( 'init', 'university_register_programs' );

/**
 * Create the two required program types.
 */
function university_create_program_types() {
	$terms = array(
		'Undergraduate',
		'Postgraduate',
	);

	foreach ( $terms as $term ) {
		if ( ! term_exists( $term, 'program-type' ) ) {
			wp_insert_term( $term, 'program-type' );
		}
	}
}
add_action( 'init', 'university_create_program_types', 20 );


/**
 * Load the custom Program fields in the block editor.
 */
function university_enqueue_program_editor_assets() {
	$screen = get_current_screen();

	if ( ! $screen || 'program' !== $screen->post_type ) {
		return;
	}

	$asset_path = get_template_directory() . '/build/admin.asset.php';

	if ( ! file_exists( $asset_path ) ) {
		return;
	}

	$asset = require $asset_path;

	wp_enqueue_media();

	wp_enqueue_script(
		'university-program-admin',
		get_template_directory_uri() . '/build/admin.js',
		$asset['dependencies'],
		$asset['version'],
		true
	);

	wp_enqueue_style(
		'university-program-admin',
		get_template_directory_uri() . '/build/admin.css',
		array(),
		$asset['version']
	);
}
add_action( 'enqueue_block_editor_assets', 'university_enqueue_program_editor_assets' );


/**
 * Register the two program type archive URLs.
 */
function university_program_type_rewrite_rules() {
	add_rewrite_rule(
		'^programmes/(undergraduate|postgraduate)/?$',
		'index.php?program-type=$matches[1]',
		'top'
	);
}
add_action( 'init', 'university_program_type_rewrite_rules', 20 );

/**
 * Refresh rewrite rules when this theme is activated.
 */
function university_flush_rewrites_on_activation() {
	university_register_programs();
	university_program_type_rewrite_rules();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'university_flush_rewrites_on_activation' );

/**
 * Load the theme stylesheet on the frontend.
 */
function university_enqueue_theme_styles() {
	wp_enqueue_style(
		'university-theme-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'university_enqueue_theme_styles' );

/**
 * Load the HTML design stylesheet.
 */
function university_enqueue_design_styles() {
	$css_path = get_template_directory() . '/assets/style.css';

	if ( file_exists( $css_path ) ) {
		wp_enqueue_style(
			'university-design',
			get_template_directory_uri() . '/assets/style.css',
			array(),
			filemtime( $css_path )
		);
	}
}
add_action( 'wp_enqueue_scripts', 'university_enqueue_design_styles', 20 );
/**
 * Load the website interaction script.
 */
function university_enqueue_design_script() {
	$script_path = get_template_directory() . '/assets/app.js';

	if ( file_exists( $script_path ) ) {
		wp_enqueue_script(
			'university-design-script',
			get_template_directory_uri() . '/assets/app.js',
			array(),
			filemtime( $script_path ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'university_enqueue_design_script' );

/**
 * Enable featured images for the theme.
 */
function university_enable_featured_images() {
	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'university_enable_featured_images' );

/**
 * Load the Interactivity API module on the program archive.
 */
function university_enqueue_program_filter() {
	if (
	! is_post_type_archive( 'program' ) &&
	! is_tax( 'program-type' )
) {
	return;
}

	$script_path = get_template_directory()
		. '/assets/program-filter.js';

	if ( ! file_exists( $script_path ) ) {
		return;
	}

	wp_enqueue_script_module(
		'university-program-filter',
		get_template_directory_uri() . '/assets/program-filter.js',
		array( '@wordpress/interactivity' ),
		filemtime( $script_path )
	);
}
add_action( 'wp_enqueue_scripts', 'university_enqueue_program_filter' );
/**
 * Build an escaped click-tracking payload.
 *
 * @param string $event     Event type: link or button.
 * @param string $label     Short label for the clicked element.
 * @param string $section   Name of the page section.
 * @param string $component Name of the UI component.
 * @return string Escaped JSON suitable for an HTML attribute.
 */
function university_program_track_attr( $event, $label, $section, $component ) {
	$event = sanitize_key( $event );

	if ( ! in_array( $event, array( 'link', 'button' ), true ) ) {
		return '';
	}

	$tracking_data = array(
		'type'         => 'click',
		'event'        => $event,
		$event         => array(
			'label' => sanitize_title( $label ),
		),
		'content_data' => array(
			'sectionName' => sanitize_text_field( $section ),
			'uiComponent' => sanitize_text_field( $component ),
		),
	);

	return esc_attr( wp_json_encode( $tracking_data ) );
}

/**
 * Register the public click-tracking endpoint.
 */
function university_register_tracking_route() {
	register_rest_route(
		'programs/v1',
		'/track',
		array(
			'methods'             => 'POST',
			'callback'            => 'university_save_tracking_event',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'university_register_tracking_route' );

/**
 * Validate and save a tracking event.
 *
 * @param WP_REST_Request $request Incoming request.
 * @return WP_REST_Response|WP_Error
 */
function university_save_tracking_event( $request ) {
	$body = $request->get_body();

	if ( strlen( $body ) > 4096 ) {
		return new WP_Error(
			'tracking_body_too_large',
			'Request body is too large.',
			array( 'status' => 413 )
		);
	}

	$data = json_decode( $body, true );

	if ( JSON_ERROR_NONE !== json_last_error() || ! is_array( $data ) ) {
		return new WP_Error(
			'tracking_invalid_json',
			'Invalid JSON.',
			array( 'status' => 400 )
		);
	}

	$payload  = isset( $data['payload'] ) ? $data['payload'] : null;
	$page_url = isset( $data['page_url'] ) ? $data['page_url'] : null;

	if ( ! is_array( $payload ) || ! is_string( $page_url ) ) {
		return new WP_Error(
			'tracking_invalid_request',
			'Invalid tracking request.',
			array( 'status' => 400 )
		);
	}

	$type  = isset( $payload['type'] ) ? $payload['type'] : '';
	$event = isset( $payload['event'] ) ? $payload['event'] : '';

	if (
		'click' !== $type ||
		! is_string( $event ) ||
		! in_array( $event, array( 'link', 'button' ), true )
	) {
		return new WP_Error(
			'tracking_invalid_event',
			'Invalid event.',
			array( 'status' => 400 )
		);
	}

	$event_data = isset( $payload[ $event ] )
		? $payload[ $event ]
		: null;

	$content_data = isset( $payload['content_data'] )
		? $payload['content_data']
		: null;

	if ( ! is_array( $event_data ) || ! is_array( $content_data ) ) {
		return new WP_Error(
			'tracking_invalid_fields',
			'Missing event fields.',
			array( 'status' => 400 )
		);
	}

	$label = isset( $event_data['label'] )
		? $event_data['label']
		: '';

	$section = isset( $content_data['sectionName'] )
		? $content_data['sectionName']
		: '';

	$component = isset( $content_data['uiComponent'] )
		? $content_data['uiComponent']
		: '';

	/*
	 * Allow only short, predictable values.
	 * Unknown payload keys are ignored below.
	 */
	if (
		! is_string( $label ) ||
		! preg_match( '/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $label ) ||
		strlen( $label ) > 60 ||
		! is_string( $section ) ||
		! preg_match( '/^[a-zA-Z0-9_-]{1,60}$/', $section ) ||
		! is_string( $component ) ||
		! preg_match( '/^[a-zA-Z0-9_-]{1,60}$/', $component )
	) {
		return new WP_Error(
			'tracking_invalid_values',
			'Invalid tracking values.',
			array( 'status' => 400 )
		);
	}

	/*
	 * Store only a URL path, without query strings or fragments.
	 */
	$path = wp_parse_url( $page_url, PHP_URL_PATH );

	if (
		! is_string( $path ) ||
		! preg_match( '#^/[a-zA-Z0-9/_%-]{0,250}$#', $path )
	) {
		return new WP_Error(
			'tracking_invalid_path',
			'Invalid page path.',
			array( 'status' => 400 )
		);
	}

	/*
	 * Simple best-effort rate limit. The IP address is hashed
	 * for the temporary counter and is not written to the log.
	 */
	$remote_address = isset( $_SERVER['REMOTE_ADDR'] )
		? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) )
		: '';

	$rate_key = 'univ_track_' . substr(
		wp_hash( $remote_address ),
		0,
		32
	);

	$request_count = (int) get_transient( $rate_key );

	if ( $request_count >= 60 ) {
		return new WP_Error(
			'tracking_rate_limited',
			'Too many tracking requests.',
			array( 'status' => 429 )
		);
	}

	set_transient(
		$rate_key,
		$request_count + 1,
		MINUTE_IN_SECONDS
	);

	/*
	 * Rebuild the payload using only approved fields.
	 */
	$clean_payload = array(
		'type'         => 'click',
		'event'        => $event,
		$event         => array(
			'label' => sanitize_title( $label ),
		),
		'content_data' => array(
			'sectionName' => sanitize_text_field( $section ),
			'uiComponent' => sanitize_text_field( $component ),
		),
	);

	$uploads = wp_upload_dir();

	if ( ! empty( $uploads['error'] ) ) {
		return new WP_Error(
			'tracking_upload_error',
			'Tracking storage is unavailable.',
			array( 'status' => 500 )
		);
	}

	$logs_root = trailingslashit( $uploads['basedir'] ) . 'logs';

	/*
	 * One data-track.log per day:
	 * uploads/logs/YYYY-MM-DD/data-track.log
	 */
	$default_path = trailingslashit( $logs_root )
		. gmdate( 'Y-m-d' )
		. '/data-track.log';

	$log_path = apply_filters(
		'university_tracking_log_path',
		$default_path
	);

	if (
		! is_string( $log_path ) ||
		! wp_mkdir_p( dirname( $log_path ) )
	) {
		return new WP_Error(
			'tracking_directory_error',
			'Tracking directory could not be created.',
			array( 'status' => 500 )
		);
	}

	/*
	 * Apache protection for the default uploads/logs directory.
	 * The server must still be tested to confirm access is denied.
	 */
	$deny_file  = trailingslashit( $logs_root ) . '.htaccess';
	$index_file = trailingslashit( $logs_root ) . 'index.php';

	if ( ! file_exists( $deny_file ) ) {
		$deny_rules = "<IfModule mod_authz_core.c>\n"
			. "Require all denied\n"
			. "</IfModule>\n"
			. "<IfModule !mod_authz_core.c>\n"
			. "Deny from all\n"
			. "</IfModule>\n";

		if ( false === file_put_contents( $deny_file, $deny_rules, LOCK_EX ) ) {
			return new WP_Error(
				'tracking_protection_error',
				'Tracking protection could not be created.',
				array( 'status' => 500 )
			);
		}
	}

	if ( ! file_exists( $index_file ) ) {
		if ( false === file_put_contents(
			$index_file,
			"<?php\n// Silence is golden.\n",
			LOCK_EX
		) ) {
			return new WP_Error(
				'tracking_index_error',
				'Tracking protection could not be created.',
				array( 'status' => 500 )
			);
		}
	}

	$entry = array(
		'timestamp' => gmdate( 'c' ),
		'page_url'  => $path,
		'payload'   => $clean_payload,
	);

	$line = wp_json_encode( $entry );

	if ( false === $line ) {
		return new WP_Error(
			'tracking_encode_error',
			'Tracking event could not be encoded.',
			array( 'status' => 500 )
		);
	}

	$result = file_put_contents(
		$log_path,
		$line . PHP_EOL,
		FILE_APPEND | LOCK_EX
	);

	if ( false === $result ) {
		return new WP_Error(
			'tracking_write_error',
			'Tracking event could not be saved.',
			array( 'status' => 500 )
		);
	}

	return new WP_REST_Response(
		array( 'success' => true ),
		200
	);
}
/**
 * Load frontend click tracking.
 */
function university_enqueue_tracking_script() {
	$script_path = get_template_directory()
		. '/assets/tracking.js';

	if ( ! file_exists( $script_path ) ) {
		return;
	}

	wp_enqueue_script(
		'university-tracking',
		get_template_directory_uri() . '/assets/tracking.js',
		array(),
		filemtime( $script_path ),
		true
	);

	$settings = array(
		'endpoint' => esc_url_raw(
			rest_url( 'programs/v1/track' )
		),
	);

	wp_add_inline_script(
		'university-tracking',
		'window.universityTracking = ' .
		wp_json_encode( $settings ) .
		';',
		'before'
	);
}
add_action( 'wp_enqueue_scripts', 'university_enqueue_tracking_script' );
/**
 * Sanitize the program content sections before saving.
 *
 * @param mixed $sections Submitted section data.
 * @return array Sanitized sections.
 */
function university_sanitize_program_sections( $sections ) {
	if ( ! is_array( $sections ) ) {
		return array();
	}

	$clean_sections = array();

	foreach ( $sections as $section ) {
		if ( ! is_array( $section ) ) {
			continue;
		}

		$title = isset( $section['title'] ) && is_string( $section['title'] )
			? sanitize_text_field( $section['title'] )
			: '';

		$description = isset( $section['description'] ) && is_string( $section['description'] )
			? sanitize_textarea_field( $section['description'] )
			: '';

		$image_id = isset( $section['image'] )
			? absint( $section['image'] )
			: 0;

		$clean_sections[] = array(
			'title'       => $title,
			'description' => $description,
			'image'       => $image_id,
		);
	}

	return $clean_sections;
}

/**
 * Check whether the current user can edit the specific program.
 *
 * @param bool   $allowed  Existing permission value.
 * @param string $meta_key Meta field name.
 * @param int    $post_id  Program ID.
 * @param int    $user_id  User requesting access.
 * @return bool
 */
function university_can_edit_program_meta( $allowed, $meta_key, $post_id, $user_id ) {
	return user_can( $user_id, 'edit_post', $post_id );
}
