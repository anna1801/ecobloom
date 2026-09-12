<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
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

do_action( 'woocommerce_before_cart' ); ?>

    <section class="py-5 cart-section">
        <div class="container py-3">
            <div class="row g-5">

                <div class="col-12 col-lg-8">
                    <div class="about-story-box p-4 bg-white">
                        <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
                            <?php do_action( 'woocommerce_before_cart_table' ); ?>
                            <div class="table-responsive">
                                <table class="shop_table_responsive cart woocommerce-cart-form__contents table align-middle border-light mb-0" cellspacing="0">
                                    <thead class="border-bottom">
                                        <tr class="text-uppercase text-muted fs-8">
                                            <th scope="col" class="py-3 product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
                                            <th scope="col" class="py-3 product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
                                            <th scope="col" class="py-3 product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
                                            <th scope="col" class="py-3 text-end product-subtotal"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
                                            <th scope="col" class="py-3 text-end product-remove"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="cartTableBody">
                                        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                                        <?php
                                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                                            $visible = apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key );

                                            if ( $_product instanceof WC_Product && $_product->exists() && $cart_item['quantity'] > 0 && $visible ) {

                                                $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                                ?>
                                                <tr id="cart-row-<?php echo esc_attr( $cart_item_key ); ?>" 
                                                    class="border-bottom cart-product-row woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>"
                                                    data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">

                                                    <td role="rowheader" class="py-4 product-name product-thumbnail" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <?php
                                                                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail',
                                                                                            $_product->get_image(
                                                                                                'woocommerce_thumbnail',
                                                                                                array(
                                                                                                    'class' => 'rounded-3 bg-pink-light p-2 cart-product-image',
                                                                                                    'style' => 'width: 72px; height: 72px;'
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
                                                               
                                                                echo '<div>';
                                                                    if ( ! $product_permalink ) {
                                                                        echo wp_kses_post( $product_name . '&nbsp;' );
                                                                    } else {

                                                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="text-decoration-none"><h6 class="fw-bold text-dark mb-1">%s</h6></a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                                                    }

                                                                    do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                                                    echo wc_get_formatted_cart_item_data( $cart_item ); 

                                                                    if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
                                                                    }
                                                                echo '</div>';
                                                            ?>
                                                        </div>
                                                    </td>

                                                    <td class="product-price py-4 fw-500 text-dark cart-unit-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
                                                        <?php
                                                            echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                                        ?>
                                                    </td>

                                                    <td class="product-quantity py-4" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
                                                        <div class="cart-item-qty bg-light px-2 py-1 rounded-pill border d-inline-flex" style="margin-top:0;">
                                                            <button class="qty-btn border-0 bg-transparent" type="button" onclick="cartPageQty(this, -1)"><i class="bi bi-dash"></i></button>
                                                            <?php
                                                            if ( $_product->is_sold_individually() ) {
                                                                $min_quantity = 1;
                                                                $max_quantity = 1;
                                                            } else {
                                                                $min_quantity = 0;
                                                                $max_quantity = $_product->get_max_purchase_quantity();
                                                            }

                                                            $product_quantity = woocommerce_quantity_input(
                                                                array(
                                                                    'input_name'   => "cart[{$cart_item_key}][qty]",
                                                                    'input_value'  => $cart_item['quantity'],
                                                                    'max_value'    => $max_quantity,
                                                                    'min_value'    => $min_quantity,
                                                                    'product_name' => $product_name,
                                                                    'classes'      => array(
                                                                        'input-text',
                                                                        'qty',
                                                                        'text',
                                                                        'fs-7',
                                                                        'fw-bold',
                                                                        'px-2',
                                                                        'qty-display',
                                                                    ),
                                                                    'readonly'     => true,
                                                                    'input_id'     => 'quantity_' . $cart_item_key,
                                                                ),
                                                                $_product,
                                                                false
                                                            );

                                                            echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                                                            ?>
                                                            <button class="qty-btn border-0 bg-transparent" type="button" onclick="cartPageQty(this, 1)"><i class="bi bi-plus"></i></button>
                                                        </div>
                                                    </td>

                                                    <td class="product-subtotal py-4 text-end fw-bold text-dark" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
                                                        <?php
                                                            echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                                        ?>
                                                    </td>

                                                    <td class="product-remove py-4 text-end">
                                                        <?php
                                                            echo apply_filters( 
                                                                'woocommerce_cart_item_remove_link',
                                                                sprintf(
                                                                    '<a role="button" href="%s" class="cart-remove-item btn btn-link text-danger p-1" data-cart-item-key="'. esc_attr( $cart_item_key ) .'" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="bi bi-trash3"></i></a>',
                                                                    esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                                    esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
                                                                    esc_attr( $product_id ),
                                                                    esc_attr( $_product->get_sku() )
                                                                ),
                                                                $cart_item_key
                                                            );
                                                        ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php do_action( 'woocommerce_cart_contents' ); ?>

                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pt-4 border-top mt-2">
                                <?php if ( wc_coupons_enabled() ) { ?>
                                    <div class="coupon d-flex gap-2">
                                        <label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> 
                                        <input type="text" name="coupon_code" class="input-text form-control checkout-input-modern px-4 fs-7" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon Code (e.g. ECO20)', 'woocommerce' ); ?>" /> 
                                        <button type="submit" class="btn btn-premium-gradient rounded-pill px-4 fs-7 fw-bold <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"> 
                                            <i class="bi bi-tag-fill me-1"></i> <?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?>
                                        </button>
                                        <?php do_action( 'woocommerce_cart_coupon' ); ?>
                                    </div>
                                <?php } ?>

                                <button type="submit" class="hidden btn btn-primary rounded-pill py-3 fw-bold shadow-sm d-flex justify-content-center align-items-center gap-2 <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>
                        
                                <?php do_action( 'woocommerce_cart_actions' ); ?>

                                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

                                <?php $shop_url = get_post_type_archive_link( 'product' ); ?>
                                <a href="<?php echo  esc_url($shop_url); ?>" class="text-magenta fw-bold text-decoration-none fs-7">
                                    <i class="bi bi-arrow-left me-1"></i> <?php esc_html_e( 'Continue Shopping', 'woocommerce' ); ?>
                                </a>
                            </div>

                            <?php do_action( 'woocommerce_after_cart_contents' ); ?>

                            <?php do_action( 'woocommerce_after_cart_table' ); ?>
                        </form>
                        <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <?php do_action( 'woocommerce_cart_collaterals' ); ?>
                </div>

            </div>
        </div>
    </section>

<?php do_action( 'woocommerce_after_cart' ); ?>
