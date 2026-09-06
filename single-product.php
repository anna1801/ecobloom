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
    <div class="modal fade" id="sizeChartModal" tabindex="-1" aria-labelledby="sizeChartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
                <div class="modal-header bg-pink-light border-0 py-3 px-4">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="sizeChartModalLabel">
                        <i class="bi bi-ruler text-magenta"></i> EcoBloom Anatomical Size Guide
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 p-lg-5">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-dark">Find Your Perfect Cup Size</h4>
                        <p class="text-muted small">Our sizing is designed by gynecologists based on pelvic anatomy,
                            age, and flow intensity.</p>
                    </div>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Size Model</th>
                                    <th>Diameter</th>
                                    <th>Cup Length</th>
                                    <th>Capacity</th>
                                    <th>Ideal For</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold text-dark">Size S (Small)</td>
                                    <td>40 mm</td>
                                    <td>58 mm</td>
                                    <td>20 ml</td>
                                    <td>Teens, under 25, or light to normal flow</td>
                                </tr>
                                <tr class="table-active">
                                    <td class="fw-bold text-magenta">Size M (Medium) <span
                                            class="badge bg-magenta ms-1">Universal</span></td>
                                    <td>43 mm</td>
                                    <td>65 mm</td>
                                    <td>28 ml</td>
                                    <td>Women ages 19–35 with moderate flow</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">Size L (Large)</td>
                                    <td>46 mm</td>
                                    <td>70 mm</td>
                                    <td>35 ml</td>
                                    <td>Women 35+, post-childbirth, or heavy flow</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-pink-light p-3 rounded-3 text-center">
                        <p class="small text-muted mb-0">
                            <strong>Comfort Guarantee:</strong> Covered by EcoBloom's 90-Day Free Size Exchange if your
                            first size isn't a perfect fit!
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light px-4 py-3 justify-content-center">
                    <button type="button" class="btn btn-primary rounded-pill px-5 py-2 fw-bold"
                        data-bs-dismiss="modal">Got It, Thanks!</button>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>