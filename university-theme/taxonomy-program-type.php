<?php
/**
 * Program type archive template.
 *
 * @package University_Theme
 */

get_header();

$current_term = get_queried_object();
?>

<main>

	<section class="page-banner">
		<h1>
			<?php echo esc_html( $current_term->name ); ?>
			Programmes
		</h1>

		<div class="shortline center"></div>
	</section>

	<section class="listing">
		<div class="container">

			<p class="intro">
				Discover a campus that blends Scottish heritage with
				Indian innovation. A global education awaits!
			</p>

			<?php if ( have_posts() ) : ?>

				<div class="program-grid">

					<?php while ( have_posts() ) : ?>
						<?php
						the_post();

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

				<?php the_posts_pagination(); ?>

			<?php else : ?>

				<p>No programs available in this category yet.</p>

			<?php endif; ?>

			<p class="back-to-programmes">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'program' ) ); ?>">
					← Back to all programmes
				</a>
			</p>

		</div>
	</section>

</main>

<?php
get_footer();