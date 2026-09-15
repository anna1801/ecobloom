<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>
<div id="payment" class="woocommerce-checkout-payment checkout-card">
	<?php if ( WC()->cart && WC()->cart->needs_payment() ) : ?>
		<div class="wc_payment_methods payment_methods methods">

			<p class="form-section-title"><i class="bi bi-credit-card-fill"></i>Payment Method</p>

			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
				}
			} else {
				echo '<li>';
				wc_print_notice( apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ), 'notice' ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment
				echo '</li>';
			}
			?>
		</div>
	<?php endif; ?>

	<!-- Trust line -->
	<div class="trust-row mt-3">
		<span class="trust-badge"><i class="bi bi-shield-lock-fill text-magenta"></i> 256-bit
			SSL</span>
		<span class="trust-badge"><i class="bi bi-award-fill text-success"></i> PCI DSS
			Secure</span>
		<span class="trust-badge"><i class="bi bi-arrow-counterclockwise text-primary"></i> Easy
			Returns</span>
		<span class="trust-badge"><i class="bi bi-box-seam text-warning"></i> Discreet
			Box</span>
	</div>
</div>
