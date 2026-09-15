<?php 
add_filter( 'woocommerce_settings_tabs_array', function( $tabs ) {

    $tabs['cod_fee'] = __( 'COD Fee', 'your-textdomain' );

    return $tabs;

}, 50 );

add_action( 'woocommerce_settings_tabs_cod_fee', function() {

    woocommerce_admin_fields( cod_fee_get_settings() );

} );

add_action( 'woocommerce_update_options_cod_fee', function() {

    woocommerce_update_options( cod_fee_get_settings() );

} );

function cod_fee_get_settings() {

    return array(

        array(
            'title' => __( 'Cash on Delivery Fee Settings', 'your-textdomain' ),
            'type'  => 'title',
            'desc'  => __( 'Configure the additional fee charged when customers choose Cash on Delivery.', 'your-textdomain' ),
            'id'    => 'cod_fee_settings',
        ),

        array(
            'title'   => __( 'Enable COD Fee', 'your-textdomain' ),
            'desc'    => __( 'Enable an additional fee for Cash on Delivery orders.', 'your-textdomain' ),
            'id'      => 'cod_fee_enabled',
            'type'    => 'checkbox',
            'default' => 'no',
        ),

        array(
            'title'             => __( 'COD Fee Amount', 'your-textdomain' ),
            'desc'              => __( 'Enter the additional COD fee amount.', 'your-textdomain' ),
            'id'                => 'cod_fee_amount',
            'type'              => 'number',
            'default'           => '0',
            'custom_attributes' => array(
                'step' => '0.01',
                'min'  => '0',
            ),
            'desc_tip'          => true,
        ),

        array(
            'title'   => __( 'Fee Type', 'your-textdomain' ),
            'id'      => 'cod_fee_type',
            'type'    => 'select',
            'default' => 'fixed',
            'options' => array(
                'fixed'      => __( 'Fixed Amount', 'your-textdomain' ),
                'percentage' => __( 'Percentage', 'your-textdomain' ),
            ),
        ),

        array(
            'title'   => __( 'Fee Label', 'your-textdomain' ),
            'desc'    => __( 'The label shown to customers at checkout.', 'your-textdomain' ),
            'id'      => 'cod_fee_label',
            'type'    => 'text',
            'default' => __( 'Cash on Delivery Fee', 'your-textdomain' ),
        ),

        array(
            'type' => 'sectionend',
            'id'   => 'cod_fee_settings',
        ),

    );

}


// To appear at checkout
add_action( 'woocommerce_cart_calculate_fees', function( $cart ) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
        return;
    }

    if ( 'yes' !== get_option( 'cod_fee_enabled', 'no' ) ) {
        return;
    }

    if ( ! WC()->session ) {
        return;
    }

    $chosen_payment_method = WC()->session->get( 'chosen_payment_method' );

    if ( 'cod' !== $chosen_payment_method ) {
        return;
    }

    $amount = (float) get_option( 'cod_fee_amount', 0 );
    $type   = get_option( 'cod_fee_type', 'fixed' );

    $label  = get_option( 'cod_fee_label');

    if ( $amount <= 0 ) {
        return;
    }

    if ( 'percentage' === $type ) {
        $fee = ( $cart->get_subtotal() * $amount ) / 100;
        $label = $label .' (' . $amount . '%)';
    } else {
        $fee = $amount;
        $label = $label;
    }

    $cart->add_fee( $label, $fee, false );

} );


?>