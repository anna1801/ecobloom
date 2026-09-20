<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }
?>

<?php get_header(); ?>

<?php do_action( 'woocommerce_before_main_content' ); ?>

    <?php while ( have_posts() ) : ?>
        <?php the_post(); ?>

        <?php wc_get_template_part( 'content', 'single-product' ); ?>

    <?php endwhile; ?>

<?php do_action( 'woocommerce_after_main_content' ); ?>

<!-- ======================================================================
    Image Zoom Lightbox Modal (#imageZoomModal)
====================================================================== -->
<div class="modal fade" id="imageZoomModal" tabindex="-1" aria-labelledby="imageZoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0 text-center">
            <div class="modal-header border-0 pb-0 justify-content-end">
                <button type="button" class="btn-close btn-close-white bg-white rounded-circle p-2 shadow"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="bg-white p-4 rounded-4 shadow-lg d-inline-block">
                    <img src="images/banner_cup.png" id="zoomedModalImg" alt="Zoomed Product View"
                        class="img-fluid rounded-3" style="max-height: 75vh;">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================================
    Size Chart Modal (#sizeChartModal)
====================================================================== -->
<?php 
    $show_hide_sizechart = get_field('show_hide_sizechart');
    if($show_hide_sizechart) : 
?>
    <div class="modal fade" id="sizeChartModal" tabindex="-1" aria-labelledby="sizeChartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                <?php 
                    $size_chart = get_field('size_chart');
                    if($size_chart) :

                    $popup_heading = $size_chart['popup_heading'];
                    $popup_footer = $size_chart['popup_footer'];
                    $size_chart = $size_chart['size_chart'];
                    ?>
                    <?php if($popup_heading) : ?>
                        <div class="modal-header bg-pink-light border-0 py-3 px-4">
                            <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="sizeChartModalLabel">
                                <i class="bi bi-ruler text-magenta"></i> <?php echo $popup_heading; ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="modal-body p-4 p-lg-5">
                        <?php 
                            if($size_chart) :
                                echo $size_chart;
                            endif;
                        ?>
                        <?php if($popup_footer) : ?>
                            <div class="bg-pink-light p-3 rounded-3 text-center">
                                <p class="small text-muted mb-0">
                                    <?php echo $popup_footer; ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="modal-footer border-0 bg-light px-4 py-3 justify-content-center">
                        <button type="button" class="btn btn-primary rounded-pill px-5 py-2 fw-bold"
                            data-bs-dismiss="modal">Got It, Thanks!</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php get_footer(); ?>