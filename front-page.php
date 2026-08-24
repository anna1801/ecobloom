<!-- WordPress default template for front / Home page -->
<?php get_header(); ?> 

    <?php
        $hero_showhide = get_field('hero_showhide');
        if ($hero_showhide) :
    ?>
        <section class="hero-banner-section" id="top">
            <div class="container hero-content">
                <div class="row align-items-center">
                    
                    <div class="col-lg-6 mb-5 mb-lg-0">
                        <?php 
                            $hero_heading = get_field('hero_heading');
                            $hero_description = get_field('hero_description');
                            $hero_tagline = get_field('hero_tagline');

                            if($hero_heading) :
                                echo '<h1 class="hero-title animate__animated animate__fadeInLeft">' . $hero_heading . '</h1>';
                            endif;
                            if($hero_description) :
                                echo '<p class="hero-description animate__animated animate__fadeInLeft" style="animation-delay: 0.1s;">' . $hero_description . '</p>';
                            endif;
                            if($hero_tagline) :
                                echo '<p class="hero-tagline-bold animate__animated animate__fadeInLeft" style="animation-delay: 0.2s;">' . $hero_tagline . '</p>';
                            endif;
                        
                            if ( have_rows('hero_ctas') ) : 
                                echo '<div class="d-flex flex-wrap gap-3 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">';
                                    while ( have_rows('hero_ctas') ) : the_row(); 
                                        $type = get_sub_field('type');
                                        $link = get_sub_field('link');
                                        
                                        if($type == 'primary') {
                                            $class = 'btn-ecobloom-primary';
                                            $action = ''; 
                                        } else if($type == 'outline') {
                                            $class = 'btn-ecobloom-outline';
                                            $action = '';
                                        } else if($type == 'mincart') {
                                            $class = 'btn-ecobloom-primary';
                                            $action = 'onclick="addSampleCupToCart(); return false;"';
                                        }

                                        echo '<a href="'. $link['url'] .'" target="'. $link['target'] .'" class="'. $class .'" '. $action .'>';
                                            echo '<span>'. $link['title'] .'</span>';
                                            if($type == 'mincart') {
                                                echo '<i class="bi bi-arrow-right"></i>';
                                            }
                                        echo '</a>';

                                    endwhile; 
                                echo '</div>';
                            endif; 
 
                            if ( have_rows('hero_benefits') ) : 
                                echo '<div class="hero-features-row animate__animated animate__fadeIn" style="animation-delay: 0.4s;">';
                                    while ( have_rows('hero_benefits') ) : the_row(); 

                                    
                                        $icon = get_sub_field('icon');
                                        $label = get_sub_field('label');
                                        
                                        echo '<div class="hero-feature-item">
                                            <span class="icon-box"><i class="'. $icon .'"></i></span>
                                            <span>'. $label .'</span>
                                        </div>';

                                    endwhile; 
                                echo '</div>';
                            endif; 
                        ?>                            
                    </div>

                    <div class="col-lg-6">
                        <div class="hero-image-wrapper">

                            <?php 
                                $hero_featured_image = get_field('hero_featured_image');
                                if($hero_featured_image) :
                                    echo '<img src="'. $hero_featured_image['url'] .'" alt="'. $hero_featured_image['alt'] .'" class="banner-cup-image animate__animated ">'; // removed "animate__zoomIn" class
                                endif;
                            ?>
                    
                            <div class="loved-by-badge">
                                <span class="loved-text">Loved by</span>
                                <span class="loved-count">10,000+</span>
                                <span class="loved-women">Women</span>
                                <i class="bi bi-heart-fill heart-icon"></i>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php 
        $features_showhide = get_field('features_showhide');
        if ( have_rows('features') && $features_showhide ) :
    ?>
        <section class="why-choose-section" id="about-section">
            <div class="container">

                <?php
                    $features_intro = get_field('features_intro');
                    if($features_intro) : 
                        section_header($features_intro, 'mb-5');
                    endif;

                    echo '<div class="row g-3 justify-content-center">';
                        while ( have_rows('features') ) : the_row(); 
                            $icon = get_sub_field('icon');
                            $icon_color = get_sub_field('icon_color');
                            $title = get_sub_field('title');
                            $details = get_sub_field('details');

                            if($icon_color) {
                                $icon_style = $icon_color;
                            } else {
                                $icon_style = '#4c1d95';
                            }
                            
                            echo '<div class="col-6 col-md-4 col-lg-2">';
                                echo '<div class="feature-icon-card">';
                                    echo '<div class="feature-icon-circle">';
                                        if($icon) :
                                            echo '<i class="'. $icon .'" style="color: '. $icon_style .';"></i>';
                                        endif;
                                    echo '</div>';
                                    if($title) :
                                        echo '<h3 class="feature-card-title">'. $title .'</h3>';
                                    endif;
                                    if($details) :
                                        echo '<p class="feature-card-text">'. $details .'</p>';
                                    endif;
                                echo '</div>';
                            echo '</div>';
                        endwhile; 
                    echo '</div>';
                ?>

            </div>
        </section>
    <?php endif; ?>

    <?php 
        $agegroup_showhide = get_field('agegroup_showhide');
        if ( have_rows('age_group') && $agegroup_showhide ) :
    ?>
        <section class="every-stage-section full-width-section" id="shop-section">
            <div class="every-stage-banner-box full-width-stage-box">
                <div class="container-fluid px-3 px-xl-5">

                    <?php
                        $agegroup_intro = get_field('agegroup_intro');
                        if($agegroup_intro) : 
                            section_header($agegroup_intro);
                        endif;

                        echo '<div class="row g-4">';
                            while ( have_rows('age_group') ) : the_row(); 

                                $age_range = get_sub_field('age_range');
                                $title = get_sub_field('title');
                                $details = get_sub_field('details');
                                $image = get_sub_field('image');
                                
                                echo '<div class="col-12 col-md-6 col-lg-3">';
                                    echo '<div class="stage-card card-full-img">';
                                        if($image) :
                                            echo '<img src="'. $image['url'] .'" alt="'. $image['alt'] .'" class="stage-card-bg-img">';
                                        endif;
                                        echo '<div class="stage-card-content">';
                                            if($age_range) :
                                                echo '<span class="stage-age-range">'. $age_range .'</span>';
                                            endif;
                                            if($title) :
                                                echo '<h3 class="stage-title">'. $title .'</h3>';
                                            endif;
                                            if($details) :
                                                echo '<p class="stage-desc">'. $details .'</p>';
                                            endif;
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';

                            endwhile; 
                        echo '</div>';
                    ?>

                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php 
        $steps_showhide = get_field('steps_showhide');
        if ( have_rows('steps') && $steps_showhide ) :
    ?>
        <section class="how-it-works-section" id="how-it-works-section">
            <div class="container">
    
                <?php
                    $steps_intro = get_field('steps_intro');
                    if($steps_intro) : 
                        section_header($steps_intro, 'mb-5');
                    endif;
                
                    echo '<div class="how-it-works-row">';
                        $i = 1;
                        while ( have_rows('steps') ) : the_row(); 

                            $icon = get_sub_field('icon');
                            $title = get_sub_field('title');
                            $details = get_sub_field('details');
                            $count = get_row_index();

                            echo '<div class="step-item-horizontal">';
                                echo '<div class="step-circle-wrap">';
                                    echo '<span class="step-circle-badge">'. $count .'</span>';
                                    if($icon) :
                                        echo '<img src="'. $icon['url'] .'" alt="'. $icon['alt'] .'" class="step-icon">';
                                    endif;
                                echo '</div>';
                                echo '<div class="step-text-block">';
                                    if($title) :
                                        echo '<h3 class="step-title">'. $title .'</h3>';
                                    endif;
                                    if($details) :
                                        echo '<p class="step-desc">'. $details .'</p>';
                                    endif;
                                echo '</div>';
                            echo '</div>';

                            if($i < count(get_field('steps'))) :
                                echo '<div class="step-arrow-item d-none d-xl-flex">'; 
                                    echo '<i class="fa-solid fa-arrow-right-long"></i>';
                                echo '</div>';
                            endif;

                            $i++;
                        endwhile; 
                    echo '</div>';

                    $steps_tagline = get_field('steps_tagline');
                    if($steps_tagline) :
                        echo '<p class="works-disclaimer">'. $steps_tagline .'</p>';
                    endif;
                ?>
              
            </div>
        </section>
    <?php endif; ?>

    <section class="switching-grid-section" id="sustainability-section">
        <div class="container-fluid px-3 px-xl-5">
            <div class="row g-4 align-items-stretch home-bottom-grid">
                
                <?php 
                    $benefits_showhide = get_field('benefits_showhide');
                    if ( have_rows('benefits') && $benefits_showhide ) :
                ?>
                    <div class="col-12 col-lg-6">
                        <div class="switching-box">
                            
                            <?php 
                                echo '<div>';
                                    $benefits_heading = get_field('benefits_heading');
                                    if($benefits_heading) :
                                        echo '<h2 class="switching-title">'. $benefits_heading .'</h2>';
                                    endif;

                                    echo '<div class="row g-3">';
                                        while ( have_rows('benefits') ) : the_row(); 

                                            $icon = get_sub_field('icon');
                                            $icon_color = get_sub_field('icon_color');
                                            $title = get_sub_field('title');

                                            if($icon_color) {
                                                $icon_style = $icon_color;
                                            } else {
                                                $icon_style = '#4c1d95';
                                            }

                                            echo '<div class="col-6 col-sm-3">';
                                                echo '<div class="switching-item">';
                                                    if($icon) :
                                                        echo '<div class="switch-icon" style="color: '. $icon_style .';"><i class="'. $icon .'"></i></div>';
                                                    endif;
                                                    if($title) :
                                                        echo '<span class="switch-label">'. $title .'</span>';
                                                    endif;
                                                echo '</div>';
                                            echo '</div>';

                                        endwhile; 
                                    echo '</div>';
                                echo '</div>';
                            
                                $benefits_tagline = get_field('benefits_tagline');
                                if($benefits_tagline) :
                                    echo '<p class="switch-disclaimer">'. $benefits_tagline .'</p>';
                                endif;
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php 
                    $sustainability_showhide = get_field('sustainability_showhide');
                    if ( $sustainability_showhide ) :
                ?>
                    <div class="col-12 col-lg-6">
                        <div class="globe-card">
                            <div class="globe-card-content">
                                <?php 
                                    $sustainability_heading = get_field('sustainability_heading');
                                    if($sustainability_heading) :
                                        echo '<h2 class="globe-title">'. $sustainability_heading .'</h2>';
                                    endif;
                                    
                                    $sustainability_description = get_field('sustainability_description');
                                    if($sustainability_description) :
                                        echo '<p class="globe-text">'. $sustainability_description .'</p>';
                                    endif;

                                    if ( have_rows('sustainability_ctas') ) : 
                                        echo '<div class="d-flex flex-wrap gap-3 cta-group">';
                                            while ( have_rows('sustainability_ctas') ) : the_row(); 
                                                $type = get_sub_field('type');
                                                $link = get_sub_field('link');
                                                
                                                if($type == 'primary') {
                                                    $class = 'btn-ecobloom-primary';
                                                    $action = ''; 
                                                } else if($type == 'outline') {
                                                    $class = 'btn-ecobloom-outline';
                                                    $action = '';
                                                } else if($type == 'mincart') {
                                                    $class = 'btn-globe';
                                                    $action = 'onclick="addSampleCupToCart(); return false;"';
                                                }

                                                echo '<a href="'. $link['url'] .'" target="'. $link['target'] .'" class="'. $class .'" '. $action .'>';
                                                    echo $link['title'];
                                                echo '</a>';

                                            endwhile; 
                                        echo '</div>';
                                    endif; 
                                ?>
                            </div>
                        
                            <div class="globe-botanical-bg">
                                <svg width="120" height="240" viewBox="0 0 100 200" fill="none" stroke="#9333ea"
                                    stroke-width="1.5" stroke-linecap="round">
                                    <path d="M50 180C40 130 60 80 80 20" />
                                    <path d="M55 150C65 140 75 142 85 152" />
                                    <path d="M55 150C45 138 35 140 25 150" />
                                    <path d="M62 110C75 100 85 102 95 112" />
                                    <path d="M62 110C50 98 40 100 30 110" />
                                    <path d="M72 70C82 60 92 62 98 70" />
                                    <path d="M72 70C60 58 50 60 40 70" />
                                    <path d="M20 190C10 140 25 100 50 40" />
                                    <path d="M30 140C40 130 50 132 60 140" />
                                    <path d="M30 140C20 128 10 130 5 140" />
                                </svg>
                            </div>

                            <?php 
                                $sustainability_image = get_field('sustainability_image');
                                if($sustainability_image) :
                                    echo '<img src="'. $sustainability_image['url'] .'" alt="'. $sustainability_image['alt'] .'" class="globe-image">';
                                endif;
                            ?>

                        </div>
                    </div>
                <?php endif; ?>

                <?php 
                    $reviews_showhide = get_field('reviews_showhide');
                    if ( have_rows('reviews') && $reviews_showhide ) :
                ?>
                    <div class="col-12 col-lg-6">
                        <div class="testimonials-box">
                            <div>
                                <?php 
                                    $reviews_heading = get_field('reviews_heading');
                                    if($reviews_heading) :
                                        echo '<h2 class="testimonials-title">'. $reviews_heading .'</h2>';
                                    endif;
                                ?>

                                <div class="testimonials-slider-wrapper">
                                   
                                    <button type="button" class="testimonial-arrow prev" id="testimonialPrevBtn"
                                        aria-label="Previous Testimonial">
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                    
                                    <div class="testimonials-scroll-track" id="testimonialsTrack">
                                        <?php 
                                            $i = 1;
                                            while ( have_rows('reviews') ) : the_row(); 

                                                $user_info = get_sub_field('user_info');
                                                $review_content = get_sub_field('review_content');

                                                    if ($i % 3 == 1) {
                                                        $color1 = '#e9d5ff';
                                                        $color2 = '#6d28d9';
                                                    } elseif ($i % 3 == 2) {
                                                        $color1 = '#fbcfe8';
                                                        $color2 = '#be185d';
                                                    } else {
                                                        $color1 = '#ede9fe';
                                                        $color2 = '#4c1d95';
                                                    }

                                                ?>

                                                <div class="testimonial-slide-item">
                                                    <div class="testimonial-card">
                                                        <div>
                                                            <div class="stars-rating">
                                                                <i class="bi bi-star-fill"></i>
                                                                <i class="bi bi-star-fill"></i>
                                                                <i class="bi bi-star-fill"></i>
                                                                <i class="bi bi-star-fill"></i>
                                                                <i class="bi bi-star-fill"></i>
                                                            </div>
                                                            <?php 
                                                                if($review_content) : 
                                                                    echo '<p class="quote-text">'. $review_content .'</p>';
                                                                endif; 
                                                            ?>
                                                        </div>
                                                        <div class="user-profile-meta">
                                                            <div class="user-avatar">
                                                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
                                                                    <circle cx="18" cy="18" r="18" fill="<?php echo $color1; ?>" />
                                                                    <circle cx="18" cy="14" r="6" fill="<?php echo $color2; ?>" />
                                                                    <path d="M6 32C6 26 11 22 18 22C25 22 30 26 30 32"
                                                                        fill="<?php echo $color2; ?>" />
                                                                </svg>
                                                            </div>
                                                            <?php
                                                                if($user_info) :
                                                                    echo '<div class="user-info">'. $user_info .'</div>';
                                                                endif;
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php 
                                                $i++;
                                            endwhile; 
                                        ?>
                                    </div>

                                    <button type="button" class="testimonial-arrow next" id="testimonialNextBtn"
                                        aria-label="Next Testimonial">
                                        <i class="bi bi-chevron-right"></i>
                                    </button>

                                </div>
                            </div>
                            
                            <div class="carousel-indicators" id="testimonialDots">
                                <?php 
                                    $total_reviews = count(get_field('reviews'));
                                    for ($j = 1; $j <= $total_reviews; $j++) {
                                        $active_class = ($j === 1) ? 'active' : '';
                                        echo '<button type="button" class="carousel-dot ' . $active_class . '" aria-label="Slide ' . $j . '"></button>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>  
                <?php endif; ?>             

                <?php 
                    $newsletter_showhide = get_field('newsletter_showhide');
                    if ( $newsletter_showhide ) :
                ?>
                    <div class="col-12 col-lg-6">
                        <div class="newsletter-box" id="guide-section">
                            <div class="newsletter-content">
                                <?php
                                    $newsletter_heading = get_field('newsletter_heading');
                                    if($newsletter_heading) :
                                        echo '<h2 class="newsletter-title">'. $newsletter_heading .'</h2>';
                                    endif;
                                    $newsletter_details = get_field('newsletter_details');
                                    if($newsletter_details) :
                                        echo '<p class="newsletter-subtitle">'. $newsletter_details .'</p>';
                                    endif;

                                    $newsletter_shortcode = get_field('newsletter_shortcode');
                                    if($newsletter_shortcode) :
                                        echo do_shortcode($newsletter_shortcode);
                                    endif;
                                ?>
                            </div>

                            <div class="newsletter-lavender-bg">
                                <svg width="180" height="260" viewBox="0 0 120 200" fill="none" stroke="#9333ea"
                                    stroke-width="1.6" stroke-linecap="round">
                                    <path d="M20 190C45 150 75 100 100 20" />
                                    <path d="M40 195C60 160 85 110 105 40" />
                                    <path d="M10 185C30 155 60 115 85 55" />
                                    <circle cx="95" cy="35" r="3" fill="#a855f7" />
                                    <circle cx="98" cy="25" r="3.5" fill="#9333ea" />
                                    <circle cx="88" cy="45" r="3" fill="#c084fc" />
                                    <circle cx="100" cy="50" r="3" fill="#a855f7" />
                                    <circle cx="102" cy="65" r="3" fill="#9333ea" />
                                    <circle cx="90" cy="60" r="2.5" fill="#c084fc" />
                                    <circle cx="80" cy="70" r="3" fill="#a855f7" />
                                    <circle cx="85" cy="85" r="3" fill="#9333ea" />
                                    <circle cx="75" cy="80" r="2.5" fill="#c084fc" />
                                    <path d="M65 95C70 90 78 88 85 92" stroke="#a855f7" stroke-width="2" />
                                    <path d="M75 60C82 55 90 54 96 58" stroke="#9333ea" stroke-width="2" />
                                    <path d="M85 35C92 30 98 30 104 35" stroke="#c084fc" stroke-width="2" />
                                    <path d="M45 145C35 135 25 138 18 148" />
                                    <path d="M55 125C48 115 38 118 30 128" />
                                    <path d="M68 105C78 95 88 97 95 107" />
                                </svg>
                            </div>

                        </div>
                    </div>
                <?php endif; ?>   

            </div>
        </div>
    </section>

<?php get_footer(); ?> 