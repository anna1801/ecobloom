<?php 
// Find post views
function count_post_views() {
    if (is_singular('post') && !is_admin() && !current_user_can('manage_options')) {

        $post_id = get_the_ID();
        $views   = (int) get_post_meta($post_id, 'post_views', true);

        update_post_meta($post_id, 'post_views', $views + 1);
    }
}
add_action('wp', 'count_post_views');

//remove <p> only inside blockquotes
function remove_p_from_blockquote($content) {
    $content = preg_replace(
        '/<blockquote>\s*<p>(.*?)<\/p>\s*<\/blockquote>/is',
        '<blockquote>$1</blockquote>',
        $content
    );
    return $content;
}
add_filter('the_content', 'remove_p_from_blockquote');

// redirect my account page to login for non logged users
add_action( 'template_redirect', function() {

    if ( ! is_account_page() || is_user_logged_in() ) {
        return;
    }

    if ( is_wc_endpoint_url( 'lost-password' ) ) {
        return;
    }

    $pages = get_pages( array(
        'meta_key'   => '_wp_page_template',
        'meta_value' => 'template/template-login.php',
        'number'     => 1,
    ) );

    if ( ! empty( $pages ) ) {
        wp_safe_redirect( get_permalink( $pages[0]->ID ) );
        exit;
    }

} );

?>