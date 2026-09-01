<?php
function ajax_product_pagination() {

    $paged = isset($_POST['page'])
        ? max(1, absint($_POST['page']))
        : 1;

    $category = isset($_POST['category'])
        ? sanitize_text_field($_POST['category'])
        : '';

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    );

    if (!empty($category) && $category !== 'all') {

        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        );
    }

    $product_query = new WP_Query($args);

    $products = array();

    if (!empty($product_query->posts)) {

        foreach ($product_query->posts as $product_id) {

            $product = wc_get_product($product_id);

            if (!$product) {
                continue;
            }

            if ($product->is_type('variable')) {

                $variations = $product->get_available_variations();

                foreach ($variations as $variation_data) {

                    $variation = wc_get_product(
                        $variation_data['variation_id']
                    );

                    if (!$variation) {
                        continue;
                    }

                    if (!$variation->exists()) {
                        continue;
                    }

                    $products[] = array(
                        'type'       => 'variation',
                        'product_id' => $product_id,
                        'variation'  => $variation,
                    );
                }

            } else {

                $products[] = array(
                    'type'       => 'product',
                    'product_id' => $product_id,
                    'variation'  => $product,
                );

            }
        }
    }

    $per_page = get_option('posts_per_page');

    $total_products = count($products);

    $total_pages = ceil($total_products / $per_page);

    $offset = ($paged - 1) * $per_page;

    $products_for_page = array_slice(
        $products,
        $offset,
        $per_page
    );

    if (!empty($products_for_page)) {

        ob_start();

        echo '<div class="row g-4">';

        global $product;

        foreach ($products_for_page as $item) {

            $product = $item['variation'];

            $GLOBALS['current_product_variation'] = $product;

            product_grid();
        }

        echo '</div>';

        product_pagination(
            $paged,
            $total_pages
        );

        wp_reset_postdata();

        $html = ob_get_clean();

        wp_send_json_success(
            array(
                'html' => $html,
            )
        );
    }

    wp_send_json_error();
}

add_action( 'wp_ajax_ajax_product_pagination', 'ajax_product_pagination' );

add_action( 'wp_ajax_nopriv_ajax_product_pagination', 'ajax_product_pagination' );


function product_scripts() {
    if (is_shop() || is_post_type_archive('product') || is_tax('product_cat') || is_tax('product_tag')) {
        wp_enqueue_script(
            'product-ajax',
            get_template_directory_uri() . '/ajax/js/ajax_product.js',
            array('jquery'),
            null,
            true
        );

        $current_category = '';

        if (is_product_category()) {
            $current_category = get_queried_object()->slug;
        }

        wp_localize_script('product-ajax', 'productAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'category' => $current_category,
        ));
    }
}

add_action('wp_enqueue_scripts', 'product_scripts');

?>