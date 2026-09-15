<?php
/*
Template Name: Login Page
*/

if ( is_user_logged_in() ) {
    wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
    exit;
}

get_header();
?>

<?php if ( !is_user_logged_in() ) : ?>
    
    <section class="py-5 bg-pink-light login_page inner-woocommerce" style="min-height: 80vh; display: flex; align-items: center;">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-5">
                    <div class="about-story-box p-5 bg-white shadow-lg">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-circle text-magenta" style="font-size: 3rem;"></i>
                            <h2 class="fw-bold text-dark mt-2">Welcome Back</h2>
                            <p class="text-muted small">Sign in to manage your EcoBloom orders &amp; account.</p>
                        </div>

                        <?php echo do_shortcode('[woocommerce_my_account]'); ?>

                        <?php
                            $register_page = get_pages( array(
                                'meta_key'   => '_wp_page_template',
                                'meta_value' => 'template/template-register.php',
                                'number'     => 1,
                            ) );

                            if ( ! empty( $register_page ) ) {
                                $register_url = get_permalink( $register_page[0]->ID );
                            } else {
                                $register_url = wc_get_page_permalink( 'myaccount' );
                            }
                        ?>

                        <div class="text-center mt-4 pt-3 border-top">
                            <span class="text-muted small">Don't have an EcoBloom account yet?</span>
                            <a href="<?php echo $register_url; ?>" class="fw-bold text-magenta text-decoration-none small ms-1">Create an Account</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php endif; ?>

<?php get_footer(); ?>