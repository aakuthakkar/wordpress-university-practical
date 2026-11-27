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