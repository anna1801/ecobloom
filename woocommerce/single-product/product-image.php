<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.5.0
 */

use Automattic\WooCommerce\Enums\ProductType;

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();

$attachment_ids    = $product->get_gallery_image_ids();

$gallery_ids = array();

if ( $post_thumbnail_id ) {
	$gallery_ids[] = $post_thumbnail_id;
}

if ( $attachment_ids ) {
	$gallery_ids = array_merge( $gallery_ids, $attachment_ids );
}

$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
	)
);
?>
<div class="col-12 col-lg-6">
    <div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
        
        <?php woocommerce_show_product_sale_flash(); ?>

        <div class="woocommerce-product-gallery__wrapper">
            <?php if ( $gallery_ids ) : ?>
                <div class="about-story-box text-center bg-pink-light p-5 mb-3 position-relative">
                    <?php product_badge($product) ?>
                    <button
                        type="button"
                        class="btn btn-light rounded-circle shadow-sm position-absolute bottom-0 end-0 m-3 d-flex align-items-center justify-content-center"
                        style="width: 44px; height: 44px;"
                        onclick="openImageZoom()"
                        title="<?php esc_attr_e( 'Zoom Image', 'ecobloom' ); ?>"
                        data-bs-toggle="modal"
                        data-bs-target="#productImageModal">
                        <i class="bi bi-zoom-in text-magenta fs-5"></i>
                    </button>
                    <?php
                        $image_id = $post_thumbnail_id;

                        if ( ! $image_id && ! empty( $gallery_ids ) ) {
                            $image_id = $gallery_ids[0];
                        }

                        if ( $image_id ) :
                            $image_url = wp_get_attachment_image_url( $image_id, 'woocommerce_single' );
                            $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

                            if ( ! $image_alt ) {
                                $image_alt = get_the_title();
                            }
                            ?>
                            <img
                                src="<?php echo esc_url( $image_url ); ?>"
                                alt="<?php echo esc_attr( $image_alt ); ?>"
                                id="mainProductImg"
                                class="img-fluid"
                                style="max-height: 400px; cursor: zoom-in;" 
                                onclick="openImageZoom()">
                            <?php
                        endif;
                    ?>
                </div>
                <?php if ( ! empty( $attachment_ids ) ) : ?>
                    <div class="row g-3 product-custom-thumbnails">
                        <?php foreach ( $gallery_ids as $index => $image_id ) : ?>
                            <?php
                            $full_image = wp_get_attachment_image_url( $image_id, 'full' );
                            $thumb      = wp_get_attachment_image(
                                $image_id,
                                'woocommerce_full',
                                false,
                                array(
                                    'class' => 'img-fluid',
                                    'style' => 'max-height: 70px;',
                                    'alt'   => get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ?: get_the_title(),
                                )
                            );

                            $active_class = 0 === $index
                                ? 'active border-magenta bg-pink-light'
                                : ' bg-light';
                            ?>
                            <div class="col-3">
                                <div class="product-thumb-item <?php echo esc_attr( $active_class ); ?> border rounded-3 p-2 text-center cursor-pointer"
                                    data-image="<?php echo esc_url( $full_image ); ?>"
                                    data-image-id="<?php echo esc_attr( $image_id ); ?>"
                                    style="cursor: pointer;">
                                    <?php echo $thumb; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="about-story-box text-center bg-pink-light p-5 mb-3 position-relative">
                    <?php
                    echo wc_placeholder_img(
                        'woocommerce_single',
                        array(
                            'class' => 'img-fluid',
                            'id'    => 'mainProductImg',
                            'style' => 'max-height: 400px; opacity: 1;',
                        )
                    );
                    ?>
                </div>
            <?php endif; ?>
        </div>
	</div>
</div>
