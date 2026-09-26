<?php

function custom_notify_me_html($product_id, $variation_id = 0) {

    return '
        <div class="custom-notify-me">

            <button type="button"
                class="button notify-me-button btn btn-outline-dark rounded-pill px-4 py-2 fw-semibold fs-7 shadow-sm"
                data-product-id="' . esc_attr($product_id) . '"
                data-variation-id="' . esc_attr($variation_id) . '">

                Notify Me <i class="bi bi-bell ms-1"></i>

            </button>

            <div class="notify-me-message"></div>

        </div>
    ';
}


/**
 * =========================================================
 * VARIABLE PRODUCTS
 * Add Notify Me after "Out of stock"
 * =========================================================
 */

add_filter(
    'woocommerce_available_variation',
    'custom_notify_me_variable_product',
    10,
    3
);

function custom_notify_me_variable_product($data, $product, $variation) {

    if (!is_user_logged_in()) {
        return $data;
    }

    $product_badge = get_field(
        'product_badge',
        $product->get_id()
    );

    $enable_notify = $product_badge['value'] ?? '';

    if ($enable_notify !== 'coming_soon') {
        return $data;
    }

    if ($variation->get_stock_status() !== 'outofstock') {
        return $data;
    }

    $data['availability_html'] .= custom_notify_me_html(
        $product->get_id(),
        $variation->get_id()
    );

    return $data;
}


/**
 * =========================================================
 * SIMPLE PRODUCTS
 * Add Notify Me after "Out of stock"
 * =========================================================
 */

add_filter('woocommerce_get_stock_html', 'custom_notify_me_simple_product', 20, 2);

function custom_notify_me_simple_product($availability_html, $product) { 

    if (!is_user_logged_in()) {
        return $availability_html;
    }

    if (!$product->is_type('simple')) {
        return $availability_html;
    }

    $product_badge = get_field(
        'product_badge',
        $product->get_id()
    );

    $enable_notify = $product_badge['value'] ?? '';

    if ($enable_notify !== 'coming_soon') {
        return $availability_html;
    }

    if ($product->get_stock_status() !== 'outofstock') {
        return $availability_html;
    }

    $availability_html .= custom_notify_me_html(
        $product->get_id()
    );

    return $availability_html;
}


/**
 * =========================================================
 * AJAX - SAVE NOTIFY ME REQUEST
 * Supports simple + variable products
 * =========================================================
 */

add_action(
    'wp_ajax_custom_product_notify_me',
    'custom_product_notify_me'
);

function custom_product_notify_me() {

    // Security
    check_ajax_referer(
        'notify_me_nonce',
        'nonce'
    );

    // Logged-in users only
    if (!is_user_logged_in()) {

        wp_send_json_error([
            'message' => 'Please log in to use this feature.'
        ]);
    }


    $user_id = get_current_user_id();

    $product_id = absint(
        $_POST['product_id'] ?? 0
    );

    $variation_id = absint(
        $_POST['variation_id'] ?? 0
    );


    /**
     * -----------------------------------------------------
     * Determine product to monitor
     * -----------------------------------------------------
     *
     * Simple:
     * product_id = 123
     * variation_id = 0
     *
     * Variable:
     * product_id = 123
     * variation_id = 456
     */

    if ($variation_id > 0) {

        // Variable product
        $product_to_check = wc_get_product(
            $variation_id
        );

        if (
            !$product_to_check ||
            !$product_to_check->is_type('variation')
        ) {

            wp_send_json_error([
                'message' => 'Variation not found.'
            ]);
        }

        // Save variation ID
        $notify_id = $variation_id;

        // Parent product ID for ACF
        $acf_product_id = $product_to_check->get_parent_id();

    } else {

        // Simple product
        $product_to_check = wc_get_product(
            $product_id
        );

        if (!$product_to_check) {

            wp_send_json_error([
                'message' => 'Product not found.'
            ]);
        }

        // Save product ID
        $notify_id = $product_id;

        // ACF belongs to this product
        $acf_product_id = $product_id;
    }


    /**
     * -----------------------------------------------------
     * Check stock
     * -----------------------------------------------------
     */

    if (
        $product_to_check->get_stock_status() !== 'outofstock'
    ) {

        wp_send_json_error([
            'message' => 'This product is already in stock.'
        ]);
    }


    /**
     * -----------------------------------------------------
     * Check ACF product badge
     * -----------------------------------------------------
     */

    $product_badge = get_field(
        'product_badge',
        $acf_product_id
    );

    $enable_notify = $product_badge['value'] ?? '';

    if ($enable_notify !== 'coming_soon') {

        wp_send_json_error([
            'message' => 'Notification is not available for this product.'
        ]);
    }


    /**
     * -----------------------------------------------------
     * Get existing subscriptions
     * -----------------------------------------------------
     */

    $notifications = get_user_meta(
        $user_id,
        '_notify_me_products',
        true
    );

    if (!is_array($notifications)) {
        $notifications = [];
    }


    /**
     * -----------------------------------------------------
     * Already subscribed?
     * -----------------------------------------------------
     */

    if (
        in_array(
            $notify_id,
            $notifications,
            true
        )
    ) {

        wp_send_json_success([
            'message' => 'You are already subscribed for this product.'
        ]);
    }


    /**
     * -----------------------------------------------------
     * Save subscription
     * -----------------------------------------------------
     */

    $notifications[] = $notify_id;

    update_user_meta(
        $user_id,
        '_notify_me_products',
        $notifications
    );


    /**
     * -----------------------------------------------------
     * Success
     * -----------------------------------------------------
     */

    wp_send_json_success([
        'message' => 'We will notify you when this product is back in stock.'
    ]);
}


/**
 * =========================================================
 * ENQUEUE AJAX JS
 * =========================================================
 */

add_action(
    'wp_enqueue_scripts',
    function () {

        wp_enqueue_script(
            'custom-notify-me',
            get_stylesheet_directory_uri() . '/ajax/js/ajax_notify.js',
            ['jquery'],
            '1.0',
            true
        );

        wp_localize_script(
            'custom-notify-me',
            'customNotifyMe',
            [
                'ajax_url' => admin_url(
                    'admin-ajax.php'
                ),

                'nonce' => wp_create_nonce(
                    'notify_me_nonce'
                ),
            ]
        );
    }
);


/**
 * =========================================================
 * SEND EMAIL WHEN SIMPLE PRODUCT COMES BACK IN STOCK
 * =========================================================
 */

add_action(
    'woocommerce_product_set_stock',
    'custom_send_back_in_stock_notifications',
    20,
    1
);

function custom_send_back_in_stock_notifications($product) {

    if (!$product instanceof WC_Product) {
        return;
    }

    // Only process when product is in stock
    if (!$product->is_in_stock()) {
        return;
    }

    $stock_product_id = $product->get_id();


    /**
     * -----------------------------------------------------
     * Find users subscribed to this product/variation
     * -----------------------------------------------------
     */

    $users = get_users([
        'meta_query' => [
            [
                'key'     => '_notify_me_products',
                'value'   => '"' . $stock_product_id . '"',
                'compare' => 'LIKE',
            ]
        ]
    ]);


    if (!$users) {
        return;
    }


    /**
     * -----------------------------------------------------
     * Determine whether this is a variation
     * -----------------------------------------------------
     */

    $is_variation = $product->is_type('variation');

    if ($is_variation) {

        $parent_id = $product->get_parent_id();

        $product_name = $product->get_name();

        $product_url = get_permalink(
            $parent_id
        );

    } else {

        $parent_id = $product->get_id();

        $product_name = $product->get_name();

        $product_url = get_permalink(
            $product->get_id()
        );
    }


    /**
     * -----------------------------------------------------
     * Send email
     * -----------------------------------------------------
     */

    foreach ($users as $user) {

        $email = $user->user_email;

        if (
            !$email ||
            !is_email($email)
        ) {
            continue;
        }


        $subject = $product_name .
            ' is back in stock';


        $message = sprintf(
            "Hi %s,\n\n%s is now back in stock.\n\nView the product:\n%s",
            $user->display_name,
            $product_name,
            $product_url
        );


        wp_mail(
            $email,
            $subject,
            $message
        );


        /**
         * -------------------------------------------------
         * Remove subscription after notification
         * -------------------------------------------------
         */

        $notifications = get_user_meta(
            $user->ID,
            '_notify_me_products',
            true
        );


        if (is_array($notifications)) {

            $notifications = array_values(
                array_diff(
                    $notifications,
                    [$stock_product_id]
                )
            );


            update_user_meta(
                $user->ID,
                '_notify_me_products',
                $notifications
            );
        }
    }
}