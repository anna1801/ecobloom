<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { 
	return;
}

?>


<div class="mt-3">
	<span class="coupon-toggle"
		onclick="document.getElementById('couponBox').style.display = document.getElementById('couponBox').style.display==='none'?'block':'none'">
		<i class="bi bi-tag-fill me-1"></i><?php echo esc_attr__( 'Have a coupon code?', 'woocommerce' ); ?>
	</span>
	<div id="couponBox" style="display: none;">
		<!-- <form class="checkout_coupon woocommerce-form-coupon" method="post" id="woocommerce-checkout-form-coupon"> -->
			<div class="d-flex gap-2 mt-2">
				<input type="text" name="coupon_code" class="form-control checkout-input-modern px-3 py-1 fs-7" placeholder="e.g. ECO20" id="coupon_code" value="" />

				<button type="button" 
						class="btn btn-premium-gradient rounded-pill px-3 py-1 fs-7 fw-bold <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" 
						name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"
						id="apply_coupon_button">
					<i class="bi bi-tag-fill me-1"></i> <?php esc_html_e( 'Apply', 'woocommerce' ); ?>
				</button>
			</div>
		<!-- </form> -->
		<div id="couponMsg" class="mt-1 small"></div>
	</div>
</div>
