<?php
/**
 * Pay for order form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-pay.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.9.0
 */

defined( 'ABSPATH' ) || exit;


$cod_fee_enabled = get_option( 'cod_fee_enabled', 'no' );
$cod_fee_amount  = (float) get_option( 'cod_fee_amount', 0 );
$cod_fee_type    = get_option( 'cod_fee_type', 'fixed' );
$cod_fee_label   = get_option( 'cod_fee_label', 'Cash on Delivery Fee' );

$payment_method = $order->get_payment_method();

foreach ( $order->get_items( 'fee' ) as $fee_item_id => $fee_item ) {

    if (
        $fee_item->get_meta( '_ecobloom_cod_fee' ) ||
        $fee_item->get_name() === $cod_fee_label
    ) {
        $order->remove_item( $fee_item_id );
    }
}

if (
    'yes' === $cod_fee_enabled &&
    'cod' === $payment_method &&
    $cod_fee_amount > 0
) {

    if ( 'percentage' === $cod_fee_type ) {

        $fee_amount = ( $order->get_subtotal() * $cod_fee_amount ) / 100;

    } else {

        $fee_amount = $cod_fee_amount;
    }

    $fee = new WC_Order_Item_Fee();

    $fee->set_name( $cod_fee_label );
    $fee->set_amount( $fee_amount );
    $fee->set_total( $fee_amount );
    $fee->add_meta_data( '_ecobloom_cod_fee', 'yes', true );

    $order->add_item( $fee );
}

$order->calculate_totals( false );
$order->save();

$totals = $order->get_order_item_totals();
?>

<section class="py-4 pb-5">
	<div class="container">
		<form id="order_review" method="post">
			<div class="row g-4">

                <?php do_action( 'woocommerce_pay_order_before_payment' ); ?>

                <div class="col-12 col-lg-7">
                    <div class="checkout-card">

                        <p class="form-section-title"><i class="bi bi-credit-card-fill"></i>Payment Method</p>
                        
                        <div id="payment">
                            <?php if ( $order->needs_payment() ) : ?>

                                <div class="wc_payment_methods payment_methods methods" aria-label="<?php esc_attr_e( 'Payment methods', 'woocommerce' ); ?>">
                                   
                                    <?php
                                        $order_payment_method = $order->get_payment_method();

                                        if ( $order_payment_method && isset( $available_gateways[ $order_payment_method ] ) ) {

                                            foreach ( $available_gateways as $gateway_id => $gateway ) {
                                                $gateway->chosen = ( $gateway_id === $order_payment_method );
                                            }
                                        }
                                    ?>

                                   <?php
                                        if ( ! empty( $available_gateways ) ) {
                                            foreach ( $available_gateways as $gateway ) {
                                                wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
                                            }
                                        } else {
                                            echo '<li>';
                                            wc_print_notice( apply_filters( 'woocommerce_no_available_payment_methods_message', esc_html__( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) ), 'notice' ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment
                                            echo '</li>';
                                        }
                                    ?>
                                </div>

                            <?php endif; ?>

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
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="order-summary-sticky">
                        <div class="about-value-card p-4 bg-pink-light border border-magenta">
                            <h5 class="fw-bold text-dark mb-3">Your EcoBloom Order</h5>
                
                            <?php if ( count( $order->get_items() ) > 0 ) : ?>
                                <?php foreach ( $order->get_items() as $item_id => $item ) : ?>
                                    <?php
                                    if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
                                        continue;
                                    }
                                    ?>

                                    <div class="checkout-order-item <?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">

                                        <?php 

                                            $product = $item->get_product();

                                            if ( $product ) {
                                                echo $product->get_image(
                                                    'woocommerce_thumbnail',
                                                    array(
                                                        'class' => 'checkout-order-img',
                                                    )
                                                );
                                            }
                                        ?>
                                        
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-dark small">
                                                <?php
                                                    echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) );

                                                    do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );

                                                    wc_display_item_meta( $item );

                                                    do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );
                                                ?>
                                            </div>
                                            <div class="text-muted" style="font-size:.78rem;"> 
                                                <?php echo apply_filters( 'woocommerce_order_item_quantity_html', ' Qty:' . sprintf( '&times;&nbsp;%s', esc_html( $item->get_quantity() ) ), $item ); ?>
                                            </div>
                                        </div>
                                        <span class="fw-bold text-dark"><?php echo $order->get_formatted_line_subtotal( $item ); ?></span>
                                    </div>

                                <?php endforeach; ?>
                            <?php endif; ?>
                
                            <hr class="my-3">

                            <div id="order-pay-totals">

                                <?php
                                    $shipping_zone_name = '';

                                    $shipping_zone = WC_Shipping_Zones::get_zone_matching_package(
                                        array(
                                            'destination' => array(
                                                'country'   => $order->get_shipping_country(),
                                                'state'     => $order->get_shipping_state(),
                                                'postcode'  => $order->get_shipping_postcode(),
                                                'city'      => $order->get_shipping_city(),
                                                'address'   => $order->get_shipping_address_1(),
                                                'address_2' => $order->get_shipping_address_2(),
                                            ),
                                        )
                                    );

                                    if ( $shipping_zone ) {
                                        $shipping_zone_name = $shipping_zone->get_zone_name();
                                    }
                                ?>

                                <?php if ( $totals ) : ?>
                                    <?php foreach ( $totals as $total ) : ?>
                                        <?php if( $total['type'] != 'total') : ?>
                                            <div class="d-flex justify-content-between mb-2">
                                               <span class="text-muted small">
                                                <?php
                                                    if ( 'shipping' === $total['type'] && $shipping_zone_name ) {
                                                        echo esc_html( $shipping_zone_name );
                                                    } else {
                                                        echo wp_kses_post( $total['label'] );
                                                    }
                                                ?>
                                                </span>
                                                <span class="fw-500 text-dark small"><?php echo $total['value']; ?></span>
                                            </div>
                                        <?php endif; ?>

                                    <?php endforeach; ?>
                                <?php endif; ?>
                    
                                <hr class="my-3">

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <span class="fs-5 fw-bold text-dark">Total Payable</span>
                                    <span class="fs-4 fw-bold text-magenta" id="checkoutTotal"><?php echo $order->get_formatted_order_total(); ?></span>
                                </div>
                            </div>

                            <div class="form-row">
                                <input type="hidden" name="woocommerce_pay" value="1" />

                                <?php do_action( 'woocommerce_pay_order_before_submit' ); ?>

                                <div class="btn btn-pay-now w-100 d-flex justify-content-center align-items-center gap-2" >
                                    <i class="bi bi-shield-check"></i>
			                        <?php echo esc_html( $order_button_text ); ?>
                                    <?php echo apply_filters( 'woocommerce_pay_order_button_html', '<button type="submit" class="button alt' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) . '" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>
                                    — <?php echo $order->get_formatted_order_total(); ?>
                                </div>
                                <?php do_action( 'woocommerce_pay_order_after_submit' ); ?>

                                <?php wc_get_template( 'checkout/terms.php' ); ?>

                                <?php wp_nonce_field( 'woocommerce-pay', 'woocommerce-pay-nonce' ); ?>
                                <?php wp_nonce_field( 'ecobloom_order_pay_nonce', 'ecobloom_order_pay_nonce' ); ?>
                            </div>
                        </div>
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
	</div>
</section>
