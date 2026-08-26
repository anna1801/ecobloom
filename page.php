<!-- WordPress default template for pages -->
<?php get_header(); ?> 

    <?php inner_hero(); ?>

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

<?php get_footer(); ?>