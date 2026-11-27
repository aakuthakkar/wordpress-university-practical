<?php
/**
 * Program archive with Interactivity API filtering.
 *
 * @package University_Theme
 */

$types = array(
	'undergraduate',
	'postgraduate',
);

$program_queries = array();

$counts = array(
	'all'           => 0,
	'undergraduate' => 0,
	'postgraduate'  => 0,
);

/**
 * Prepare the programs for each type.
 */
foreach ( $types as $type_slug ) {
	$term = get_term_by( 'slug', $type_slug, 'program-type' );

	if ( ! $term || is_wp_error( $term ) ) {
		continue;
	}

	$query = new WP_Query(
		array(
			'post_type'      => 'program',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'tax_query'      => array(
				array(
					'taxonomy' => 'program-type',
					'field'    => 'term_id',
					'terms'    => $term->term_id,
				),
			),
		)
	);

	$program_queries[ $type_slug ] = array(
		'term'  => $term,
		'query' => $query,
	);

	$counts[ $type_slug ] = $query->post_count;
	$counts['all']      += $query->post_count;
}

/*
 * Select the current program type when a category URL loads.
 */
$initial_filter = 'all';

if ( is_tax( 'program-type' ) ) {
	$current_term = get_queried_object();

	if (
		$current_term &&
		isset( $current_term->slug ) &&
		in_array( $current_term->slug, $types, true )
	) {
		$initial_filter = $current_term->slug;
	}
}

$headings = array(
	'all'           => 'All Programmes',
	'undergraduate' => 'Undergraduate Programmes',
	'postgraduate'  => 'Postgraduate Programmes',
);

$all_url = get_post_type_archive_link( 'program' );

$urls = array(
	'all'           => $all_url,
	'undergraduate' => home_url( '/programmes/undergraduate/' ),
	'postgraduate'  => home_url( '/programmes/postgraduate/' ),
);

/**
 * Pass initial data to the Interactivity API store.
 */
wp_interactivity_state(
	'university/program-filter',
	array(
		'selected' => $initial_filter,
		'loading'  => false,
		'counts'   => $counts,
		'urls'     => $urls,
	)
);

get_header();
?>

<main
	class="program-archive"
	data-filter="<?php echo esc_attr( $initial_filter ); ?>"
	data-wp-interactive="university/program-filter"
	data-wp-bind--data-filter="state.selected"
	data-wp-on-window--popstate="actions.syncFromUrl"
>

<h1 data-wp-text="state.heading">
	<?php echo esc_html( $headings[ $initial_filter ] ); ?>
</h1>

	<nav class="program-filter" aria-label="Filter programmes">

	<a
		href="<?php echo esc_url( $urls['all'] ); ?>"
		<?php echo wp_interactivity_data_wp_context( array( 'type' => 'all' ) ); ?>
		data-wp-on--click="actions.filter"
		data-track="<?php echo university_program_track_attr(
			'link',
			'filter-all',
			'programme-listing',
			'componentLink'
		); ?>"
	>
		All
	</a>

	<a
		href="<?php echo esc_url( $urls['undergraduate'] ); ?>"
		<?php echo wp_interactivity_data_wp_context( array( 'type' => 'undergraduate' ) ); ?>
		data-wp-on--click="actions.filter"
		data-track="<?php echo university_program_track_attr(
			'link',
			'filter-undergraduate',
			'programme-listing',
			'componentLink'
		); ?>"
	>
		Undergraduate
	</a>

	<a
		href="<?php echo esc_url( $urls['postgraduate'] ); ?>"
		<?php echo wp_interactivity_data_wp_context( array( 'type' => 'postgraduate' ) ); ?>
		data-wp-on--click="actions.filter"
		data-track="<?php echo university_program_track_attr(
			'link',
			'filter-postgraduate',
			'programme-listing',
			'componentLink'
		); ?>"
	>
		Postgraduate
	</a>

</nav>

	<p
		class="program-filter__loading"
		data-wp-bind--hidden="!state.loading"
		hidden
	>
		Loading programmes…
	</p>

	<p
		class="program-filter__empty"
		data-wp-bind--hidden="!state.isEmpty"
		hidden
	>
		No programmes found for this category.
	</p>

	<?php foreach ( $program_queries as $type_slug => $program_data ) : ?>

		<?php
		$term  = $program_data['term'];
		$query = $program_data['query'];
		?>

		<section
	class="program-group"
	<?php echo wp_interactivity_data_wp_context( array( 'type' => $type_slug ) ); ?>
	data-wp-bind--hidden="state.isHidden"
	<?php if ( 'all' !== $initial_filter && $initial_filter !== $type_slug ) : ?>
		hidden
	<?php endif; ?>
>

			<h2><?php echo esc_html( $term->name ); ?></h2>

			<?php if ( $query->have_posts() ) : ?>

				<div class="program-grid">

					<?php while ( $query->have_posts() ) : ?>
						<?php
						$query->the_post();

						get_template_part(
							'template-parts/program',
							'card',
							array(
								'post_id' => get_the_ID(),
							)
						);
						?>
					<?php endwhile; ?>

				</div>

			<?php else : ?>

				<p>No programmes available in this category yet.</p>

			<?php endif; ?>

		</section>

		<?php wp_reset_postdata(); ?>

	<?php endforeach; ?>

</main>

<?php
get_footer();