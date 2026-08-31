<?php
function ajax_blog_pagination() {

    $paged = isset($_POST['page'])
        ? absint($_POST['page'])
        : 1;

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => get_option('posts_per_page'),
        'paged'          => $paged,
        'post_status'    => 'publish',
    );

    $blog_query = new WP_Query($args);

    if ($blog_query->have_posts()):

        ob_start();
        
        echo '<div class="blog-grid">';
            while($blog_query->have_posts()): $blog_query->the_post();
                blog_grid(); 
            endwhile;
        echo '</div>';

        $current_page = $paged;
        $total_pages   = $blog_query->max_num_pages;

        blog_pagination($current_page, $total_pages);

        wp_reset_postdata();

        $html = ob_get_clean();

        wp_send_json_success(array(
            'html' => $html
        ));

    else:

        wp_send_json_error();

    endif;

}

add_action('wp_ajax_ajax_blog_pagination', 'ajax_blog_pagination');
add_action('wp_ajax_nopriv_ajax_blog_pagination', 'ajax_blog_pagination');


function blog_scripts() {
    if (is_home()) {
        wp_enqueue_script(
            'blog-ajax',
            get_template_directory_uri() . '/ajax/js/ajax_blog.js',
            array('jquery'),
            null,
            true
        );

        wp_localize_script('blog-ajax', 'blogAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
    }
}

add_action('wp_enqueue_scripts', 'blog_scripts');

?>