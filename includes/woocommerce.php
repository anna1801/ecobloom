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

?>