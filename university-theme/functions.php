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