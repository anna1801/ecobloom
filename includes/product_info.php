<?php 

function  product_tag($product, $class='') {
    $product_id = $product->is_type( 'variation' )
                    ? $product->get_parent_id()
                    : $product->get_id();
    $tags = get_the_terms( $product_id, 'product_tag' ); 
    if ( $tags && ! is_wp_error( $tags ) ) :
        echo '<span class="badge bg-light text-magenta border border-light-subtle rounded-pill px-3 py-1 fs-8 fw-semibold '.$class.'">';
            echo esc_html( implode( ' • ', wp_list_pluck( $tags, 'name' ) ) );
        echo '</span>';
    endif;
}

function product_badge($product) {
    $product_badge = '';

    if ($product) {
        if ($product->is_type('variation')) {
            $parent_id = $product->get_parent_id();
            $product_badge = get_field('product_badge',$parent_id);

        } else {
            $product_badge = get_field( 'product_badge', $product->get_id() );
        }
    }
    
    if($product_badge) :
        if($product_badge["value"] == 'new') {
            echo '<span class="badge bg-success text-white position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill">'.$product_badge["label"].'</span>';
        } elseif($product_badge["value"] == 'coming_soon') {
            echo '<span class="badge bg-info text-dark position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill fw-bold">'.$product_badge["label"].'</span>';
        } elseif($product_badge["value"] == 'most_opular') {
            echo '<span class="badge bg-dark text-white position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill">'.$product_badge["label"].'</span>';
        } elseif($product_badge["value"] == 'best_seller') {
            echo '<span class="badge bg-magenta text-white position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill">'.$product_badge["label"].'</span>';
        }
    endif;
}


?>