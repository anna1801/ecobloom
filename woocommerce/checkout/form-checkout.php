<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>

<section class="py-4 pb-5">
	<div class="container">
		<form name="checkout" method="post" id="checkoutForm" class="checkout woocommerce-checkout" 
				action="<?php echo esc_url( wc_get_checkout_url() ); ?>" 
				enctype="multipart/form-data" 
				aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>"
				onsubmit="submitOrder(event)">
			<div class="row g-4">
				<div class="col-12 col-lg-7">
					<?php if ( $checkout->get_checkout_fields() ) : ?>

						<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

						<?php do_action( 'woocommerce_checkout_billing' ); ?>

						<?php do_action( 'woocommerce_checkout_shipping' ); ?>

						<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

					<?php endif; ?>
				</div>

				<div class="col-12 col-lg-5">
					<div class="order-summary-sticky">
						<div class="about-value-card p-4 bg-pink-light border border-magenta">

							<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

							<h5 class="fw-bold text-dark mb-3"> <?php esc_html_e( 'Your EcoBloom Order', 'woocommerce' ); ?></h5>
							
							<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

							<?php do_action( 'woocommerce_checkout_order_review' ); ?>

							<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

						</div>

						<!-- Delivery info card -->
						<div class="about-story-box p-3 bg-white mt-3">
							<div class="d-flex align-items-start gap-3">
								<i class="bi bi-truck text-magenta fs-4 mt-1"></i>
								<div>
									<div class="fw-bold text-dark small mb-1">Estimated Delivery</div>
									<div class="text-muted" style="font-size:.8rem;">3–5 business days (Metro) · 5–7
										days (Other cities)</div>
									<div class="text-muted" style="font-size:.8rem;">All orders dispatched in
										<strong>discreet, unmarked packaging</strong>.</div>
								</div>
							</div>
						</div>
					</div>	
				</div>	
			</div>
		</form>
		<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
	</div>
</section>
