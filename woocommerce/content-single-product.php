<?php
defined( 'ABSPATH' ) || exit;

global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); 
	return;
}
?>
<section class="py-5">
    <div class="container py-3">
        <div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
            <div class="row g-5 align-items-center">
                
                <?php  do_action( 'woocommerce_before_single_product_summary' ); ?>

                <div class="col-12 col-lg-6">
                    <div class="entry-summary">
                        <?php do_action( 'woocommerce_single_product_summary' ); ?>
                    </div>
                </div>
            </div>

            <?php do_action( 'woocommerce_after_single_product_summary' );?>

        </div>
    </div>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
