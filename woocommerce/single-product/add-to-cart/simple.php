<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.2.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product ); 

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
        <div class="d-flex align-items-center gap-3 mb-4">
            <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

            <?php
            do_action( 'woocommerce_before_add_to_cart_quantity' );

            echo '<div class="cart-item-qty bg-light px-3 py-2 rounded-pill border d-flex align-items-center" style="margin-top: 0;">';
                echo '<button class="qty-btn border-0 bg-transparent" type="button" id="qtyMinusBtn"><i class="bi bi-dash"></i></button>';

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

                echo '<button class="qty-btn border-0 bg-transparent" type="button" id="qtyPlusBtn"><i class="bi bi-plus"></i></button>';
            echo '</div>';

            do_action( 'woocommerce_after_add_to_cart_quantity' );
            ?>

            <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" 
                    class="btn btn-primary rounded-pill flex-grow-1 py-3 fw-bold shadow-sm d-flex justify-content-center align-items-center gap-2 single_add_to_cart_button button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>">
                <i class="bi bi-bag-plus-fill"></i> Add to Shopping Bag
            </button>

            <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
        </div>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
