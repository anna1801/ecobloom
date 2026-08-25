<?php
/*
Template Name: Contact Page
*/

get_header();
?>

<?php inner_hero(); ?>


    <section class="py-5">
        <div class="container py-3">
            <div class="row g-5">
                
                <div class="col-12 col-lg-5">
                    <?php 
                        $label = get_field('label');
                        $heading = get_field('heading');
                        $content = get_field('content');

                        if ($label) :
                            echo '<span class="section-tag d-inline-block text-magenta fw-bold mb-2">' . $label . '</span>';
                        endif;
                        if ($heading) :
                            echo '<h2 class="section-title mb-4">' . $heading . '</h2>';
                        endif;
                        if ($content) :
                            echo '<p class="text-muted mb-4" style="line-height: 1.8;">' . $content . '</p>';
                        endif;

                        if ( have_rows('contact_info') ) : 
                            echo '<div class="d-flex flex-column gap-3">';
                                while ( have_rows('contact_info') ) : the_row(); 
                                    $icon = get_sub_field('icon');
                                    $label = get_sub_field('label');
                                    $value = get_sub_field('value');
                                    $link = get_sub_field('link');
                                    ?>
                                    <div class="about-value-card p-4 d-flex align-items-start gap-3">
                                        <div class="about-value-icon mb-0 flex-shrink-0"
                                            style="width: 48px; height: 48px; font-size: 1.3rem;">
                                            <?php
                                                if($icon) :
                                                    echo '<i class="' . $icon . '"></i>';
                                                endif;
                                            ?>
                                        </div>
                                        <div>
                                            <?php
                                                if ($label) :
                                                    echo '<h6 class="fw-bold text-dark mb-1">' . $label . '</h6>';
                                                endif;
                                                if ($value) :
                                                    echo '<p class="text-muted small mb-1">' . $value . '</p>';
                                                endif;
                                                if ($link) :
                                                    echo '<a href="' . $link['url'] . '" target="' . $link['target'] . '" class="text-magenta fw-bold text-decoration-none">' . $link['title'] . '</a>';
                                                endif;
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                endwhile; 
                            echo '</div>';
                        endif; 
                    ?>
                </div>

                <?php
                    $form_shortcode = get_field('form_shortcode');
                    if ($form_shortcode) :
                ?>
                    <div class="col-12 col-lg-7">
                        <div class="about-story-box p-5 bg-white contact-form-box">
                            <?php
                                $form_heading = get_field('form_heading');
                                if ($form_heading) :
                                    echo '<h3 class="fw-bold text-dark mb-4">' . $form_heading . '</h3>';
                                endif;

                                echo do_shortcode($form_shortcode);
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </section>

    <?php
        $iframe_src = get_field('iframe_src');
        if ($iframe_src) :
    ?>
        <section class="pb-5">
            <div class="container pb-3">
                <div class="about-story-box overflow-hidden p-0" style="border-radius:20px; height:320px;">
                    <iframe
                        src="<?php echo $iframe_src; ?>"
                        width="100%" height="320" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade" title="EcoBloom Office Location - Thiruvananthapuram">
                    </iframe>
                </div>
            </div>
        </section>
    <?php endif; ?>

<?php get_footer(); ?>