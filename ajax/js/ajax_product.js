jQuery(document).ready(function ($) {

    let currentCategory = productAjax.category || 'all';

    $(document).on('click', '.ecobloom-filter-btn', function (e) {

        e.preventDefault();

        const $button = $(this);

        currentCategory = $button.data('filter') || 'all';

        $('.ecobloom-filter-btn').removeClass('active');
        $button.addClass('active');

        loadProducts(1, currentCategory);
    });

    $(document).on('click', '.ajax-pagination', function (e) {

        e.preventDefault();

        const $link = $(this);

        if ($link.closest('.page-item').hasClass('disabled')) {
            return;
        }

        const page = parseInt($link.data('page'), 10);

        if (!page || page < 1) {
            return;
        }

        loadProducts(page, currentCategory);
    });

    function loadProducts(page, category) {

        const $productResults = $('#product-results');

        $productResults.addClass('is-loading');

        $.ajax({

            url: productAjax.ajax_url,

            type: 'POST',

            data: {
                action: 'ajax_product_pagination',
                page: page,
                category: category
            },

            success: function (response) {

                if (response.success) {

                    $productResults.html(response.data.html);

                    const currentUrl = new URL(window.location.href);

                    if (page === 1) {
                        currentUrl.searchParams.delete('paged');
                    } else {
                        currentUrl.searchParams.set('paged', page);
                    }

                    if (category && category !== 'all' && !productAjax.category) {
                        currentUrl.searchParams.set('product_cat', category);
                    } else if (category === 'all' && !productAjax.category) {
                        currentUrl.searchParams.delete('product_cat');
                    }


                    // window.history.pushState(
                    //     {
                    //         page: page,
                    //         category: category
                    //     },
                    //     '',
                    //     currentUrl.toString()
                    // );

                    if (typeof AOS !== 'undefined') {
                        AOS.refreshHard();
                    }

                    $('html, body').animate({
                        //scrollTop: $productResults.offset().top - 100
                        scrollTop: $('#to-top').offset().top - 100
                    }, 400);
                }

            },

            error: function () {

                console.log('Product AJAX request failed.');

            },

            complete: function () {

                $productResults.removeClass('is-loading');

            }

        });
    }

});