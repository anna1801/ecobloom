<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
// do_action( 'woocommerce_cart_is_empty' ); /* this is a notice */

if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
    <section class="py-5">
        <div class="container py-3">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-9">
                    <div class="about-story-box p-5 bg-white text-dark body-content text-center" style="line-height: 1.8;">

                        <h2 class="wp-block-heading has-text-align-center with-empty-cart-icon wc-block-cart__empty-cart__title">  
                            <?php esc_html_e('Your basket is currently empty!', 'woocommerce'); ?>
                        </h2>

                        <p class="text-muted mb-4">
                            <?php esc_html_e('Add some EcoBloom products to get started.', 'woocommerce'); ?>
                        </p>

                        <?php $shop_url = get_post_type_archive_link( 'product' ); ?>
                        <a class="btn-globe wc-backward<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" 
                        href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', esc_url($shop_url) ) ); ?>">
                            <?php echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', __( 'Return to shop', 'woocommerce' ) ) ); ?>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
