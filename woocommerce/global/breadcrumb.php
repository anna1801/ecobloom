<?php 

global $product;

$product = wc_get_product( get_the_ID() );

if ( $product ) :

    $display_product = $product;

    if ( $product->is_type( 'variable' ) ) {
        $attributes = array();

        foreach ( $product->get_variation_attributes() as $attribute_name => $options ) {
            $key = 'attribute_' . sanitize_title( $attribute_name );

            if ( isset( $_GET[ $key ] ) ) {
                $attributes[ $key ] = sanitize_title( wp_unslash( $_GET[ $key ] ) );
            }
        }

        if ( ! empty( $attributes ) ) {
            $variation_id = $product->get_matching_variation( $attributes );

            if ( $variation_id ) {
                $display_product = wc_get_product( $variation_id );
            }
        }
    }

    $product_name = get_variation_display_name( $display_product );
    ?>
    <section class="py-3 bg-light border-bottom">
        <div class="container">
            <ul class="page-breadcrumb mb-0">
                <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                <?php 
                    $shop_url = get_post_type_archive_link( 'product' );
                    if($shop_url) :
                        echo '<li>/</li>
                             <li><a href="'.esc_url($shop_url).'">Our Products</a></li>';
                    endif;
                ?>
                <li>/</li>
                <li class="text-dark fw-bold breadcrumb-title"><?php echo $product_name; ?></li>
            </ul>
        </div>
    </section>
<?php endif; ?>
