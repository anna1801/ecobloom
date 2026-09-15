<?php
/*
Template Name: Register Page
*/

if ( is_user_logged_in() ) {
    wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
    exit;
}

get_header();
?>

<?php if ( !is_user_logged_in() ) : ?>

    <section class="py-5 bg-pink-light register_page inner-woocommerce" style="min-height: 80vh; display: flex; align-items: center;">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="about-story-box p-5 bg-white shadow-lg">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-plus-fill text-magenta" style="font-size: 3rem;"></i>
                            <h2 class="fw-bold text-dark mt-2">Join EcoBloom</h2>
                            <p class="text-muted small">Create an account for faster checkout, order tracking, and member rewards.</p>
                        </div>

                        <?php echo do_shortcode('[woocommerce_my_account]'); ?>

                        <?php
                            $login_page = get_pages( array(
                                'meta_key'   => '_wp_page_template',
                                'meta_value' => 'template/template-login.php',
                                'number'     => 1,
                            ) );

                            if ( ! empty( $login_page ) ) {
                                $login_url = get_permalink( $login_page[0]->ID );
                            } else {
                                $login_url = wc_get_page_permalink( 'myaccount' );
                            }
                        ?>

                        <div class="text-center mt-4 pt-3 border-top">
                            <span class="text-muted small">Already have an EcoBloom account?</span>
                            <a href="<?php echo $login_url; ?>" class="fw-bold text-magenta text-decoration-none small ms-1">Sign In Here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php endif; ?>

<?php get_footer(); ?>