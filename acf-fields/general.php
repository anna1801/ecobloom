<?php 
// automatically add class to table tag
add_filter( 'acf/format_value/type=wysiwyg', function( $value ) {

    if ( empty( $value ) ) {
        return $value;
    }

    return preg_replace(
        '/<table(?![^>]*\bclass=)([^>]*)>/i',
        '<table class="table table-bordered align-middle"$1>',
        $value
    );

}, 10 );

// Custom field for badges
add_filter('mce_buttons', function ($buttons) {
    $buttons[] = 'custom_badge';
    return $buttons;
});

add_filter('mce_external_plugins', function ($plugins) {
    $plugins['custom_badge'] = get_template_directory_uri() . '/acf-fields/js/acf-badges.js';
    return $plugins;
});

// css file for admin
add_filter('mce_css', function ($mce_css) {

    $mce_css .= ',' . get_template_directory_uri() . '/acf-fields/css/editor.css';

    return $mce_css;
});

?>