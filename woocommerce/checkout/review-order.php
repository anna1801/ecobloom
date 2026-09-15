<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
	
<div class="woocommerce-checkout-review-order-table">

	<?php do_action( 'woocommerce_review_order_before_cart_contents' ); ?>

	<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<div class="checkout-order-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<?php
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

					$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail',
						$_product->get_image(
							'woocommerce_thumbnail',
							array(
								'class' => 'checkout-order-img',
							)
						),
						$cart_item,
						$cart_item_key
					);

					if ( ! $product_permalink ) {
						echo $thumbnail; 
					} else {
						printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); 
					}
					?>
					<div class="flex-grow-1">
						<div class="fw-bold text-dark small"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?></div>
						<div class="text-muted" style="font-size:.78rem;">Qty: <?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', sprintf( '&times;&nbsp;%s', $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?></div>
						<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
					</div>
					<span class="fw-bold text-dark">
						<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
					</span>
				</div>

				<?php
			}
		}
	?>

	<?php do_action( 'woocommerce_review_order_after_cart_contents' ); ?>

	<?php 
		if ( wc_coupons_enabled() ) :
			wc_get_template( 'checkout/form-coupon.php' );
		endif;
	?>
	
	<hr class="my-3">

	<div class="d-flex justify-content-between mb-2">
		<span class="text-muted small">Subtotal (<?php echo WC()->cart->get_cart_contents_count(); ?> items)</span>
		<span class="fw-500 text-dark small"><?php wc_cart_totals_subtotal_html(); ?></span>
	</div>

	<?php if ( wc_tax_enabled() ) : ?>
		<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
			<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
				<div class="d-flex justify-content-between mb-2 tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<span class="text-muted small"><?php echo esc_html( $tax->label ); ?></span>
					<span class="fw-500 text-dark small"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="d-flex justify-content-between mb-2tax-total">
				<span class="text-muted small"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
				<span class="fw-500 text-dark small"><?php wc_cart_totals_taxes_total_html(); ?></span>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

		<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

		<?php wc_cart_totals_shipping_html(); ?>

		<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

	<?php endif; ?>

	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<div class="d-flex justify-content-between mb-2">
			<span class="text-muted small"><?php echo esc_html( $fee->name ); ?></span>
			<span class="fw-500 text-dark small"><?php wc_cart_totals_fee_html( $fee ); ?></span>
		</div>
	<?php endforeach; ?>

	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
		<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?> d-flex justify-content-between mb-2" id="discountRow">
			<span class="text-success small fw-bold">
				<i class="bi bi-tag-fill me-1"></i><?php wc_cart_totals_coupon_label( $coupon ); ?>
			</span>
			<span class="text-success fw-bold small" id="discountAmt"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
		</div>
	<?php endforeach; ?>

	<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

	<hr class="my-3">

	<div class="d-flex justify-content-between align-items-center mb-4 total_payable">
		<span class="fs-5 fw-bold text-dark"><?php esc_html_e( 'Total Payable', 'woocommerce' ); ?></span>
		<span class="fs-4 fw-bold text-magenta" id="checkoutTotal"><?php wc_cart_totals_order_total_html(); ?></span>
	</div>

	<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>


	<?php 
		$order_button_text = apply_filters(
				'woocommerce_order_button_text',
				__( 'Place order', 'woocommerce' )
			);

		$order_total = WC()->cart->get_total();
	?>
	<div class="place-order">
		<noscript>
			<?php
			/* translators: $1 and $2 opening and closing emphasis tags respectively */
			printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
			?>
			<br/><button type="submit" class="button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
		</noscript>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<div class="btn btn-pay-now w-100 d-flex justify-content-center align-items-center gap-2">
			<i class="bi bi-shield-check"></i>
			<?php echo esc_html( $order_button_text ); ?>
			<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) . '" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '"> ' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>
			— <?php echo $order_total; ?>
		</div>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
		
	</div>

</div>
<?php
if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
