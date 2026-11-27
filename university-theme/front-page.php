<?php
/**
 * Home page template.
 *
 * @package University_Theme
 */

get_header();

$theme_url = get_template_directory_uri();
?>

<main>

	<!-- Hero section -->
	<section
		class="hero"
		aria-label="Featured slideshow"
		aria-roledescription="carousel"
	>
		<img
			class="hero-image"
			src="<?php echo esc_url( $theme_url . '/assets/skyline.jpg' ); ?>"
			alt="Mumbai skyline"
		>

		<button
			type="button"
			class="hero-arrow hero-prev"
			aria-label="Previous slide"
		>
			&#8249;
		</button>

		<button
			type="button"
			class="hero-arrow hero-next"
			aria-label="Next slide"
		>
			&#8250;
		</button>

		<div class="hero-copy">
			<h1>Join the University of Aberdeen in India</h1>

			<p>
				Global challenges are waiting for new ways of thinking.
				Discover your true north and start creating a more inclusive
				and sustainable world <span>→</span>
			</p>

			<div class="dots" role="group" aria-label="Choose slide">

				<button
					type="button"
					class="active"
					aria-label="Go to slide 1"
					aria-current="true"
				></button>

				<button
					type="button"
					aria-label="Go to slide 2"
				></button>

				<button
					type="button"
					aria-label="Go to slide 3"
				></button>

			</div>

			<span class="sr-only slide-status" aria-live="polite">
				Slide 1 of 3
			</span>
		</div>
	</section>

	<!-- Careers section -->
	<section class="careers" id="careers">
		<div class="container">

			<h2>Careers and Employability</h2>

			<div class="shortline"></div>

			<div class="career-layout">

				<div
					class="reference-photo career-photo"
					role="img"
					aria-label="Students on campus"
				></div>

				<div>
					<p>
						Lorem Ipsum is simply dummy text of the printing
						and typesetting industry. Lorem Ipsum has been the
						industry's standard dummy text ever since the 1500s,
						when an unknown printer took a galley of type and
						scrambled it to make a type specimen book.
					</p>

					<p>
						Lorem Ipsum has been the industry's standard dummy
						text ever since the 1500s, when an unknown printer
						took a galley of type and scrambled it to make a
						type specimen book.
					</p>

					<a class="yellow" href="#programmes">
						See More
					</a>
				</div>

			</div>
		</div>
	</section>

	<!-- Programmes section -->
	<section class="home-programmes" id="programmes">
		<div class="container">

			<h2>Our Programmes</h2>

			<div class="shortline center"></div>

			<div class="cards">

				<article class="card">

					<div
						class="reference-photo home-under"
						role="img"
						aria-label="Technology and artificial intelligence"
					></div>

					<div class="card-body">
						<h3>Undergraduate Programmes</h3>

						<p>
							Lorem ipsum dolor sit amet, consectetur
							adipiscing elit. Vestibulum ante tortor.
						</p>

						<a href="<?php echo esc_url( home_url( '/programmes/undergraduate/' ) ); ?>">
							See More <span>→</span>
						</a>
					</div>

				</article>

				<article class="card">

					<div
						class="reference-photo home-post"
						role="img"
						aria-label="Students in a classroom"
					></div>

					<div class="card-body">
						<h3>Postgraduate Programmes</h3>

						<p>
							Lorem ipsum dolor sit amet, consectetur
							adipiscing elit. Vestibulum ante tortor.
						</p>

						<a href="<?php echo esc_url( home_url( '/programmes/postgraduate/' ) ); ?>">
							See More <span>→</span>
						</a>
					</div>

				</article>

			</div>
		</div>
	</section>

</main>

<?php
get_footer();