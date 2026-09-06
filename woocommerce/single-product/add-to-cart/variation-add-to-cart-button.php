<?php
/**
 * Single variation cart button
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.5.2
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>
<div class="woocommerce-variation-add-to-cart variations_button d-flex align-items-center gap-3 mb-4">
	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<?php do_action( 'woocommerce_before_add_to_cart_quantity' ); ?>

    <div class="cart-item-qty bg-light px-3 py-2 rounded-pill border d-flex align-items-center" style="margin-top: 0;">
        <button class="qty-btn border-0 bg-transparent" type="button" id="qtyMinusBtn"><i class="bi bi-dash"></i></button>
        <?php
            woocommerce_quantity_input(
                array(
                    'min_value'   => $product->get_min_purchase_quantity(),
                    'max_value'   => $product->get_max_purchase_quantity(),
                    'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), 
                    'input_id'    => 'productQtyInput',
                    'classes'     => array(
                        'border-0',
                        'bg-transparent',
                        'text-center',
                        'fw-bold',
                        'text-dark',
                    ),
                    'readonly'         => true,
                )
            );
        ?>
        <button class="qty-btn border-0 bg-transparent" type="button" id="qtyPlusBtn"><i class="bi bi-plus"></i></button>
    </div>

	<?php do_action( 'woocommerce_after_add_to_cart_quantity' ); ?>

	<button type="submit" 
        class="btn btn-primary rounded-pill flex-grow-1 py-3 fw-bold shadow-sm d-flex justify-content-center align-items-center gap-2 single_add_to_cart_button button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>">
        <i class="bi bi-bag-plus-fill"></i> Add to Shopping Bag
    </button>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
	<input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>