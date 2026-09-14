<!-- WordPress default template for pages -->
<?php get_header(); ?> 

    <?php inner_hero(); ?>

    <?php if (is_cart()) : ?>
        
        <?php the_content(); ?>

    <?php elseif (is_checkout()) : ?>

        <div class="container pt-4 pb-2">
            <div class="checkout-steps">
                <div class="checkout-step done">
                    <div class="step-circle"><i class="bi bi-check-lg"></i></div>
                    <span class="step-label">Shopping Bag</span>
                </div>
                <div class="step-connector done"></div>
                <div class="checkout-step active">
                    <div class="step-circle">2</div>
                    <span class="step-label">Checkout</span>
                </div>
                <div class="step-connector"></div>
                <div class="checkout-step">
                    <div class="step-circle">3</div>
                    <span class="step-label">Confirmation</span>
                </div>
            </div>
        </div>

        <?php the_content(); ?>

    <?php else : ?>
        <section class="py-5 default-page">
            <div class="container py-3">
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-9">
                        <div class="about-story-box p-5 bg-white text-dark body-content" style="line-height: 1.8;">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

<?php get_footer(); ?>