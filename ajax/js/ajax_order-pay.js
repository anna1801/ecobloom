jQuery(function ($) {

    $(document).on(
        'change',
        '#payment input[name="payment_method"]',
        function () {

            var paymentMethod = $(this).val();

            var urlParams = new URLSearchParams(
                window.location.search
            );

            var orderId = window.location.pathname.match(
                /order-pay\/(\d+)/
            );

            var orderKey = urlParams.get('key');

            if (!orderId || !orderKey) {
                return;
            }

            var $totals = $('#order-pay-totals');

            $totals.css('opacity', '0.5');

            $.ajax({

                url: ecobloomOrderPay.ajax_url,

                type: 'POST',

                data: {
                    action: 'ecobloom_order_pay_payment',
                    security: $('#ecobloom_order_pay_nonce').val(),
                    order_id: orderId[1],
                    order_key: orderKey,
                    payment_method: paymentMethod
                },

                success: function (response) {

                    console.log(
                        'Order pay response:',
                        response
                    );

                    if (
                        response.success &&
                        response.data &&
                        response.data.html
                    ) {

                        $totals.html(
                            response.data.html
                        );
                    }

                },

                error: function (xhr) {

                    console.error(
                        'Order pay AJAX error:',
                        xhr.responseText
                    );

                },

                complete: function () {

                    $totals.css('opacity', '1');

                }

            });

        }

    );

});