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
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	register_post_meta(
		'program',
		'content_sections',
		array(
			'type'         => 'array',
			'single'       => true,
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
			'auth_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
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