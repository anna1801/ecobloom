<?php
/*
Template Name: About Page
*/

get_header();
?>

<?php inner_hero(); ?>

<?php if (have_rows('template')) : ?>

    <?php while (have_rows('template')) : the_row(); ?>

        <?php if (get_row_layout() === 'mission') : ?>

            <?php 
                $label = get_sub_field('label');
                $heading = get_sub_field('heading');
                $content = get_sub_field('content');
            ?>

            <section class="py-5 my-3" id="mission">
                <div class="container">
                    <div class="row g-5 align-items-center">
                        <div class="col-12 col-lg-6">
                            <?php 
                                if ($label) :
                                    echo '<span class="section-tag d-inline-block text-magenta fw-bold mb-2">' . $label . '</span>';
                                endif;
                                if ($heading) :
                                    echo '<h2 class="section-title mb-4">' . $heading . '</h2>';
                                endif;
                                if ($content) :
                                    echo '<p class="text-muted fs-6 mb-4" style="line-height: 1.8;">' . $content . '</p>';
                                endif;

                                if ( have_rows('highlights') ) : 
                                    echo '<div class="d-flex align-items-center gap-4 pt-2">';
                                        while ( have_rows('highlights') ) : the_row(); 
                                            $icon = get_sub_field('icon');
                                            $title = get_sub_field('title');
                                            $content = get_sub_field('content');
                                            ?>
                                            <div class="d-flex align-items-center gap-3">
                                                <?php if( $icon ) : ?>
                                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-pink-light text-magenta"
                                                        style="width: 48px; height: 48px; font-size: 1.4rem;">
                                                        <i class="bi <?php echo $icon; ?>"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <?php
                                                        if ($title) :
                                                            echo '<h6 class="mb-0 fw-bold text-dark">' . $title . '</h6>';
                                                        endif;
                                                        if ($content) :
                                                            echo '<small class="text-muted">' . $content . '</small>';
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
                            $story = get_sub_field('story');
                            if ($story) :
                                $title = $story['title'];
                                $description = $story['description'];
                                $image = $story['image'];
                                if( $image || $title || $description ) :
                                    ?>
                                    <div class="col-12 col-lg-6">
                                        <div class="about-story-box text-center position-relative">
                                            <?php
                                                if($image) :
                                                    echo '<img src="' . $image['url'] . '" alt="' . $image['alt'] . '" class="img-fluid rounded-4 mb-3" style="max-height: 380px;">';
                                                endif;
                                                if ($title) :
                                                    echo '<h4 class="fw-bold text-dark mb-2">' . $title . '</h4>';
                                                endif;
                                                if ($description) :
                                                    echo '<p class="text-muted mb-0">' . $description . '</p>';
                                                endif;
                                            ?>
                                        </div>
                                    </div>
                                    <?php 
                                endif;
                            endif; 
                        ?>
                    </div>
                </div>
            </section>
            
        <?php elseif (get_row_layout() === 'vision') : ?>

            <section class="py-5 bg-pink-light" id="vision">
                <div class="container py-4">
                    <div class="row g-4 align-items-center justify-content-between">

                        <?php 
                            $milestone = get_sub_field('milestone');
                            if ($milestone) :
                                $title = $milestone['title'];
                                $description = $milestone['description'];
                                if( $title || $description ) :
                                    $vision_col = 'col-12 col-lg-6';
                                    ?>
                                    <div class="col-12 col-lg-5">
                                        <div class="about-story-box bg-white">
                                            <div class="about-value-icon mb-3">
                                                <i class="bi bi-globe-americas"></i>
                                            </div>
                                            <?php
                                                if ($title) :
                                                    echo '<h3 class="fw-bold text-dark mb-3">' . $title . '</h3>';
                                                endif;
                                                if ($description) :
                                                    echo '<p class="text-muted mb-0" style="line-height: 1.7;">' . $description . '</p>';
                                                endif;
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                endif;
                            else :
                                $vision_col = 'col-12 col-lg-12';
                            endif;
                        ?>

                        <div class="<?php echo $vision_col; ?>">
                            <?php 
                                $label = get_sub_field('label');
                                $heading = get_sub_field('heading');
                                $content = get_sub_field('content');

                                if ($label) :
                                    echo '<span class="section-tag d-inline-block text-magenta fw-bold mb-2">' . $label . '</span>';
                                endif;
                                if ($heading) :
                                    echo '<h2 class="section-title mb-4">' . $heading . '</h2>';
                                endif;
                                if ($content) :
                                    echo '<p class="text-muted fs-6 mb-4" style="line-height: 1.8;">' . $content . '</p>';
                                endif;

                                if ( have_rows('highlights') ) : 
                                    echo '<ul class="list-unstyled d-flex flex-column gap-3 mb-0">';
                                        while ( have_rows('highlights') ) : the_row(); 
                                            $title = get_sub_field('title');
                                            $content = get_sub_field('content');
                                            ?>
                                            <li class="d-flex align-items-start gap-3">
                                                <i class="bi bi-check-circle-fill text-magenta fs-5 mt-1"></i>
                                                <div>
                                                    <?php if ($title) : ?>
                                                        <h6 class="fw-bold text-dark mb-1"><?php echo $title; ?></h6>
                                                    <?php endif; ?>
                                                    <?php if ($content) : ?>
                                                        <p class="text-muted mb-0"><?php echo $content; ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                            <?php
                                        endwhile; 
                                    echo '</ul>';
                                endif; 
                            ?>
                        </div>
                    </div>
                </div>
            </section>

        <?php elseif (get_row_layout() === 'core_values') : ?>

            <section class="py-5 my-4" id="values">
                <div class="container">
                    <div class="text-center max-w-700 mx-auto mb-5" style="max-width: 680px;">
                        <?php 
                            $label = get_sub_field('label');
                            $heading = get_sub_field('heading');
                            $content = get_sub_field('content');

                            if ($label) :
                                echo '<span class="section-tag d-inline-block text-magenta fw-bold mb-2">' . $label . '</span>';
                            endif;
                            if ($heading) :
                                echo '<h2 class="section-title">' . $heading . '</h2>';
                            endif;
                            if ($content) :
                                echo '<p class="text-muted">' . $content . '</p>';
                            endif;
                        ?>
                    </div>

                    <?php 
                        if ( have_rows('core_values') ) : 
                            echo '<div class="row g-4">';
                                while ( have_rows('core_values') ) : the_row(); 
                                    $icon = get_sub_field('icon');
                                    $title = get_sub_field('title');
                                    $content = get_sub_field('content');
                                    ?>
                                    <div class="col-12 col-md-6 col-lg-3">
                                        <div class="about-value-card">
                                            <div class="about-value-icon">
                                                <?php if($icon) : ?>
                                                    <i class="<?php echo $icon; ?>"></i>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($title) : ?>
                                                <h5 class="fw-bold text-dark mb-2"><?php echo $title; ?></h5>
                                            <?php endif; ?>
                                            <?php if ($content) : ?>
                                                <p class="text-muted fs-7 mb-0"><?php echo $content; ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php
                                endwhile; 
                            echo '</div>';
                        endif; 
                    ?>
                </div>
            </section>
 
        <?php endif; ?>

    <?php endwhile; ?>

<?php endif; ?>

<?php faq_accordion(); ?>

<?php get_footer(); ?>