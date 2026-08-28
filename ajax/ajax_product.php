<?php
function ajax_product_pagination() {

    $paged = isset($_POST['page'])
        ? absint($_POST['page'])
        : 1;

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => get_option('posts_per_page'),
        'paged'          => $paged,
        'post_status'    => 'publish',
    );

    $product_query = new WP_Query($args);

    if ($product_query->have_posts()):

        ob_start();
        
        echo '<div class="row g-4">';
            while($product_query->have_posts()): $product_query->the_post();
                product_grid(); 
            endwhile;
        echo '</div>';

        $current_page = $paged;
        $total_pages   = $product_query->max_num_pages;

        product_pagination($current_page, $total_pages);

        wp_reset_postdata();

        $html = ob_get_clean();

        wp_send_json_success(array(
            'html' => $html
        ));

    else:

        wp_send_json_error();

    endif;

}

add_action('wp_ajax_ajax_product_pagination', 'ajax_product_pagination');
add_action('wp_ajax_nopriv_ajax_product_pagination', 'ajax_product_pagination');


function product_scripts() {

    wp_enqueue_script(
        'product-ajax',
        get_template_directory_uri() . '/ajax/js/ajax_product.js',
        array('jquery'),
        null,
        true
    );

    wp_localize_script('product-ajax', 'productAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));

}

add_action('wp_enqueue_scripts', 'product_scripts');

?>