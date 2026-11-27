<?php
/**
 * Single program template.
 *
 * @package University_Theme
 */

get_header();
?>

<main>
	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : ?>
			<?php
			the_post();

			$academic_year = get_post_meta(
				get_the_ID(),
				'academic_year',
				true
			);

			$duration = get_post_meta(
				get_the_ID(),
				'duration',
				true
			);

			$sections = get_post_meta(
				get_the_ID(),
				'content_sections',
				true
			);
			?>

			<article>
				<h1><?php the_title(); ?></h1>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="program-featured-image">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				<?php endif; ?>

				<p>
					<strong>Academic Year:</strong>
					<?php echo esc_html( $academic_year ); ?>
				</p>

				<p>
					<strong>Duration:</strong>
					<?php echo esc_html( $duration ); ?>
				</p>

				<div class="program-main-content">
					<?php the_content(); ?>
				</div>

				<?php if ( is_array( $sections ) && ! empty( $sections ) ) : ?>
					<div class="program-content-sections">

						<?php foreach ( $sections as $section ) : ?>

							<?php if ( ! is_array( $section ) ) : ?>
								<?php continue; ?>
							<?php endif; ?>

							<section class="program-content-section">

								<?php if ( ! empty( $section['title'] ) ) : ?>
									<h2>
										<?php echo esc_html( $section['title'] ); ?>
									</h2>
								<?php endif; ?>

								<?php if ( ! empty( $section['image'] ) ) : ?>
									<div class="program-section-image">
										<?php
										echo wp_get_attachment_image(
											(int) $section['image'],
											'large'
										);
										?>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $section['description'] ) ) : ?>
									<p>
										<?php echo esc_html( $section['description'] ); ?>
									</p>
								<?php endif; ?>

							</section>

						<?php endforeach; ?>

					</div>
				<?php endif; ?>

			</article>

		<?php endwhile; ?>

	<?php else : ?>

		<p>Program not found.</p>

	<?php endif; ?>
</main>

<?php
get_footer();