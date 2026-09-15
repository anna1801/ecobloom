<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

<div class="u-columns" id="customer_login">

	<div class="u-column1" id="login">

<?php endif; ?>


		<form class="woocommerce-form woocommerce-form-login" method="post" novalidate>

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<div class="mb-3 woocommerce-form-row woocommerce-form-row--wide form-row-wide">
				<label for="username" class="form-label fw-semibold small text-dark"><?php esc_html_e( 'Username or Email Address *', 'woocommerce' ); ?></label>
				<input type="text" class="form-control rounded-pill px-4 py-3 woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" placeholder="you@example.com" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
			</div>

			<div class="mb-3 woocommerce-form-row woocommerce-form-row--wide form-row-wide">
				<div class="d-flex justify-content-between align-items-center">
					<label for="password" class="form-label fw-semibold small text-dark mb-0"><?php esc_html_e( 'Password *', 'woocommerce' ); ?></label>
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="small text-magenta text-decoration-none"><?php esc_html_e( 'Forgot Password?', 'woocommerce' ); ?></a>
				</div>
				<input class="form-control rounded-pill px-4 py-3 mt-1 woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" placeholder="••••••••" autocomplete="current-password" required aria-required="true" />
			</div>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<div class="mb-4 form-check">
				<input class="form-check-input woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
				<label class="form-check-label small text-muted" for="rememberme"> <?php esc_html_e( 'Remember my login on this device', 'woocommerce' ); ?></label>
			</div>

			<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

			<button type="submit" class="btn btn-primary rounded-pill w-100 py-3 fw-bold shadow-sm woocommerce-button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>">
				<?php esc_html_e( 'Sign In', 'woocommerce' ); ?> <i class="bi bi-arrow-right ms-1"></i>
			</button>
		
			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

	</div>

	<div class="u-column2" id="register">

		<form method="post" class="woocommerce-form woocommerce-form-register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
			<div class="row g-3">
				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<div class="col-md-6 woocommerce-form-row woocommerce-form-row--wide form-row-wide">
					<label for="reg_firstname" class="form-label fw-semibold small text-dark"><?php esc_html_e( 'First Name *', 'woocommerce' ); ?></label>
					<input type="text" class="form-control rounded-pill px-4 py-3 woocommerce-Input woocommerce-Input--text input-text" name="first_name" id="reg_firstname" placeholder="Priya" autocomplete="First Name" value="<?php echo ( ! empty( $_POST['first_name'] ) ) ? esc_attr( wp_unslash( $_POST['first_name'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
				</div>

				<div class="col-md-6 woocommerce-form-row woocommerce-form-row--wide form-row-wide">
					<label for="reg_lastname" class="form-label fw-semibold small text-dark"><?php esc_html_e( 'Last Name *', 'woocommerce' ); ?></label>
					<input type="text" class="form-control rounded-pill px-4 py-3 woocommerce-Input woocommerce-Input--text input-text" name="last_name" id="reg_lastname" placeholder="Sharma" autocomplete="Last Name" value="<?php echo ( ! empty( $_POST['last_name'] ) ) ? esc_attr( wp_unslash( $_POST['last_name'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
				</div>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

					<div class="col-12 woocommerce-form-row woocommerce-form-row--wide form-row-wide">
						<label for="reg_username" class="form-label fw-semibold small text-dark"><?php esc_html_e( 'Username *', 'woocommerce' ); ?></label>
						<input type="text" class="form-control rounded-pill px-4 py-3 woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" placeholder="priya" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
					</div>

				<?php endif; ?>

				<div class="col-12 woocommerce-form-row woocommerce-form-row--wide form-row-wide">
					<label for="reg_email" class="form-label fw-semibold small text-dark"><?php esc_html_e( 'Email Address *', 'woocommerce' ); ?> </label>
					<input type="email" class="form-control rounded-pill px-4 py-3 woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" placeholder="priya@example.com" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
				</div>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

					<div class="col-md-6 woocommerce-form-row woocommerce-form-row--wide form-row-wide">
						<label for="reg_password" class="form-label fw-semibold small text-dark"><?php esc_html_e( 'Create Password *', 'woocommerce' ); ?> </label>
						<input type="password" class="form-control rounded-pill px-4 py-3 woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" placeholder="Min. 8 characters" autocomplete="new-password" required aria-required="true" />
					</div>

					<div class="col-md-6 woocommerce-form-row woocommerce-form-row--wide form-row-wide">
						<label for="reg_password_confirm" class="form-label fw-semibold small text-dark"><?php esc_html_e( 'Confirm Password *', 'woocommerce' ); ?> </label>
						<input type="password" class="form-control rounded-pill px-4 py-3 woocommerce-Input woocommerce-Input--text input-text" name="password_confirm" id="reg_password_confirm" placeholder="Repeat password" autocomplete="new-password" required aria-required="true" />
					</div>

				<?php else : ?>

					<div class="form-label fw-semibold small text-dark"><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?></div>

				<?php endif; ?>
				
				<div class="col-12 form-check mt-3">
					<input type="checkbox" class="form-check-input ms-1" name="privacy_policy" id="privacy_policy" value="1" required>
					<label class="form-check-label small text-muted ms-2" for="privacy_policy">
						<?php echo wc_privacy_policy_text( 'registration' ); ?>
					</label>
				</div>

				<?php do_action( 'woocommerce_register_form' ); ?>

				<div class="col-12 mt-2 woocommerce-form-row ">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>

					<button type="submit" class="btn btn-primary rounded-pill w-100 py-3 fw-bold shadow-sm woocommerce-Button woocommerce-button <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>">
						<?php esc_html_e( 'Create Account', 'woocommerce' ); ?> <i class="bi bi-check2-circle ms-1"></i>
					</button>
				
				</div>

				<?php do_action( 'woocommerce_register_form_end' ); ?>
			</div>
		</form>

	</div>

</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
