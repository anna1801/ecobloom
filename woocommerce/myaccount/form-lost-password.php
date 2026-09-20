<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.2.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );
?>

<div class="row justify-content-center">
	<div class="col-12 col-md-8 col-lg-5">
		<div class="about-story-box p-5 bg-white shadow-lg text-center">

			<div class="mb-4">
				<div class="rounded-circle bg-pink-light text-magenta mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px; font-size: 2rem;">
					<i class="bi bi-key-fill"></i>
				</div>
				<h2 class="fw-bold text-dark mt-2">Reset Password</h2>
				<p class="text-muted small">
					<?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Enter your registered email address and we will send you a secure password reset link.', 'woocommerce' ) ); ?>
				</p>
			</div>

			<form method="post" class="text-start woocommerce-ResetPassword lost_reset_password">

				<div class="mb-4 woocommerce-form-row ">
					<label for="user_login" class="form-label fw-semibold small text-dark">
						<?php esc_html_e( 'Registered Email Address or Username *', 'woocommerce' ); ?>
					</label>
					<input class="form-control rounded-pill px-4 py-3 woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" placeholder="Email or Username" required aria-required="true" />
				</div>

				<div class="clear"></div>

				<?php do_action( 'woocommerce_lostpassword_form' ); ?>

				<input type="hidden" name="wc_reset_password" value="true" />
				<button type="submit" class="btn btn-primary rounded-pill w-100 py-3 fw-bold shadow-sm woocommerce-Button <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>">
					<?php esc_html_e( 'Send Recovery Link', 'woocommerce' ); ?> <i class="bi bi-envelope ms-1"></i>
				</button>
		
				<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

			</form>

			<?php
				$login_page = get_pages( array(
					'meta_key'   => '_wp_page_template',
					'meta_value' => 'template/template-login.php',
					'number'     => 1,
				) );

				if ( ! empty( $login_page ) ) {
					$login_url = get_permalink( $login_page[0]->ID );
				} else {
					$login_url = wc_get_page_permalink( 'myaccount' );
				}
			?>
			<div class="text-center mt-4 pt-3 border-top">
				<a href="<?php echo $login_url; ?>" class="fw-bold text-magenta text-decoration-none small">
					<i class="bi bi-arrow-left me-1"></i> Back to Sign In
				</a>
			</div>

		</div>
	</div>
</div>

<?php
do_action( 'woocommerce_after_lost_password_form' );
