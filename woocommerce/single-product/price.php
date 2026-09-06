<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( ! $product || $product->is_type( 'variable' ) ) {
	return;
}

$price         = $product->get_price();
$regular_price = $product->get_regular_price();
$sale_price    = $product->get_sale_price();

if ( $product->is_on_sale() && $regular_price && $sale_price ) {
	$discount = round( ( (float) $regular_price - (float) $sale_price ) / (float) $regular_price * 100 );
}

?>

<div class="d-flex align-items-center gap-3 mb-4">
    <span class="fs-2 fw-bold text-dark"><?php echo eco_price( $price ); ?></span>
    <?php if ( $product->is_on_sale() && $regular_price ) : ?>
        <span class="text-decoration-line-through text-muted fs-5">
            <?php echo eco_price( $regular_price ); ?>
        </span>

        <?php if ( isset( $discount ) ) : ?>
            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold">
                Save <?php echo esc_html( $discount ); ?>%
            </span>
        <?php endif; ?>
    <?php endif; ?>
</div>
