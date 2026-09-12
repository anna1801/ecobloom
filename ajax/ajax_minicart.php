<?php 
// Quantity update
add_action( 'wp_ajax_update_mini_cart_quantity', 'update_mini_cart_quantity' );
add_action( 'wp_ajax_nopriv_update_mini_cart_quantity', 'update_mini_cart_quantity' );

function update_mini_cart_quantity() {

    if ( ! WC()->cart ) {
        wp_send_json_error(
            array(
                'message' => 'Cart is not available.',
            )
        );
    }

    $cart_item_key = isset( $_POST['cart_item_key'] )
        ? wc_clean( wp_unslash( $_POST['cart_item_key'] ) )
        : '';

    $quantity = isset( $_POST['quantity'] )
        ? absint( $_POST['quantity'] )
        : 1;

    if ( empty( $cart_item_key ) ) {
        wp_send_json_error(
            array(
                'message' => 'Cart item key is missing.',
            )
        );
    }

    if ( $quantity < 1 ) {
        $quantity = 1;
    }

    $cart = WC()->cart->get_cart();

    if ( ! isset( $cart[ $cart_item_key ] ) ) {
        wp_send_json_error(
            array(
                'message' => 'Cart item not found.',
            )
        );
    }

    $cart_item = $cart[ $cart_item_key ];

    $_product = $cart_item['data'];

    if ( $_product->is_sold_individually() ) {
        $quantity = 1;
    }

    $max_quantity = $_product->get_max_purchase_quantity();

    if (
        $max_quantity > 0 &&
        $quantity > $max_quantity
    ) {
        $quantity = $max_quantity;
    }

    WC()->cart->set_quantity(
        $cart_item_key,
        $quantity,
        true
    );

    WC()->cart->calculate_totals();

    WC()->cart->set_session();

    $cart = WC()->cart->get_cart();

    if ( ! isset( $cart[ $cart_item_key ] ) ) {
        wp_send_json_error(
            array(
                'message' => 'Updated cart item not found.',
            )
        );
    }

    $cart_item = $cart[ $cart_item_key ];

    $_product = $cart_item['data'];

    $quantity = (int) $cart_item['quantity'];

    $item_subtotal = WC()->cart->get_product_subtotal(
        $_product,
        $quantity
    );

    $cart_subtotal = WC()->cart->get_cart_subtotal();

    $cart_total = WC()->cart->get_total();

    $cart_count = WC()->cart->get_cart_contents_count();

    ob_start();

    woocommerce_mini_cart();

    $mini_cart_html = ob_get_clean();

    $fragments = array(
        'div.widget_shopping_cart_content' =>
            '<div class="widget_shopping_cart_content">' .
            $mini_cart_html .
            '</div>',
    );

    wp_send_json_success(
        array(
            'cart_item_key' => $cart_item_key,
            'quantity'      => $quantity,
            'item_subtotal' => $item_subtotal,
            'cart_subtotal' => $cart_subtotal,
            'cart_total'    => $cart_total,
            'cart_count'    => $cart_count,
            'fragments'     => $fragments,
        )
    );
}


// Remove cart item
add_action( 'wp_ajax_remove_cart_item_custom', 'remove_cart_item_custom' );
add_action( 'wp_ajax_nopriv_remove_cart_item_custom', 'remove_cart_item_custom' );

function remove_cart_item_custom() {

    if ( ! WC()->cart ) {
        wp_send_json_error(
            array(
                'message' => 'Cart is not available.',
            )
        );
    }

    $cart_item_key = isset( $_POST['cart_item_key'] )
        ? wc_clean( wp_unslash( $_POST['cart_item_key'] ) )
        : '';

    if ( empty( $cart_item_key ) ) {
        wp_send_json_error(
            array(
                'message' => 'Cart item key is missing.',
            )
        );
    }

    $cart = WC()->cart->get_cart();

    if ( ! isset( $cart[ $cart_item_key ] ) ) {
        wp_send_json_error(
            array(
                'message' => 'Cart item not found.',
            )
        );
    }

    WC()->cart->remove_cart_item(
        $cart_item_key
    );

    WC()->cart->calculate_totals();


    WC()->cart->set_session();

    $cart_count = WC()->cart->get_cart_contents_count();

    $cart_subtotal = WC()->cart->get_cart_subtotal();

    $cart_total = WC()->cart->get_total();

    ob_start();

    woocommerce_mini_cart();

    $mini_cart_html = ob_get_clean();

    $fragments = array(
        'div.widget_shopping_cart_content' =>
            '<div class="widget_shopping_cart_content">' .
            $mini_cart_html .
            '</div>',
    );

    wp_send_json_success(
        array(
            'cart_item_key' => $cart_item_key,
            'cart_count'    => $cart_count,
            'cart_subtotal' => $cart_subtotal,
            'cart_total'    => $cart_total,
            'fragments'     => $fragments,
        )
    );
}

// Update shipping method
add_action( 'wp_ajax_update_cart_shipping_method', 'update_cart_shipping_method' );
add_action( 'wp_ajax_nopriv_update_cart_shipping_method', 'update_cart_shipping_method' );

function update_cart_shipping_method() {

	if ( ! WC()->cart ) {
		wp_send_json_error(
			array(
				'message' => 'Cart is not available.',
			)
		);
	}

	$shipping_methods = isset( $_POST['shipping_method'] )
		? (array) wp_unslash( $_POST['shipping_method'] )
		: array();

	if ( empty( $shipping_methods ) ) {
		wp_send_json_error(
			array(
				'message' => 'Shipping method is missing.',
			)
		);
	}

	$shipping_methods = array_map( 'wc_clean', $shipping_methods );

	WC()->session->set(
		'chosen_shipping_methods',
		$shipping_methods
	);

	WC()->cart->calculate_shipping();
	WC()->cart->calculate_totals();
	WC()->cart->set_session();

	$cart_subtotal = WC()->cart->get_cart_subtotal();
	$cart_total    = WC()->cart->get_total();
	$cart_count    = WC()->cart->get_cart_contents_count();

	$chosen_methods = WC()->session->get(
		'chosen_shipping_methods',
		array()
	);

	ob_start();

	$packages = WC()->shipping()->get_packages();

	if ( ! empty( $packages ) ) :

		foreach ( $packages as $index => $package ) :

			$available_methods = isset( $package['rates'] )
				? $package['rates']
				: array();

			if ( empty( $available_methods ) ) {
				continue;
			}

			$chosen_method = isset( $chosen_methods[ $index ] )
				? $chosen_methods[ $index ]
				: '';

			$shipping_zone = WC_Shipping_Zones::get_zone_matching_package( $package );
			$zone_name     = $shipping_zone->get_zone_name();
			?>

			<div class="d-flex justify-content-between mb-3 woocommerce-shipping-totals shipping">

				<span class="text-muted">
					<?php echo esc_html( $zone_name ); ?>
				</span>

				<span
					class="text-dark fw-500 shipment-value"
					data-title="<?php echo esc_attr( $zone_name ); ?>">

					<ul class="woocommerce-shipping-methods mini-cart-shipping-methods">

						<?php foreach ( $available_methods as $method ) : ?>

							<?php
							$method_id = $method->get_id();

							$input_id = 'mini_cart_shipping_method_' .
								$index . '_' .
								sanitize_title( $method_id );

							$is_checked = ( $method_id === $chosen_method );
							?>

							<li>

								<input
									type="radio"
									name="mini_cart_shipping_method[<?php echo esc_attr( $index ); ?>]"
									data-index="<?php echo esc_attr( $index ); ?>"
									id="<?php echo esc_attr( $input_id ); ?>"
									value="<?php echo esc_attr( $method_id ); ?>"
									class="shipping_method"
									<?php checked( $is_checked, true ); ?>>

								<label
									for="<?php echo esc_attr( $input_id ); ?>"
									class="shipping-method-label text-success fw-bold">
									<?php
									echo wp_kses_post(
										wc_cart_totals_shipping_method_label( $method )
									);
									?>
								</label>

								<?php
								do_action(
									'woocommerce_after_shipping_rate',
									$method,
									$index
								);
								?>

							</li>

						<?php endforeach; ?>

					</ul>

				</span>

			</div>

			<?php

		endforeach;

	endif;

	$shipping_html = ob_get_clean();

	ob_start();

	woocommerce_mini_cart();

	$mini_cart_html = ob_get_clean();

	$fragments = array(
		'div.widget_shopping_cart_content' =>
			'<div class="widget_shopping_cart_content">' .
			$mini_cart_html .
			'</div>',
	);

	wp_send_json_success(
		array(
			'cart_subtotal' => $cart_subtotal,
			'cart_total'    => $cart_total,
			'cart_count'    => $cart_count,
			'shipping_html' => $shipping_html,
			'fragments'     => $fragments,
			'chosen_methods' => $chosen_methods,
		)
	);
}

?>