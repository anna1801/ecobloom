
(function () {
    tinymce.PluginManager.add('custom_badge', function (editor) {

        var badgeClass = 'badge bg-pink-light text-magenta border border-light-subtle rounded-pill px-3 py-2 fs-8';

        function setCursorAfterIcon(badge) {
            var icon = editor.dom.select('i', badge)[0];

            if (!icon) {
                editor.selection.setCursorLocation(badge, badge.childNodes.length);
                return;
            }

            // Place cursor after the icon element
            editor.selection.setCursorLocation(badge, 1);
        }

        function addBadge(wrapper) {
            editor.dom.add(
                wrapper,
                'div',
                {
                    'class': badgeClass
                },
                '<i class="bi bi-check-circle-fill me-1" contenteditable="false"></i>&nbsp;'
            );

            var items = editor.dom.select('.badge', wrapper);
            var newItem = items[items.length - 1];

            setCursorAfterIcon(newItem);
        }

        editor.addButton('custom_badge', {
            icon: 'checkmark',

            onclick: function () {

                editor.insertContent(
                    '<div class="badges d-flex flex-wrap gap-3">' +
                        '<div class="' + badgeClass + '">' +
                            '<i class="bi bi-check-circle-fill me-1" contenteditable="false"></i>&nbsp;' +
                        '</div>' +
                    '</div>'
                );

                var wrappers = editor.dom.select('.badges');

                if (wrappers.length) {
                    var wrapper = wrappers[wrappers.length - 1];
                    var item = editor.dom.select('.badge', wrapper)[0];

                    setCursorAfterIcon(item);
                }
            }
        });

        editor.on('keydown', function (event) {

            if (event.keyCode !== 13) {
                return;
            }

            var current = editor.selection.getNode();

            if (!editor.dom.hasClass(current, 'badge')) {
                return;
            }

            event.preventDefault();

            var wrapper = editor.dom.getParent(
                current,
                '.badges'
            );

            if (!wrapper) {
                return;
            }

            addBadge(wrapper);
        });

        /*
         * Fix pasted text.
         * Always insert pasted content after the icon.
         */
        editor.on('paste', function (event) {

            var current = editor.selection.getNode();

            if (!editor.dom.hasClass(current, 'badge')) {
                return;
            }

            var badge = current;
            var icon = editor.dom.select('i', badge)[0];

            if (!icon) {
                return;
            }

            // Move selection to the end of the badge,
            // immediately after the icon/text already inside it.
            editor.selection.select(badge, true);
            editor.selection.collapse(false);
        });

    });
})();

