<?php
/**
 * Single program template.
 *
 * @package University_Theme
 */

get_header();
?>

<main class="program-detail">

	<?php if ( have_posts() ) : ?>

		<?php while ( have_posts() ) : ?>
			<?php
			the_post();

			$program_id = get_the_ID();

			$academic_year = get_post_meta(
				$program_id,
				'academic_year',
				true
			);

			$duration = get_post_meta(
				$program_id,
				'duration',
				true
			);

			$sections = get_post_meta(
				$program_id,
				'content_sections',
				true
			);

			$types = get_the_terms(
				$program_id,
				'program-type'
			);

			$archive_url = get_post_type_archive_link( 'program' );
			?>

			<nav class="program-breadcrumbs" aria-label="Breadcrumb">

				<a
					href="<?php echo esc_url( home_url( '/' ) ); ?>"
					data-track="<?php echo university_program_track_attr(
						'link',
						'breadcrumb-home',
						'programme-overview',
						'componentLink'
					); ?>"
				>
					Home
				</a>

				<span aria-hidden="true"> / </span>

				<a
					href="<?php echo esc_url( $archive_url ); ?>"
					data-track="<?php echo university_program_track_attr(
						'link',
						'breadcrumb-programmes',
						'programme-overview',
						'componentLink'
					); ?>"
				>
					Programmes
				</a>

				<span aria-hidden="true"> / </span>

				<span aria-current="page">
					<?php the_title(); ?>
				</span>

			</nav>

			<article>

				<h1><?php the_title(); ?></h1>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="program-featured-image">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				<?php endif; ?>

				<?php if ( $types && ! is_wp_error( $types ) ) : ?>
					<p>
						<strong>Program Type:</strong>

						<?php foreach ( $types as $type ) : ?>

							<?php
							$type_url = home_url(
								'/programmes/' . $type->slug . '/'
							);
							?>

							<a
								href="<?php echo esc_url( $type_url ); ?>"
								data-track="<?php echo university_program_track_attr(
									'link',
									'program-type',
									'programme-overview',
									'componentLink'
								); ?>"
							>
								<?php echo esc_html( $type->name ); ?>
							</a>

						<?php endforeach; ?>
					</p>
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

				<div class="program-detail-actions">

					<a
						class="yellow"
						href="#interest-dialog"
						data-dialog
						data-track="<?php echo university_program_track_attr(
							'link',
							'apply-now',
							'programme-overview',
							'componentLink'
						); ?>"
					>
						Apply now
					</a>

					<button
						class="yellow"
						type="button"
						data-dialog
						data-track="<?php echo university_program_track_attr(
							'button',
							'open-enquiry-form',
							'programme-overview',
							'componentButton'
						); ?>"
					>
						Enquire now
					</button>

				</div>

				<p class="program-back-link">
					<a
						href="<?php echo esc_url( $archive_url ); ?>"
						data-track="<?php echo university_program_track_attr(
							'link',
							'back-to-programmes',
							'programme-overview',
							'componentLink'
						); ?>"
					>
						← Back to programmes
					</a>
				</p>

			</article>

		<?php endwhile; ?>

	<?php else : ?>

		<p>Program not found.</p>

	<?php endif; ?>

</main>

<?php
get_footer();