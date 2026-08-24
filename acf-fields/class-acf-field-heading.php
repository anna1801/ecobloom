<?php

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    class acf_field_custom_heading extends acf_field {

        public function __construct() {

            $this->name     = 'custom_text';
            $this->label    = __( 'Custom Heading', 'your-textdomain' );
            $this->category = 'basic';

            parent::__construct();
        }

        public function render_field( $field ) {

            $value = $field['value'];

            ?>

            <div class="acf-custom-heading">

                <div class="acf-custom-heading-toolbar">

                    <button type="button" class="button acf-highlight" > Highlight </button>
                    <p class="description acf-highlight-description"> Select text and click <strong>Highlight</strong> to apply/remove the style</p>

                </div>

                <div class="acf-custom-heading-editor" contenteditable="true" data-placeholder="Enter heading...">
                    <?php echo wp_kses(
                        $value,
                        array(
                            'span' => array(
                                'class' => true,
                            ),
                            'br' => array(),
                        )
                    ); ?>
                </div>

                <input type="hidden" class="acf-custom-heading-input" name="<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />

            </div>

            <?php
        }

        public function update_value( $value, $post_id, $field ) {

            return wp_kses(
                $value,
                array(
                    'span' => array(
                        'class' => true,
                    ),
                    'br' => array(),
                )
            );
        }

        public function input_admin_enqueue_scripts() {

            wp_enqueue_style(
                'acf-custom-heading',
                get_template_directory_uri() . '/acf-fields/css/custom-heading.css',
                array(),
                '1.0.0'
            );

            wp_enqueue_script(
                'acf-custom-heading',
                get_template_directory_uri() . '/acf-fields/js/custom-heading.js',
                array( 'jquery' ),
                '1.0.0',
                true
            );
        }
    }

?>