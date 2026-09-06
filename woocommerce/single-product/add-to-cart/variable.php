<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
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

global $product;

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
	<?php else : ?>

		<?php do_action( 'woocommerce_before_single_variation' ); ?>

		<?php woocommerce_single_variation(); ?>

        <div class="mb-4 variations" cellspacing="0" role="presentation">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label for="cupSizeSelect" class="fw-bold text-dark fs-7">Select Cup Size:</label>
                <!-- to do -->
                <a href="#" class="text-magenta small text-decoration-none fw-semibold"
                    data-bs-toggle="modal" data-bs-target="#sizeChartModal"><i
                        class="bi bi-ruler me-1"></i>View Size Chart</a>
                <!-- to do end-->
            </div>
            <?php foreach ( $attributes as $attribute_name => $options ) : ?>
                <?php
                    wc_dropdown_variation_attribute_options(
                        array(
                            'options'   => $options,
                            'attribute' => $attribute_name,
                            'product'   => $product,
                            'class'     => 'form-select form-select-lg rounded-pill border-2 border-magenta px-4 py-2 fw-semibold text-dark shadow-sm',
                            'id'        => 'cupSizeSelect',
                            'aria-label'=> 'Select Menstrual Cup Size',
                        )
                    );
                   // echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#" aria-label="' . esc_attr__( 'Clear options', 'woocommerce' ) . '"> <i class="bi bi-x-circle"></i> </a>' ) ) : '';
                ?>
            <?php endforeach; ?>
        </div>

		<div class="reset_variations_alert screen-reader-text" role="alert" aria-live="polite" aria-relevant="all"></div>
		<?php
		// Reset snapshot for cases where a theme/plugin loads the variation form later, like quick-view modals.
		if ( \Automattic\WooCommerce\Internal\VariationGallery\Package::is_enabled() ) :
			?>
			<script type="text/template" class="wc-product-gallery-default-template"><?php echo wc_get_product_gallery_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></script>
			<?php
		endif;
		?>
		<?php do_action( 'woocommerce_after_variations_table' ); ?>

		<div class="single_variation_wrap">
			<?php //do_action( 'woocommerce_single_variation' ); ?>
			<?php woocommerce_single_variation_add_to_cart_button(); ?>
		</div>

		<?php do_action( 'woocommerce_after_single_variation' ); ?>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
