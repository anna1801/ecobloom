<?php 
add_action( 'wp_ajax_ecobloom_order_pay_payment', 'ecobloom_order_pay_payment' );
add_action( 'wp_ajax_nopriv_ecobloom_order_pay_payment', 'ecobloom_order_pay_payment' );

function ecobloom_order_pay_payment() {

    check_ajax_referer( 'ecobloom_order_pay_nonce', 'security' );

    $order_id       = isset( $_POST['order_id'] ) ? absint( $_POST['order_id'] ) : 0;
    $order_key      = isset( $_POST['order_key'] ) ? wc_clean( wp_unslash( $_POST['order_key'] ) ) : '';
    $payment_method = isset( $_POST['payment_method'] ) ? wc_clean( wp_unslash( $_POST['payment_method'] ) ) : '';

    if ( ! $order_id || ! $order_key || ! $payment_method ) {
        wp_send_json_error();
    }

    $order = wc_get_order( $order_id );

    if ( ! $order ) {
        wp_send_json_error();
    }

    if ( ! hash_equals( $order->get_order_key(), $order_key ) ) {
        wp_send_json_error();
    }

    $cod_label = get_option(
        'cod_fee_label',
        'Cash on Delivery Fee'
    );

    foreach ( $order->get_items( 'fee' ) as $fee_id => $fee_item ) {

        $is_cod_fee = $fee_item->get_meta( '_ecobloom_cod_fee' );

        $fee_name = $fee_item->get_name();

        if (
            'yes' === $is_cod_fee ||
            $fee_name === $cod_label ||
            strpos( $fee_name, $cod_label ) === 0
        ) {
            $order->remove_item( $fee_id );
        }
    }

    if (
        'cod' === $payment_method &&
        'yes' === get_option( 'cod_fee_enabled', 'no' )
    ) {

        $amount = (float) get_option(
            'cod_fee_amount',
            0
        );

        $type = get_option(
            'cod_fee_type',
            'fixed'
        );

        $label = get_option(
            'cod_fee_label',
            'Cash on Delivery Fee'
        );

        if ( $amount > 0 ) {

            if ( 'percentage' === $type ) {

                $fee_amount = (
                    $order->get_subtotal() * $amount
                ) / 100;

                $label .= ' (' . $amount . '%)';

            } else {

                $fee_amount = $amount;
            }

            $fee = new WC_Order_Item_Fee();

            $fee->set_name( $label );
            $fee->set_amount( $fee_amount );
            $fee->set_total( $fee_amount );
            $fee->set_tax_status( 'none' );

            $fee->add_meta_data(
                '_ecobloom_cod_fee',
                'yes',
                true
            );

            $order->add_item( $fee );
        }
    }

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

    $available_gateways = WC()->payment_gateways()->get_available_payment_gateways();

    if ( isset( $available_gateways[ $payment_method ] ) ) {

        $gateway = $available_gateways[ $payment_method ];

        $order->set_payment_method( $gateway );

        $order->set_payment_method_title( $gateway->get_title() );
    }

    $order->calculate_totals( false );

    $order->save();

    $totals = $order->get_order_item_totals();

    ob_start();

    if ( $totals ) {

        foreach ( $totals as $total ) {

            if ( 'total' === $total['type'] ) {
                continue;
            }
            ?>
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
                <span class="fw-500 text-dark small"> <?php echo wp_kses_post( $total['value'] ); ?></span>
            </div>
            <?php
        }
    }
    ?>

    <hr class="my-3">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <span class="fs-5 fw-bold text-dark"> Total Payable </span>

        <span class="fs-4 fw-bold text-magenta" id="checkoutTotal">
            <?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
        </span>

    </div>

    <?php

    $html = ob_get_clean();

    wp_send_json_success(
        array(
            'html'  => $html,
            'total' => $order->get_formatted_order_total(),
        )
    );
}


add_action( 'wp_enqueue_scripts', function() {

    if ( is_wc_endpoint_url( 'order-pay' ) ) {

        wp_enqueue_script(
            'ecobloom-order-pay',
            get_template_directory_uri() . '/ajax/js/ajax_order-pay.js',
            array( 'jquery' ),
            '1.0.0',
            true
        );

        wp_localize_script(
            'ecobloom-order-pay',
            'ecobloomOrderPay',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            )
        );
    }

} );
?>