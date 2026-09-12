<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 11.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' ); ?>

<?php if ( WC()->cart && ! WC()->cart->is_empty() ) : ?>

	<div class="p-4 woocommerce-mini-cart cart_list product_list_widget <?php echo esc_attr( $args['list_class'] ); ?>">
		<?php
		do_action( 'woocommerce_before_mini_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			/**
			 * Filter whether this cart item is visible in the mini-cart.
			 *
			 * @since 1.6.0
			 * @param bool   $visible       Whether the cart item is visible. Default true.
			 * @param array  $cart_item     The cart item data.
			 * @param string $cart_item_key The cart item key.
			 */
			$visible = apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key );

			if ( $_product instanceof WC_Product && $_product->exists() && $cart_item['quantity'] > 0 && $visible ) {
				/**
				 * This filter is documented in woocommerce/templates/cart/cart.php.
				 *
				 * @since 2.1.0
				 */
				$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail', array( 'class' => 'cart-item-img' )), $cart_item, $cart_item_key );
				$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<div id="minicart-row-<?php echo esc_attr( $cart_item_key ); ?>" class="cart-item-row woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">

					<?php echo $thumbnail  ?>

                    <div class="cart-item-details">
                        <div class="cart-item-title"><a href="<?php echo esc_url( $product_permalink ); ?>" class="text-decoration-none" style="color: unset;"><?php echo wp_kses_post( $product_name ); ?></a></div>
                        <div class="cart-item-price"><?php echo $product_price; ?></div>
                        <div class="cart-item-qty">
                            <button class="qty-btn" onclick="miniCartQty('minicart-row-<?php echo esc_attr( $cart_item_key ); ?>', -1)" title="Decrease">-</button>
							<span class="qty-display-minicart px-2 fw-semibold small" 
								data-qty="<?php echo esc_attr( $cart_item['quantity'] ); ?>"
    							data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
								<?php echo $cart_item['quantity']; ?>
							</span>
                            <button class="qty-btn" onclick="miniCartQty('minicart-row-<?php echo esc_attr( $cart_item_key ); ?>', 1)" title="Increase">+</button>
                        </div>
                    </div>
                   
					<?php
					echo apply_filters( 
						'woocommerce_cart_item_remove_link',
						sprintf(
							'<button role="button" href="%s" class="mini-cart-remove-item btn btn-link text-muted p-1 remove remove_from_cart_button" aria-label="%s" data-cart-item-key="'. esc_attr( $cart_item_key ) .'" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s" data-success_message="%s"><i class="bi bi-trash3"></i></button>',
							esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
							/* translators: %s is the product name */
							esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
							esc_attr( $product_id ),
							esc_attr( $cart_item_key ),
							esc_attr( $_product->get_sku() ),
							/* translators: %s is the product name */
							esc_attr( sprintf( __( '&ldquo;%s&rdquo; has been removed from your cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) )
						),
						$cart_item_key
					);
					?>

					<?php echo wc_get_formatted_cart_item_data( $cart_item );  ?>
	
				</div>
				<?php
			}
		}

		do_action( 'woocommerce_mini_cart_contents' );
		?>
	</div>

	<div class="cart-footer-box">
		<div class="d-flex justify-content-between mb-2 woocommerce-mini-cart__total total">
			<span class="text-muted">Subtotal</span>
			<span class="fw-bold" id="cartSubtotal"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
		</div>

		<?php
		$packages = WC()->shipping()->get_packages();

		if ( ! empty( $packages ) ) :
			foreach ( $packages as $index => $package ) :

				$available_methods = isset( $package['rates'] ) ? $package['rates'] : array();

				if ( empty( $available_methods ) ) {
					continue;
				}

				$chosen_methods = WC()->session->get( 'chosen_shipping_methods', array() );
				$chosen_method  = isset( $chosen_methods[ $index ] ) ? $chosen_methods[ $index ] : '';

				$shipping_zone = WC_Shipping_Zones::get_zone_matching_package( $package );
				$zone_name     = $shipping_zone->get_zone_name();
				?>

				<div class="d-flex justify-content-between mb-3 woocommerce-shipping-totals shipping">

					<span class="text-muted">
						<?php echo esc_html( $zone_name ); ?>
					</span>

					<span class="text-dark fw-500 shipment-value" data-title="<?php echo esc_attr( $zone_name ); ?>">

						<ul class="woocommerce-shipping-methods mini-cart-shipping-methods">

							<?php foreach ( $available_methods as $method ) : ?>

								<?php
								$method_id = $method->id;
								$input_id  = 'shipping_method_' . $index . '_' . sanitize_title( $method_id );

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
										<?php checked( $is_checked, true ); ?>
									>

									<label
										for="<?php echo esc_attr( $input_id ); ?>"
										class="shipping-method-label text-success fw-bold"
									>
										<?php echo wp_kses_post( wc_cart_totals_shipping_method_label( $method ) ); ?>
									</label>

									<?php do_action( 'woocommerce_after_shipping_rate', $method, $index ); ?>

								</li>

							<?php endforeach; ?>

						</ul>

					</span>
				</div>

			<?php endforeach; ?>
		<?php endif; ?>

		<?php
			$taxes = WC()->cart->get_tax_totals();
			if ( ! empty( $taxes ) ) {
				foreach ( $taxes as $tax ) {

				if( WC()->cart->display_prices_including_tax() ) {
					$taxval = 'Included in price';
				} else {
					$taxval = wp_kses_post( $tax->formatted_amount );
				}
					?>
					<div class="d-flex justify-content-between mb-3">
						<span class="text-muted"><?php echo esc_html( $tax->label ); ?></span>
						<span class="text-dark fw-500"><?php echo $taxval; ?></span>
					</div>
					<?php
				}
			}
		?>

		<hr class="my-2">

		<div class="d-flex justify-content-between mb-4">
			<span class="fs-5 fw-bold text-dark">Total</span>
			<span class="fs-5 fw-bold text-magenta" id="cartTotalPrice"> <?php echo WC()->cart->get_total(); ?> </span>
		</div>

		<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

		<a class="btn btn-ecobloom-primary w-100 justify-content-center mb-2"
			href="<?php echo esc_url( wc_get_checkout_url() ); ?>">
			<span>Proceed to Checkout</span>
			<i class="bi bi-arrow-right"></i>
		</a>
		<a class="btn btn-outline-secondary w-100 rounded-pill btn-sm mt-2" 
			href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>">
			Continue Shopping
		</a>

		<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
	</div>

<?php else : ?>

	<div class="cart-empty-state">
		<div class="cart-empty-icon"><i class="bi bi-bag-x"></i></div>
		<h5 class="fw-bold mb-2"> <?php esc_html_e( 'Your Bag is Empty', 'woocommerce' ); ?></h5>
		<p class="text-muted small mb-4"> <?php esc_html_e( 'Discover reliable comfort for every stage of life.', 'woocommerce' ); ?></p>
		<a href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>" class="btn btn-ecobloom-primary btn-sm" data-bs-dismiss="offcanvas">Start Shopping</a>
	</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
