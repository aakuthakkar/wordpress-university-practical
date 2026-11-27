<?php
/**
 * Program archive template.
 *
 * @package University_Theme
 */

get_header();

$program_types = array(
	'undergraduate',
	'postgraduate',
);
?>

<main class="program-archive">
	<h1>All Programmes</h1>

	<?php foreach ( $program_types as $type_slug ) : ?>

		<?php
		$term = get_term_by( 'slug', $type_slug, 'program-type' );

		if ( ! $term || is_wp_error( $term ) ) {
			continue;
		}

		$programs = new WP_Query(
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
		?>

		<section class="program-group">
			<h2><?php echo esc_html( $term->name ); ?></h2>

			<?php if ( $programs->have_posts() ) : ?>
				<div class="program-grid">
					<?php while ( $programs->have_posts() ) : ?>
						<?php
						$programs->the_post();

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
				<p>No programs available in this category yet.</p>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>
		</section>

	<?php endforeach; ?>
</main>

<?php
get_footer();