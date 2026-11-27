<?php
/**
 * Reusable program card.
 *
 * @package University_Theme
 */

$program_id = isset( $args['post_id'] )
	? absint( $args['post_id'] )
	: get_the_ID();

if ( ! $program_id || 'program' !== get_post_type( $program_id ) ) {
	return;
}

$academic_year = get_post_meta( $program_id, 'academic_year', true );
$duration      = get_post_meta( $program_id, 'duration', true );
?>

<article class="program-card">
	<a href="<?php echo esc_url( get_permalink( $program_id ) ); ?>">
		<?php if ( has_post_thumbnail( $program_id ) ) : ?>
			<div class="program-card__image">
				<?php
				echo get_the_post_thumbnail(
					$program_id,
					'medium_large'
				);
				?>
			</div>
		<?php endif; ?>

		<h3><?php echo esc_html( get_the_title( $program_id ) ); ?></h3>
	</a>

	<p>
		<strong>Academic Year:</strong>
		<?php echo esc_html( $academic_year ); ?>
	</p>

	<p>
		<strong>Duration:</strong>
		<?php echo esc_html( $duration ); ?>
	</p>
</article>