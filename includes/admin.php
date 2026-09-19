<?php
// Restrict specific page templates to be used only once. (Only run for the Page Attributes dropdown.)
function restrict_page_templates_to_one_page( $templates ) {

    if ( ! is_admin() ) {
        return $templates;
    }

    global $pagenow;

    if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
        return $templates;
    }

    $post_type = '';

    if ( isset( $_GET['post'] ) ) {
        $post_type = get_post_type( (int) $_GET['post'] );
    } elseif ( isset( $_GET['post_type'] ) ) {
        $post_type = sanitize_key( $_GET['post_type'] );
    }

    if ( 'page' !== $post_type ) {
        return $templates;
    }

    $restricted_templates = array(
        'template/template-about.php',
        'template/template-contact.php',
        'template/template-image_gallery.php',
        'template/template-video_library.php', 
        'template/template-login.php', 
        'template/template-register.php',
    );

    foreach ( $restricted_templates as $template_file ) {

        $used_pages = get_posts( array(
            'post_type'      => 'page',
            'posts_per_page' => 1,
            'post__not_in'   => array( get_the_ID() ),
            'meta_key'       => '_wp_page_template',
            'meta_value'     => $template_file,
            'fields'         => 'ids',
        ) );

        if ( ! empty( $used_pages ) ) {
            unset( $templates[ $template_file ] );
        }
    }

    return $templates;
}

add_filter(
    'theme_page_templates',
    'restrict_page_templates_to_one_page'
);


// Add store name and GSTIN custom fields in Woocommerce -> settings -> General
add_filter( 'woocommerce_general_settings', function( $settings ) {

    $new_settings = array();

    foreach ( $settings as $setting ) {

        $new_settings[] = $setting;

        if ( isset( $setting['id'] ) && 'woocommerce_store_address_2' === $setting['id'] ) {

            $new_settings[] = array(
                'title'    => __( 'Store Name', 'woocommerce' ),
                'desc'     => __( 'Enter your store/business name.', 'woocommerce' ),
                'id'       => 'woocommerce_store_name',
                'type'     => 'text',
                'default'  => '',
                'desc_tip' => true,
            );

            $new_settings[] = array(
                'title'    => __( 'GSTIN', 'woocommerce' ),
                'desc'     => __( 'Enter your GSTIN number.', 'woocommerce' ),
                'id'       => 'woocommerce_store_gstin',
                'type'     => 'text',
                'default'  => '',
                'desc_tip' => true,
            );
        }
    }

    return $new_settings;
} );

?>