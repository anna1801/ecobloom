jQuery(function ($) {

    const $variationForm = $('form.variations_form');

    if (!$variationForm.length) {
        return;
    }

    $variationForm.on('found_variation', function (event, variation) {

        const url = new URL(window.location.href);

        $.each(variation.attributes, function (attribute, value) {

            if (value) {

                url.searchParams.set(
                    attribute,
                    value
                );

            } else {

                url.searchParams.delete(attribute);

            }

        });

        window.history.replaceState(
            null,
            '',
            url.toString()
        );

    });

    $variationForm.on('reset_data', function () {

        const url = new URL(window.location.href);

        [...url.searchParams.keys()].forEach(function (key) {

            if (key.indexOf('attribute_') === 0) {

                url.searchParams.delete(key);

            }

        });

        window.history.replaceState(
            null,
            '',
            url.toString()
        );

    });

});