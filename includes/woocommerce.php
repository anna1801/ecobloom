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
add_action('pre_get_posts', function ($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('product')) {
        $posts_per_page = get_option('posts_per_page');
        $query->set('posts_per_page', $posts_per_page);
    }
});
?>