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
    'woocommerce_product_set_stock_status',
    'custom_notify_me_stock_status_change',
    10,
    3
);

function custom_notify_me_stock_status_change(
    $product_id,
    $stock_status,
    $product
) {

    // We only care when it becomes IN STOCK.
    if ($stock_status !== 'instock') {
        return;
    }

    if (!$product) {
        $product = wc_get_product($product_id);
    }

    if (!$product) {
        return;
    }

    $stock_product_id = $product->get_id();

    /*
     * Find users who subscribed to this exact
     * product/variation.
     */
    $users = get_users([
        'meta_query' => [
            [
                'key'     => '_notify_me_products',
                'value'   => '"' . $stock_product_id . '"',
                'compare' => 'LIKE',
            ],
        ],
    ]);

    if (empty($users)) {
        return;
    }

    /*
     * Product name.
     */
    $product_name = $product->get_name();

    /*
     * If this is a variation, use the parent
     * product URL.
     */
    if ($product->is_type('variation')) {

        $parent_product = wc_get_product(
            $product->get_parent_id()
        );

        $product_url = $parent_product
            ? get_permalink($parent_product->get_id())
            : get_permalink($product_id);

    } else {

        $product_url = get_permalink($product_id);
    }

    $subject = sprintf(
        '%s is back in stock!',
        $product_name
    );

    foreach ($users as $user) {

        $email = $user->user_email;

        if (!$email) {
            continue;
        }

        $message = '
            <html>
            <body>

                <h2>Good news!</h2>

                <p>
                    <strong>' . esc_html($product_name) . '</strong>
                    is now back in stock.
                </p>

                <p>
                    <a href="' . esc_url($product_url) . '"
                       style="
                            display:inline-block;
                            padding:12px 20px;
                            background:#000;
                            color:#fff;
                            text-decoration:none;
                            border-radius:30px;
                       ">
                        View Product
                    </a>
                </p>

            </body>
            </html>
        ';

        $headers = [
            'Content-Type: text/html; charset=UTF-8',
        ];

        wp_mail(
            $email,
            $subject,
            $message,
            $headers
        );
    }

    /*
     * IMPORTANT:
     * Remove the product/variation from each user's
     * notification list after sending.
     */
    foreach ($users as $user) {

        $notifications = get_user_meta(
            $user->ID,
            '_notify_me_products',
            true
        );

        if (!is_array($notifications)) {
            continue;
        }

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