<?php 
    function section_header($intro, $class='') {
        if($intro) : 
            
            $heading = $intro['heading'];
            $description = $intro['description'];

            if($class) {
                $class = $class;
            } else {
                $class = '';
            }

            echo '<div class="section-header ' . esc_attr($class) . '">';
                if (!empty($heading)) :
                    echo '<h2 class="section-title title-with-flourish">' . $heading . '</h2>';
                endif;
                if (!empty($description)) :
                    echo '<p class="section-subtitle">' . esc_html($description) . '</p>';
                endif;
            echo '</div>';
        endif;
    }
?>