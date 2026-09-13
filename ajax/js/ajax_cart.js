
// Cart page quantity update
window.cartPageQty = function (button, delta) {

    const row = jQuery(button).closest(
        '.woocommerce-cart-form__cart-item'
    );

    if (!row.length) {
        return;
    }

    const cartItemKey = row.attr(
        'data-cart-item-key'
    );

    if (!cartItemKey) {

        console.error(
            'Cart item key missing from cart row.'
        );

        return;
    }

    const qtyInput = row.find(
        '.qty-display'
    );

    if (!qtyInput.length) {
        return;
    }

    let quantity = parseInt(
        qtyInput.val(),
        10
    );

    if (isNaN(quantity)) {
        quantity = 1;
    }

    quantity += delta;

    if (quantity < 1) {
        quantity = 1;
    }

    qtyInput.val(quantity);

    updateWooCartQuantity(
        cartItemKey,
        quantity,
        'cart'
    );
};

// MiniCart quantity update
window.miniCartQty = function (rowId, delta) {

    const row = document.getElementById(
        rowId
    );

    if (!row) {
        return;
    }

    const qtyEl = row.querySelector(
        '.qty-display-minicart'
    );

    if (!qtyEl) {
        return;
    }

    let quantity = parseInt(
        qtyEl.getAttribute('data-qty'),
        10
    );

    if (isNaN(quantity)) {
        quantity = 1;
    }

    quantity += delta;

    if (quantity < 1) {
        quantity = 1;
    }

    const cartItemKey = qtyEl.getAttribute(
        'data-cart-item-key'
    );

    if (!cartItemKey) {

        console.error(
            'Cart item key missing.'
        );

        return;
    }

    qtyEl.setAttribute(
        'data-qty',
        quantity
    );

    qtyEl.textContent = quantity;

    updateWooCartQuantity(
        cartItemKey,
        quantity,
        'mini-cart'
    );
};

// Update actual WooCommerce cart.
function updateWooCartQuantity(cartItemKey, quantity, source) {

    if (!cartItemKey) {
        console.error('Cart item key missing.');
        return;
    }

    if (quantity < 1) {
        quantity = 1;
    }

    jQuery.ajax({

        type: 'POST',

        url: wc_cart_params.ajax_url,

        dataType: 'json',

        data: {
            action: 'update_mini_cart_quantity',
            cart_item_key: cartItemKey,
            quantity: quantity
        },

        success: function (response) {

            if (!response.success) {

                console.error(
                    'Cart update failed:',
                    response.data
                );

                return;
            }

            const data = response.data;

            const cartRow = jQuery(
                '.woocommerce-cart-form__cart-item[data-cart-item-key="' +
                data.cart_item_key +
                '"]'
            );

            if (cartRow.length) {

                const qtyInput = cartRow.find(
                    '.qty-display'
                );

                if (qtyInput.length) {

                    qtyInput.val(
                        data.quantity
                    );

                }

                const subtotal = cartRow.find(
                    '.product-subtotal'
                );

                if (subtotal.length) {

                    subtotal.html(
                        data.item_subtotal
                    );

                }
            }

            jQuery('.cart-subtotal .amount').html(
                data.cart_subtotal
            );

            jQuery('.order-total .amount').html(
                data.cart_total
            );

            jQuery('.cart-badge-count').text(
                data.cart_count
            );

            if (data.fragments) {

                jQuery.each(
                    data.fragments,
                    function (selector, html) {

                        jQuery(selector).replaceWith(
                            html
                        );

                    }
                );

            }

            jQuery(document.body).trigger(
                'wc_fragments_refreshed'
            );

        },

        error: function (xhr) {

            console.error(
                'Cart AJAX error:',
                xhr.responseText
            );

        }

    });
}

// Remove cart item
jQuery(function ($) {

    $(document).on(
        'click',
        '.cart-remove-item',
        function (e) {

            e.preventDefault();

            const button = $(this);

            const cartItemKey = button.attr(
                'data-cart-item-key'
            );

            if (!cartItemKey) {

                console.error(
                    'Cart item key missing.'
                );

                return;
            }

            removeWooCartItem(
                cartItemKey,
                button
            );
        }
    );

    $(document).on(
        'click',
        '.mini-cart-remove-item',
        function (e) {

            e.preventDefault();

            const button = $(this);

            const cartItemKey = button.attr(
                'data-cart-item-key'
            );

            if (!cartItemKey) {

                console.error(
                    'Cart item key missing.'
                );

                return;
            }

            removeWooCartItem(
                cartItemKey,
                button
            );
        }
    );

    window.removeWooCartItem = function (
        cartItemKey,
        button
    ) {

        if (!cartItemKey) {
            return;
        }

        button.prop(
            'disabled',
            true
        );


        $.ajax({

            type: 'POST',

            url: wc_cart_params.ajax_url,

            dataType: 'json',

            data: {
                action: 'remove_cart_item_custom',
                cart_item_key: cartItemKey
            },


            success: function (response) {

                console.log(
                    'Remove response:',
                    response
                );


                if (!response.success) {

                    console.error(
                        'Remove failed:',
                        response.data
                    );

                    button.prop(
                        'disabled',
                        false
                    );

                    return;
                }


                const data = response.data;

                const cartRow = $(
                    '.woocommerce-cart-form__cart-item[data-cart-item-key="' +
                    data.cart_item_key +
                    '"]'
                );


                if (cartRow.length) {

                    cartRow.remove();

                }

                $('.cart-badge-count').text(
                    data.cart_count
                );

                $('.cart_totals .cart-subtotal .amount').html(
                    data.cart_subtotal
                );

                $('.cart_totals .order-total .amount').html(
                    data.cart_total
                );

                if (data.fragments) {

                    $.each(
                        data.fragments,
                        function (
                            selector,
                            html
                        ) {

                            $(selector).replaceWith(
                                html
                            );

                        }
                    );

                }

                if (
                    parseInt(data.cart_count, 10) === 0
                ) {

                    if (
                        $('.woocommerce-cart-form').length
                    ) {

                        window.location.reload();

                    }

                }

            },


            error: function (xhr) {

                console.error(
                    'Remove cart AJAX error:',
                    xhr.responseText
                );

                button.prop(
                    'disabled',
                    false
                );

            }

        });

    };

});

// Update shipping method
jQuery(function ($) {

    $(document).on('change', '.shipping_method', function () {

        const changedMethod = $(this);
        const changedIndex = String(changedMethod.data('index'));
        const changedValue = changedMethod.val();

        if (!changedValue) {
            return;
        }

        const selectedShippingMethods = {};

        $('.cart_totals .shipping_method:checked').each(function () {

            const index = String($(this).data('index'));

            selectedShippingMethods[index] = $(this).val();

        });

        $('.mini-cart-shipping-methods .shipping_method:checked').each(function () {

            const index = String($(this).data('index'));

            selectedShippingMethods[index] = $(this).val();

        });

        selectedShippingMethods[changedIndex] = changedValue;

        $('.shipping_method').prop('disabled', true);

        $.ajax({

            type: 'POST',

            url: wc_cart_params.ajax_url,

            dataType: 'json',

            data: {
                action: 'update_cart_shipping_method',
                shipping_method: selectedShippingMethods
            },

            success: function (response) {

                if (!response.success) {

                    console.error(
                        'Shipping update failed:',
                        response.data
                    );

                    return;
                }

                const data = response.data;

                $('.cart-subtotal .amount').html(
                    data.cart_subtotal
                );

                $('.order-total .amount').html(
                    data.cart_total
                );

                $('.cart-badge-count').text(
                    data.cart_count
                );

                if (data.chosen_methods) {

                    $.each(
                        data.chosen_methods,
                        function (index, methodId) {

                            index = String(index);

                            $('.cart_totals .shipping_method').each(function () {

                                const input = $(this);

                                const inputIndex = String(
                                    input.data('index')
                                );

                                if (inputIndex !== index) {
                                    return;
                                }

                                input.prop(
                                    'checked',
                                    input.val() === methodId
                                );

                            });

                            $('.mini-cart-shipping-methods .shipping_method').each(function () {

                                const input = $(this);

                                const inputIndex = String(
                                    input.data('index')
                                );

                                if (inputIndex !== index) {
                                    return;
                                }

                                input.prop(
                                    'checked',
                                    input.val() === methodId
                                );

                            });

                        }
                    );

                }

                if (data.fragments) {

                    $.each(
                        data.fragments,
                        function (selector, html) {

                            $(selector).replaceWith(html);

                        }
                    );

                }

                if (data.chosen_methods) {

                    $.each(
                        data.chosen_methods,
                        function (index, methodId) {

                            index = String(index);

                            $('.cart_totals .shipping_method').each(function () {

                                const input = $(this);

                                if (
                                    String(input.data('index')) === index
                                ) {
                                    input.prop(
                                        'checked',
                                        input.val() === methodId
                                    );
                                }

                            });

                            $('.mini-cart-shipping-methods .shipping_method').each(function () {

                                const input = $(this);

                                if (
                                    String(input.data('index')) === index
                                ) {
                                    input.prop(
                                        'checked',
                                        input.val() === methodId
                                    );
                                }

                            });

                        }
                    );

                }

                $(document.body).trigger(
                    'wc_fragments_refreshed'
                );

            },

            error: function (xhr) {

                console.error(
                    'Shipping AJAX error:',
                    xhr.responseText
                );

            },

            complete: function () {

                $('.shipping_method').prop(
                    'disabled',
                    false
                );

            }

        });

    });

});