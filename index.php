<!-- Blog / Blog category / Blog search results Template -->
<?php get_header(); ?>

    <?php inner_hero(); ?>

    <?php if(have_posts()): ?>
        <section class="py-5 my-3">
            <div class="container">
                <div id="blog-results">
                    <?php 
                        echo '<div class="blog-grid">';
                            while(have_posts()): the_post();
                                blog_grid(); 
                            endwhile;
                        echo '</div>';

                        $current_page = max(1, get_query_var('paged'));
                        $total_pages   = $wp_query->max_num_pages;

                        blog_pagination($current_page, $total_pages);
                    ?>
                </div> 
            </div>
        </section>

    <?php else: ?>
        <section class="py-5 my-3 text-center">
            <div class="container">
                <p>No posts found.</p>
            </div>
        </section>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>

    <?php faq_accordion(); ?>

<?php get_footer(); ?>