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

?>