jQuery(document).ready(function ($) {

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

        const $blogResults = $('#blog-results');

        $blogResults.addClass('is-loading');

        $.ajax({
            url: blogAjax.ajax_url,
            type: 'POST',

            data: {
                action: 'ajax_blog_pagination',
                page: page
            },

            success: function (response) {

                if (response.success) {

                    $blogResults.html(response.data.html);

                    const currentUrl = new URL(window.location.href);

                    if (page === 1) {
                        currentUrl.searchParams.delete('paged');
                    } else {
                        currentUrl.searchParams.set('paged', page);
                    }

                    window.history.pushState(
                        { page: page },
                        '',
                        currentUrl.toString()
                    );

                    if (typeof AOS !== 'undefined') {
                        AOS.refreshHard();
                    }

                    $('html, body').animate({
                        scrollTop: $('#blog-results').offset().top - 100
                    }, 400);

                }

            },

            complete: function () {
                $blogResults.removeClass('is-loading');
            }

        });

    });

});