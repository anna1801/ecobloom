<?php 
// Price with decimal 
function eco_price( $price ) {
    return wc_price(
        $price,
        [
            'decimals' => ( $price == (int) $price ) ? 0 : wc_get_price_decimals()
        ]
    );
}

// set number of products per page in shop page (from Settings → Reading → Blog pages show at most)
// set Product archive_order_by_date
add_action('pre_get_posts', function ($query) {

    if (
        ! is_admin() &&
        $query->is_main_query() &&
        (
            is_post_type_archive( 'product' ) ||
            is_tax( 'product_cat' ) ||
            is_tax( 'product_tag' )
        )
    ) {

        $query->set( 'posts_per_page', get_option( 'posts_per_page' ) );
        $query->set( 'orderby', 'date' );
        $query->set( 'order', 'DESC' );
    }

});

// To create product URL based on attribute
function get_variation_product_url($variation) {

    if (!$variation || !$variation->is_type('variation')) {
        return $variation ? $variation->get_permalink() : '';
    }

    $parent_id = $variation->get_parent_id();

    $url = get_permalink($parent_id);

    $attributes = $variation->get_variation_attributes();

    if (!empty($attributes)) {

        foreach ($attributes as $attribute_name => $attribute_value) {

            if ($attribute_value === '') {
                continue;
            }

            $url = add_query_arg(
                $attribute_name,
                $attribute_value,
                $url
            );
        }
    }

    return $url;
}

// To create product name by variation
function get_variation_display_name($variation) {

    if (!$variation || !$variation->is_type('variation')) {
        return $variation ? $variation->get_name() : '';
    }

    $parent = wc_get_product($variation->get_parent_id());

    if (!$parent) {
        return $variation->get_name();
    }

    $attributes = $variation->get_attributes();

    $variation_names = array();

    foreach ($attributes as $attribute_name => $attribute_value) {

        if (!$attribute_value) {
            continue;
        }

        $taxonomy = str_replace(
            'attribute_',
            '',
            $attribute_name
        );

        $attribute_label = wc_attribute_label(
            $taxonomy,
            $parent
        );

        if (taxonomy_exists($taxonomy)) {

            $term = get_term_by(
                'slug',
                $attribute_value,
                $taxonomy
            );

            if ($term && !is_wp_error($term)) {

                $attribute_value_name = $term->name;

            } else {

                $attribute_value_name = $attribute_value;
            }

        } else {

            $attribute_value_name = $attribute_value;
        }

        $variation_names[] = $attribute_label . ' ' . $attribute_value_name;
    }

    if (!empty($variation_names)) {

        return $parent->get_name()
            . ' — '
            . implode(', ', $variation_names);
    }

    return $parent->get_name();
}

/* Display WooCommerce variations as separate products in Shop / Product Category / Product Tag archives.*/
add_filter('woocommerce_product_loop_start', function ($html) {
    return $html;
});

add_action('woocommerce_before_shop_loop_item', function () {
    global $product;

    if (!$product || !$product->is_type('variable')) {
        return;
    }
    $variations = $product->get_available_variations();

    if (empty($variations)) {
        return;
    }
}, 1);

/* Replace variable products in the main WooCommerce loop with their individual variations. */
add_filter('the_posts', function ($posts, $query) {

    if (is_admin() || !$query->is_main_query()) {
        return $posts;
    }

    if (
        !is_shop() &&
        !is_product_category() &&
        !is_product_tag()
    ) {
        return $posts;
    }

    $new_posts = [];

    foreach ($posts as $post) {

        if ($post->post_type !== 'product') {
            $new_posts[] = $post;
            continue;
        }

        $product = wc_get_product($post->ID);

        if (!$product || !$product->is_type('variable')) {
            $new_posts[] = $post;
            continue;
        }

        $variations = $product->get_available_variations();

        foreach ($variations as $variation) {

            $variation_id = $variation['variation_id'];

            $variation_post = get_post($variation_id);

            if (!$variation_post) {
                continue;
            }

            $variation_post = clone $variation_post;

            $variation_post->post_type = 'product';

            $variation_post->parent_product_id = $product->get_id();

            $variation_post->variation_id = $variation_id;

            $new_posts[] = $variation_post;
        }
    }

    return $new_posts;

}, 20, 2);


/* Make wc_get_product() return the variation when WooCommerce loop receives our fake product post. */
add_filter('woocommerce_product_get_name', function ($name, $product) {

    if (isset($product->variation_id) && $product->variation_id) {

        $variation = wc_get_product($product->variation_id);

        if ($variation) {

            $attributes = $variation->get_attributes();

            if (!empty($attributes)) {

                $attribute_names = [];

                foreach ($attributes as $attribute => $value) {

                    if ($value) {
                        $attribute_names[] = ucfirst($value);
                    }
                }

                if (!empty($attribute_names)) {
                    return $name . ' – ' . implode(', ', $attribute_names);
                }
            }
        }
    }

    return $name;

}, 10, 2);

// enqueue js file for product variable url attribute
function product_variation_url_script() {

    if (is_product()) {

        wp_enqueue_script(
            'product-variation-url',
            get_template_directory_uri() . '/assets/js/product-variation-url.js',
            array('jquery', 'wc-add-to-cart-variation'),
            null,
            true
        );

    }

}
add_action('wp_enqueue_scripts','product_variation_url_script');

// add class to body
add_filter('body_class', function ($classes) {
    if (is_product()) {
        $classes[] = 'woocommerce-block-theme-has-button-styles';
    }
    return $classes;
});

// Price style in single product page
add_filter( 'woocommerce_available_variation', function( $variation_data, $product, $variation ) {

    if ( ! is_product() ) {
        return $variation_data;
    }

    $price         = (float) $variation->get_price();
    $regular_price = (float) $variation->get_regular_price();

    if ( $regular_price > $price ) {
        $saving = $regular_price - $price;
        $saving_percent = round( ( $saving / $regular_price ) * 100 );

        $variation_data['price_html'] = sprintf(
            '<div class="d-flex align-items-center gap-3 mb-4">
                <span class="fs-2 fw-bold text-dark">%s</span>
                <span class="text-decoration-line-through text-muted fs-5">%s</span>
                <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold">Save %s%%</span>
            </div>',
            eco_price( $price ),
            eco_price( $regular_price ),
            $saving_percent
        );
    } else {
        $variation_data['price_html'] = sprintf(
            '<div class="d-flex align-items-center gap-3 mb-4">
                <span class="fs-2 fw-bold text-dark">%s</span>
            </div>',
            eco_price( $price )
        );
    }

    return $variation_data;

}, 10, 3 );

// insert default short description to validate variation description
add_filter( 'woocommerce_available_variation', function( $variation_data, $product, $variation ) {

	$variation_data['parent_short_description'] = apply_filters(
		'woocommerce_short_description',
		$product->get_short_description()
	);

	return $variation_data;

}, 10, 3 );

// Remove product_meta (category and tag) from single product page
add_action( 'wp', function() {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
} );

// remove sale flash from the top and added inside product-image.php
remove_action(
    'woocommerce_before_single_product_summary',
    'woocommerce_show_product_sale_flash',
    10
);

// allow duplicate comment/review
add_filter( 'duplicate_comment_id', function( $duplicate_id ) {
    if ( isset( $_POST['comment_post_ID'] ) ) {
        $post_id = absint( $_POST['comment_post_ID'] );

        if ( 'product' === get_post_type( $post_id ) ) {
            return 0;
        }
    }

    return $duplicate_id;
} );

// include attribute term description along with name in product page attribute dropdown
add_filter( 'woocommerce_variation_option_name', 'custom_variation_option_name', 10, 4 );

function custom_variation_option_name( $option, $term, $attribute, $product ) {

    $attribute_name = wc_attribute_label( $attribute, $product );

    if ( $term instanceof WP_Term && ! empty( $term->description ) ) {
        $option = $attribute_name . ' ' . $option . ' ' . wp_strip_all_tags( $term->description );
    } else {
        $option = $attribute_name . ' ' . $option;
    }

    return $option;
}

// remove default tabs and related products from single product
add_action( 'wp', function() {
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
} );

// Remove default product description from dashboard
add_action( 'init', function() {
    remove_post_type_support( 'product', 'editor' );
} );


?>