<?php
/**
 * Lost password confirmation text.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/lost-password-confirmation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.9.0
 */

defined( 'ABSPATH' ) || exit;

//wc_print_notice( esc_html__( '', 'woocommerce' ) );
?>

<?php do_action( 'woocommerce_before_lost_password_confirmation_message' ); ?>

    <div class="success-page-wrap">
        <div class="success-card">

            <div class="mb-2">
                <span class="confetti-emoji" style="animation-delay:0s;">🌸</span>
                <span class="confetti-emoji" style="animation-delay:.2s;">🎉</span>
                <span class="confetti-emoji" style="animation-delay:.4s;">🌱</span>
            </div>

            <div class="success-icon-ring">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <h1 class="fw-bold text-dark mb-2" style="font-size:1.8rem;"><?php echo esc_html( 'Password reset email has been sent.', 'woocommerce' ); ?></h1>
            <p class="text-muted mb-3">
                <?php echo esc_html( apply_filters( 'woocommerce_lost_password_confirmation_message', esc_html__( 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.', 'woocommerce' ) ) ); ?>
            </p>

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
					<i class="bi bi-arrow-left me-1"></i> <?php echo esc_html( 'Back to Sign In', 'woocommerce' ); ?>
				</a>
			</div>

        </div>
    </div>

<?php do_action( 'woocommerce_after_lost_password_confirmation_message' ); ?>
