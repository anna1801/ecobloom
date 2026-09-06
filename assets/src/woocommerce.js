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