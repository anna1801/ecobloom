<!-- WordPress default template for Shop -->
<?php get_header(); ?> 

    <?php inner_hero(); ?>

    <div id="to-top"></div>

    <?php if ( is_shop() ) : ?>
        <section class="py-4 bg-white border-bottom">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-center gap-2 product_category">
                    <button type="button"
                        class="btn btn-primary rounded-pill px-4 py-2 fw-semibold fs-7 shadow-sm ecobloom-filter-btn active"
                        data-filter="all">All Products</button>
                
                    <?php
                    $categories = get_terms([
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => true,
                        'exclude'    => [get_option('default_product_cat')],
                    ]);

                    foreach ($categories as $category) :
                    ?>
                        <button type="button"
                            class="btn btn-outline-dark rounded-pill px-4 py-2 fw-semibold fs-7 ecobloom-filter-btn"
                            data-filter="<?php echo esc_attr($category->slug); ?>">
                            <?php echo esc_html($category->name); ?>
                        </button>
                    <?php endforeach; ?>

                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php global $wp_query; ?>

    <?php if(have_posts()): ?>
        <section class="py-5 my-3">
            <div class="container">
                <div id="product-results" ?>
                    <?php 
                        echo '<div class="row g-4">';
                            while(have_posts()): the_post();
                                product_grid(); 
                            endwhile;
                        echo '</div>';

                        $current_page = max(1, get_query_var('paged'));
                        $total_pages   = $wp_query->max_num_pages;

                        product_pagination($current_page, $total_pages);
                    ?>
                </div>
            </div>
        </section>
    <?php else: ?>
        <section class="py-5 my-3 text-center">
            <div class="container">
                <p>No Products found.</p>
            </div>
        </section>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>

<?php get_footer(); ?>