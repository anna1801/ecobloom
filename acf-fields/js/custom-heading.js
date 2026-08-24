jQuery(function ($) {

    console.log('Custom heading loaded');


    /*
     * Update hidden ACF input
     */
    function updateValue($wrapper) {

        var $editor = $wrapper.find('.acf-custom-heading-editor');
        var $input  = $wrapper.find('.acf-custom-heading-input');

        if (!$editor.length || !$input.length) {
            return;
        }

        var value = $editor.html();

        $input.val(value).trigger('change');

    }


    /*
     * Find the closest magenta span
     */
    function getMagentaParent(node, editor) {

        if (!node) {
            return null;
        }

        if (node.nodeType === 3) {
            node = node.parentNode;
        }

        while (node && node !== editor) {

            if (
                node.nodeType === 1 &&
                node.tagName.toLowerCase() === 'span' &&
                node.classList.contains('text-magenta')
            ) {
                return node;
            }

            node = node.parentNode;
        }

        return null;
    }


    /*
     * Highlight toggle
     */
    $(document).on('mousedown', '.acf-highlight', function (e) {

        /*
         * Prevent the button from destroying
         * the current text selection.
         */
        e.preventDefault();

    });


    $(document).on('click', '.acf-highlight', function (e) {

        e.preventDefault();

        var $button  = $(this);
        var $wrapper = $button.closest('.acf-custom-heading');
        var $editor  = $wrapper.find('.acf-custom-heading-editor');

        if (!$editor.length) {
            return;
        }


        /*
         * Get selection BEFORE focusing editor
         */
        var selection = window.getSelection();

        if (!selection || !selection.rangeCount) {

            alert('Please select the text first.');

            return;
        }


        var range = selection.getRangeAt(0);


        /*
         * Selection must be inside editor
         */
        if (!$editor[0].contains(range.commonAncestorContainer)) {

            alert('Please select text inside the heading.');

            return;
        }


        /*
         * No text selected
         */
        if (selection.isCollapsed) {

            alert('Please select the text first.');

            return;
        }


        /*
         * Check whether selected text
         * is already highlighted
         */
        var magentaParent = getMagentaParent(
            range.commonAncestorContainer,
            $editor[0]
        );


        /*
         * REMOVE HIGHLIGHT
         */
        if (magentaParent) {

            var parent = magentaParent.parentNode;

            while (magentaParent.firstChild) {

                parent.insertBefore(
                    magentaParent.firstChild,
                    magentaParent
                );

            }

            parent.removeChild(magentaParent);


            /*
             * Update hidden ACF field
             */
            updateValue($wrapper);


            /*
             * Keep editor active
             */
            $editor.focus();

            return;

        }


        /*
         * ADD HIGHLIGHT
         */
        var span = document.createElement('span');

        span.className = 'text-magenta';


        try {

            range.surroundContents(span);

        } catch (error) {

            /*
             * Handles selections crossing
             * existing HTML elements
             */
            var fragment = range.extractContents();

            span.appendChild(fragment);

            range.insertNode(span);

        }


        /*
         * Clear selection
         */
        selection.removeAllRanges();


        /*
         * Update hidden ACF field
         */
        updateValue($wrapper);


        /*
         * Keep editor active
         */
        $editor.focus();

    });


    /*
     * Update ACF value while typing
     */
    $(document).on(
        'input',
        '.acf-custom-heading-editor',
        function () {

            var $wrapper = $(this).closest('.acf-custom-heading');

            updateValue($wrapper);

        }
    );


    /*
     * Allow Enter as <br>
     */
    $(document).on(
        'keydown',
        '.acf-custom-heading-editor',
        function (e) {

            if (e.key !== 'Enter') {
                return;
            }

            e.preventDefault();


            var editor = this;

            var selection = window.getSelection();


            if (!selection || !selection.rangeCount) {
                return;
            }


            var range = selection.getRangeAt(0);


            /*
             * Make sure selection is inside editor
             */
            if (!editor.contains(range.commonAncestorContainer)) {
                return;
            }


            /*
             * Remove selected content if necessary
             */
            if (!selection.isCollapsed) {
                range.deleteContents();
            }


            /*
             * Create <br>
             */
            var br = document.createElement('br');


            /*
             * Insert <br>
             */
            range.insertNode(br);


            /*
             * Create a second <br> when necessary.
             *
             * This makes the cursor move to a
             * genuinely new visual line in contenteditable.
             */
            var nextNode = br.nextSibling;

            if (
                !nextNode ||
                nextNode.nodeName !== 'BR'
            ) {

                var secondBr = document.createElement('br');

                br.parentNode.insertBefore(
                    secondBr,
                    br.nextSibling
                );

            }


            /*
             * Move cursor after the first <br>
             */
            range.setStartAfter(br);
            range.collapse(true);


            selection.removeAllRanges();
            selection.addRange(range);


            /*
             * IMPORTANT:
             * Update the wrapper, not the editor.
             */
            var $wrapper = $(editor).closest(
                '.acf-custom-heading'
            );

            updateValue($wrapper);

        }
    );


    /*
     * ACF repeater / flexible content support
     */
    if (typeof acf !== 'undefined') {

        acf.addAction(
            'append',
            function ($el) {

                $el.find(
                    '.acf-custom-heading-editor'
                ).each(function () {

                    var $wrapper = $(this).closest(
                        '.acf-custom-heading'
                    );

                    updateValue($wrapper);

                });

            }
        );


        /*
         * Update fields before ACF saves the post
         */
        acf.addAction(
            'prepare',
            function () {

                $('.acf-custom-heading').each(
                    function () {

                        updateValue($(this));

                    }
                );

            }
        );

    }


});