<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://woocommerce.com/document/template-structure/
 * @package    WooCommerce\Templates
 * @version    1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


global $product;

$product = wc_get_product(get_the_ID());
$product_name = $product->get_name();
$attribute_name = '';

if (!$product) {
    get_footer();
    return;
}

if ($product->is_type('variable')) {
    $variation_id = $product->get_matching_variation($_GET);

    if ($variation_id) {
        $variation = wc_get_product($variation_id);

        $name = get_variation_display_name($variation);
    } else {
        $name = $product->get_name();
    }

    // variation
    $attributes = $product->get_variation_attributes();
    if (!empty($attributes)) {
        $attribute_key = array_key_first($attributes);
        $taxonomy = str_replace('attribute_', '', $attribute_key);
        if (taxonomy_exists($taxonomy)) {
            $attribute_name = wc_attribute_label($taxonomy, $product);
        } else {
            $attribute_name = wc_attribute_label($attribute_key, $product);
        }
    }
} else {
    $name = $product->get_name();
}

?>

<?php product_tag($product, 'mb-2'); ?>

<h1 class="product-title fs-2 fw-bold text-dark mb-2" data-product-name="<?php echo esc_attr($product_name); ?>" data-attribute-name="<?php echo esc_attr($attribute_name); ?>" >
    <?php echo $name; ?>
</h1>