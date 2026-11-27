<?php
/**
 * Theme header.
 *
 * @package University_Theme
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="header">
	<div class="header-inner">

		<a
			href="<?php echo esc_url( home_url( '/' ) ); ?>"
			aria-label="University of Aberdeen Mumbai home"
		>
			<img
				class="logo"
				src="<?php echo esc_url( get_template_directory_uri() . '/assets/logo.png' ); ?>"
				alt="University of Aberdeen Mumbai"
			>
		</a>

		<button
			class="menu-toggle"
			type="button"
			aria-expanded="false"
			aria-controls="nav"
		>
			Menu ☰
		</button>

		<nav id="nav" aria-label="Main navigation">
			<a href="<?php echo esc_url( home_url( '/#programmes' ) ); ?>">
				Study
			</a>

			<a href="<?php echo esc_url( home_url( '/#careers' ) ); ?>">
				About Us
			</a>

			<a href="#footer">Contact Us</a>

			<button class="yellow" type="button" data-dialog>
				Apply Now
			</button>
		</nav>

	</div>

	<div class="subnav">
		<a href="https://www.abdn.ac.uk/">
			University of Aberdeen UK
		</a>
	</div>
</header>