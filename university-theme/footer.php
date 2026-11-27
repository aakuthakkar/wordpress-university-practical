<?php
/**
 * Theme footer.
 *
 * @package University_Theme
 */
?>

<footer id="footer">
	<div class="footer-inner">

		<div>
			<a
				href="<?php echo esc_url( home_url( '/' ) ); ?>"
				data-track="<?php echo university_program_track_attr(
					'link',
					'footer-home',
					'site-footer',
					'componentLink'
				); ?>"
			>
				<img
					class="logo"
					src="<?php echo esc_url( get_template_directory_uri() . '/assets/logo.png' ); ?>"
					alt="University of Aberdeen Mumbai"
				>
			</a>

			<p>
				The University of Aberdeen<br>
				India Location,<br>
				Mumbai
			</p>
		</div>

		<div>
			<h3>Connect With Us</h3>

			<div class="social" aria-label="Social media">
				<span>𝕏</span>
				<span>◎</span>
				<span>▣</span>
			</div>
		</div>

	</div>

	<div class="footer-bottom">
		<span>Privacy</span>
		<span>Accessibility</span>
		<span>Cookies</span>

		<p>
			&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
			University of Aberdeen
		</p>
	</div>
</footer>

<dialog id="interest-dialog" aria-labelledby="dialog-title">

	<div class="dialog-head">

		<h2 id="dialog-title">
			Register your interest
		</h2>

		<button
			type="button"
			class="close"
			aria-label="Close"
			data-track="<?php echo university_program_track_attr(
				'button',
				'close-enquiry-form',
				'enquiry-form',
				'componentButton'
			); ?>"
		>
			×
		</button>

	</div>

	<form id="interest-form">

		<div class="form-grid">

			<label class="wide">
				I am enquiring as <b>*</b>

				<select required>
					<option value="">Select an option</option>
					<option>Prospective student</option>
					<option>Parent or guardian</option>
					<option>Other</option>
				</select>
			</label>

			<label>
				Level of interest <b>*</b>

				<select required>
					<option value="">Select an option</option>
					<option>Undergraduate</option>
					<option>Postgraduate</option>
				</select>
			</label>

			<label>
				Programme of interest <b>*</b>

				<select required>
					<option value="">Select an option</option>
					<option>Computer Applications</option>
					<option>Engineering</option>
					<option>Other</option>
				</select>
			</label>

			<label>
				First name <b>*</b>

				<input
					type="text"
					autocomplete="given-name"
					required
				>
			</label>

			<label>
				Last name <b>*</b>

				<input
					type="text"
					autocomplete="family-name"
					required
				>
			</label>

			<label>
				Country <b>*</b>

				<select required>
					<option value="">Select an option</option>
					<option>India</option>
					<option>Other</option>
				</select>
			</label>

			<label>
				City <b>*</b>

				<input
					type="text"
					autocomplete="address-level2"
					required
				>
			</label>

			<label>
				Phone number <b>*</b>

				<input
					type="tel"
					placeholder="+91"
					autocomplete="tel"
					required
				>
			</label>

			<label>
				Email <b>*</b>

				<input
					type="email"
					autocomplete="email"
					required
				>
			</label>

		</div>

		<div class="form-footer">

			<p>
				By clicking the button below, you agree to receive
				communications about this programme and other academic
				programmes from the University of Aberdeen.
			</p>

			<button
				class="yellow"
				type="submit"
				data-track="<?php echo university_program_track_attr(
					'button',
					'submit-enquiry-form',
					'enquiry-form',
					'componentButton'
				); ?>"
			>
				Submit
			</button>

			<p class="demo-note">
				Demo only: this form does not send or save information.
			</p>

		</div>

	</form>

	<div id="thanks" hidden>
		<div class="success">

			<strong>
				Thank you for registering your interest
			</strong>

			<p>
				We'll send you helpful information and updates about
				this programme, the application process, and more.
			</p>

		</div>
	</div>

</dialog>

<?php wp_footer(); ?>

</body>
</html>