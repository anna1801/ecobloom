<?php 
    function faq_accordion() {
        
        if ( is_home() ) {
            $id = get_option('page_for_posts');
        } elseif ( is_page() ) {
            $id = get_the_ID();
        } elseif ( is_singular() ) {
            $id = get_the_ID();
        } else {
            $id = get_queried_object_id();
        }

        ?>
        <?php if ( have_rows('faq', $id) ) : ?>
            <section class="py-5 bg-section-alt" id="faqs">
                <div class="container py-3">
                    <div class="text-center max-w-700 mx-auto mb-5" style="max-width: 680px;">
                        <?php 
                            $label = get_field('label', $id);
                            $heading = get_field('heading', $id);
                            $content = get_field('content', $id);

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

                    <div class="row justify-content-center">
                        <div class="col-12 col-lg-9">
                            <div class="accordion ecobloom-faq-accordion" id="aboutFaqAccordion">
                                <?php
                                    while ( have_rows('faq', $id) ) : the_row(); 
                                        $question = get_sub_field('question', $id);
                                        $answer = get_sub_field('answer', $id);
                                        if( $question && $answer ) :
                                            ?>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading<?php echo get_row_index(); ?>">
                                                    <button class="accordion-button <?php echo (get_row_index() != 1) ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse<?php echo get_row_index(); ?>" aria-expanded="true" aria-controls="collapse<?php echo get_row_index(); ?>">
                                                        <?php echo $question; ?>
                                                    </button>
                                                </h2>
                                                <div id="collapse<?php echo get_row_index(); ?>" class="accordion-collapse collapse <?php echo (get_row_index() == 1) ? 'show' : ''; ?>" aria-labelledby="heading<?php echo get_row_index(); ?>"
                                                    data-bs-parent="#aboutFaqAccordion">
                                                    <div class="accordion-body">
                                                        <?php echo $answer; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        endif;
                                    endwhile; 
                                ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </section>
        <?php endif; ?>
        <?php
    }
?>