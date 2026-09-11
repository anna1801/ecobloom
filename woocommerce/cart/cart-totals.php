<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="about-value-card p-4 bg-pink-light border border-magenta cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h4 class="fw-bold text-dark mb-4">Order Summary</h4>

	<div class="shop_table shop_table_responsive">

        <div class="d-flex justify-content-between align-items-center mb-3 cart-subtotal">
            <span class="text-muted">Subtotal</span>
            <span class="fw-bold text-dark" id="cartPageSubtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
                <?php wc_cart_totals_subtotal_html(); ?>
            </span>
        </div>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="d-flex justify-content-between align-items-center mb-3 cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<span class="text-muted"><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
				<span class="fw-bold text-dark" data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>">
                    <?php wc_cart_totals_coupon_html( $coupon ); ?>
                </span>
            </div>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

			<div class="d-flex justify-content-between align-items-center mb-3 shipping">
				<span class="text-muted"><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></span>
				<span class="fw-bold text-dark" data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>">
                    <?php woocommerce_shipping_calculator(); ?>
                </span>
			</div>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="d-flex justify-content-between align-items-center mb-3 fee">
				<span class="text-muted"><?php echo esc_html( $fee->name ); ?></span>
				<span class="fw-bold text-dark" data-title="<?php echo esc_attr( $fee->name ); ?>">
                    <?php wc_cart_totals_fee_html( $fee ); ?>
                </span>
			</div>
		<?php endforeach; ?>

		<?php
		if ( wc_tax_enabled() ) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
			}

            if( WC()->cart->display_prices_including_tax() ) {

                foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { 
                    ?>
                    <div class="d-flex justify-content-between align-items-center mb-3 tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                        <span class="text-muted"><?php echo esc_html( $tax->label ) . $estimated_text; ?></span>
                        <span class="text-dark fw-500" data-title="<?php echo esc_attr( $tax->label ); ?>">
                            Included in price
                        </span>
                    </div>
                    <?php
                }

            } else {

                if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
                    foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { 
                        ?>
                        <div class="d-flex justify-content-between align-items-center mb-3 tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                            <span class="text-muted"><?php echo esc_html( $tax->label ) . $estimated_text; ?></span>
                            <span class="fw-bold text-dark" data-title="<?php echo esc_attr( $tax->label ); ?>">
                                <?php echo wp_kses_post( $tax->formatted_amount ); ?>
                            </span>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="d-flex justify-content-between align-items-center mb-3 tax-total">
                        <span class="text-muted"><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text;?></span>
                        <span class="fw-bold text-dark" data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>">
                            <?php wc_cart_totals_taxes_total_html(); ?>
                        </span>
                    </div>
                    <?php
                }
            }
		}
		?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

        <hr class="my-4">

		<div class="d-flex justify-content-between align-items-center mb-4 order-total">
			<span class="fs-5 fw-bold text-dark"><?php esc_html_e( 'Total', 'woocommerce' ); ?></span>
			<span class="fs-4 fw-bold text-magenta" id="cartPageTotal" data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>">
                <?php wc_cart_totals_order_total_html(); ?>
            </span>
		</div>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

    </div>

	<div class="wc-proceed-to-checkout">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
