/**
 * Register the Programs post type and Program Type taxonomy.
 */
function university_register_programs() {
	register_post_type(
		'program',
		array(
			'labels'       => array(
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
			'menu_icon'    => 'dashicons-welcome-learn-more',
		)
	);

	register_taxonomy(
		'program-type',
		array( 'program' ),
		array(
			'labels'            => array(
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
 * Create the required program types if they do not exist.
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