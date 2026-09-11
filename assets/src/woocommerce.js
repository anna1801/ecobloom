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

// Cart page quantity update
window.cartPageQty = function (rowId, delta) {
    const row = document.getElementById(rowId);
    if (!row) return;
    const qtyEl = row.querySelector('.qty-display');
    let qty = parseInt(qtyEl.value, 10) + delta;
    if (qty < 1) qty = 1;
    qtyEl.value = qty;
    jQuery(qtyEl).trigger('change');

    // jQuery('button[name="update_cart"]') .prop('disabled', false) .removeAttr('disabled'); /* update button click */

    const updateButton = jQuery('button[name="update_cart"]');

    updateButton.prop('disabled', false).removeAttr('disabled');

    updateButton.trigger('click'); /* Automatically do what the user would have done */
};
