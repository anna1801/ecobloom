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


?>