jQuery(document).on('click', '.notify-me-button', function () {

    const button = jQuery(this);

    const productId = button.data('product-id');
    const variationId = button.data('variation-id') || 0;

    const message = button
        .closest('.custom-notify-me')
        .find('.notify-me-message');

    button.prop('disabled', true);


    jQuery.ajax({

        url: customNotifyMe.ajax_url,

        type: 'POST',

        data: {

            action: 'custom_product_notify_me',

            nonce: customNotifyMe.nonce,

            product_id: productId,

            variation_id: variationId

        },


        success: function (response) {

            message
                .removeClass('success error')
                .addClass(
                    response.success
                        ? 'success'
                        : 'error'
                )
                .text(response.data.message)
                .addClass('show');

            setTimeout(function () {

                message.removeClass('show');

            }, 2500);

            if (!response.success) {

                button.prop(
                    'disabled',
                    false
                );
            }
        },


        error: function () {

            message
                .removeClass('success')
                .addClass('error')
                .text(
                    'Something went wrong. Please try again.'
                )
                .addClass('show');


            button.prop(
                'disabled',
                false
            );


            setTimeout(function () {

                message.removeClass('show');

            }, 2500);
        }

    });

});