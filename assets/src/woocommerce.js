// single-product page featured / variation image
jQuery(function ($) {
    $('.variations_form').on('found_variation', function (event, variation) {
        if (!variation || !variation.image || !variation.image.full_src) {
            return;
        }

        // featured image
        const $image = $('#mainProductImg');

        if (!$image.length) {
            return;
        }

        $image.attr('src', variation.image.full_src || variation.image.src);

        if (variation.image.alt) {
            $image.attr('alt', variation.image.alt);
        }

        // gallery
        const $firstThumb = $('.product-custom-thumbnails .product-thumb-item').first();

        if (!$firstThumb.length) {
            return;
        }

        $firstThumb
            .attr('data-image', variation.image.full_src)
            .attr('data-image-id', variation.variation_id);

        $firstThumb.find('img')
            .attr('src', variation.image.src || variation.image.full_src)
            .attr('srcset', variation.image.srcset || '')
            .attr('sizes', variation.image.sizes || '')
            .attr('alt', variation.image.alt || '');

    });
});

// Apply coupon - Checkout
jQuery(function ($) {

    $(document).on('click', '#apply_coupon_button', function (e) {

        e.preventDefault();

        var button = $(this);
        var coupon = $('#coupon_code').val().trim();

        if (!coupon) {
            $('#couponMsg')
                .removeClass('text-success')
                .addClass('text-danger')
                .text('Please enter a coupon code.')
                .show();

            return;
        }

        button.prop('disabled', true);

        $('#couponMsg')
            .removeClass('text-danger text-success')
            .text('Applying coupon...')
            .show();

        $.ajax({
            type: 'POST',

            url: ecobloom_coupon.ajax_url,

            data: {
                security: ecobloom_coupon.nonce,
                coupon_code: coupon
            },

            success: function (response) {

                var notices = $(response);

                if (notices.find('.woocommerce-error').length) {

                    $('#couponMsg')
                        .removeClass('text-success')
                        .addClass('text-danger')
                        .html(
                            notices.find('.woocommerce-error').html()
                        )
                        .show();

                        setTimeout(function () {
                            $('#couponMsg').fadeOut(300);
                        }, 2000);                   

                    return;
                }

                if (notices.find('.woocommerce-message').length) {

                    $('#couponMsg')
                        .removeClass('text-danger')
                        .addClass('text-success')
                        .html(
                            notices.find('.woocommerce-message').html()
                        )
                        .show();

                    setTimeout(function () {
                        $('#couponMsg').fadeOut(300);
                    }, 2000);  

                }

                $('body').trigger('update_checkout');

                $('#couponMsg')
                    .removeClass('text-success')
                    .addClass('text-danger')
                    .html(response)
                    .show();

                setTimeout(function () {
                    $('#couponMsg').fadeOut(300);
                }, 2000);

            },

            error: function (xhr) {

                console.log('Coupon AJAX error:', xhr);

                $('#couponMsg')
                    .removeClass('text-success')
                    .addClass('text-danger')
                    .text('Unable to apply coupon. Please try again.')
                    .show();

                setTimeout(function () {
                    $('#couponMsg').fadeOut(300);
                }, 2000);
            },

            complete: function () {
                button.prop('disabled', false);
            }
        });

    });

});