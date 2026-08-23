<?php
    // Header menu
    class Header_Navwalker extends Walker_Nav_Menu {

        public function start_lvl(&$output, $depth = 0, $args = null) {

            $indent = str_repeat("\t", $depth);

            $output .= "\n{$indent}<ul class=\"dropdown-menu\">\n";
        }

        public function start_el(
            &$output,
            $item,
            $depth = 0,
            $args = null,
            $id = 0
        ) {

            $indent = ($depth) ? str_repeat("\t", $depth) : '';

            $classes = empty($item->classes)
                ? array()
                : (array) $item->classes;

            $has_children = in_array(
                'menu-item-has-children',
                $classes,
                true
            );

            if ($depth === 0) {
                $classes[] = 'nav-item';
            }

            if ($has_children && $depth === 0) {
                $classes[] = 'dropdown';
            }

            if ($depth > 0) {
                $classes[] = 'dropdown-item-wrapper';
            }

            $is_active =
                in_array('current-menu-item', $classes, true) ||
                in_array('current-menu-parent', $classes, true) ||
                in_array('current-menu-ancestor', $classes, true);

            $class_names = implode(
                ' ',
                array_filter(
                    array_map(
                        'sanitize_html_class',
                        $classes
                    )
                )
            );

            $output .= $indent . '<li class="' . esc_attr($class_names) . '">';

            $menu_icon = get_field(
                'menu_icon',
                $item->ID
            );

            $atts = array();

            $atts['href'] = !empty($item->url)
                ? $item->url
                : '#';

            if (!empty($item->target)) {
                $atts['target'] = $item->target;

                if ($item->target === '_blank') {
                    $atts['rel'] = 'noopener noreferrer';
                }
            }

            if (!empty($item->attr_title)) {
                $atts['title'] = $item->attr_title;
            }

            if (!empty($item->xfn)) {
                $atts['rel'] = !empty($atts['rel'])
                    ? $atts['rel'] . ' ' . $item->xfn
                    : $item->xfn;
            }

            if ($depth === 0) {

                $atts['class'] = 'nav-link';

                if ($has_children) {

                    $atts['class'] .= ' dropdown-toggle';

                    $atts['role'] = 'button';

                    $atts['data-bs-toggle'] = 'dropdown';

                    $atts['aria-expanded'] = 'false';
                }

                if ($is_active) {
                    $atts['class'] .= ' active';
                }

            } else {

                $atts['class'] = 'dropdown-item';

                if ($is_active) {
                    $atts['class'] .= ' active';
                }
            }

            $attributes = '';

            foreach ($atts as $attr => $value) {

                if ($value === '') {
                    continue;
                }

                if ($attr === 'href') {
                    $value = esc_url($value);
                } else {
                    $value = esc_attr($value);
                }

                $attributes .= ' ' . $attr . '="' . $value . '"';
            }

            $title = apply_filters(
                'the_title',
                $item->title,
                $item->ID
            );

            $title = apply_filters(
                'nav_menu_item_title',
                $title,
                $item,
                $args,
                $depth
            );

            $output .= '<a' . $attributes . '>';

            if ($menu_icon ) {

                if($depth > 0) {
                    $depth_class = 'child_menu_item';
                } else {
                    $depth_class = 'parent_menu_item';
                }

                $output .= '<i class="bi bi-'
                    . esc_attr($menu_icon)
                    . ' ' . esc_attr($depth_class) . '"></i>';
            }

            $output .= esc_html($title);

            if ($has_children && $depth === 0) {

                $output .= ' <i class="bi bi-chevron-down ms-1"'
                    . ' style="font-size: 0.75rem;"></i>';
            }

            $output .= '</a>';
        }

        public function end_el(
            &$output,
            $item,
            $depth = 0,
            $args = null
        ) {

            $output .= "</li>\n";
        }

        public function end_lvl(
            &$output,
            $depth = 0,
            $args = null
        ) {

            $indent = str_repeat("\t", $depth);

            $output .= "{$indent}</ul>\n";
        }
    }

    // Footer menu
    class Footer_Navwalker extends Walker_Nav_Menu {

        private $items = array();

        public function walk( $elements, $max_depth, ...$args ) {

            $this->items = $elements;

            $top_level_items = array_filter(
                $elements,
                function ( $item ) {
                    return (int) $item->menu_item_parent === 0;
                }
            );

            $total = count( $top_level_items );

            $half = (int) ceil( $total / 2 );

            $column_1_items = array_slice( $top_level_items, 0, $half );
            $column_2_items = array_slice( $top_level_items, $half );

            $output = '<div class="footer-links-grid-template">';

            // First column.
            $output .= '<ul class="footer-links-col">';

            foreach ( $column_1_items as $item ) {
                $output .= $this->get_menu_item_output(
                    $item,
                    $elements,
                    $max_depth,
                    $args
                );
            }

            $output .= '</ul>';

            // Second column.
            $output .= '<ul class="footer-links-col">';

            foreach ( $column_2_items as $item ) {
                $output .= $this->get_menu_item_output(
                    $item,
                    $elements,
                    $max_depth,
                    $args
                );
            }

            $output .= '</ul>';

            $output .= '</div>';

            return $output;
        }

        private function get_menu_item_output(
            $item,
            $elements,
            $max_depth,
            $args
        ) {

            $output = '';

            $this->start_el(
                $output,
                $item,
                0,
                $args[0] ?? null,
                0
            );

            return $output;
        }
    }

?>