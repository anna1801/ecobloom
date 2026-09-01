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

    <?php
        $paged = max(
            1,
            get_query_var('paged'),
            get_query_var('page')
        );

        $posts_per_page = get_option('posts_per_page');

        $category = '';

        if (is_product_category()) {
            $category = get_queried_object()->slug;
        }

        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        );

        if (!empty($category)) {

            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => $category,
                ),
            );
        }

        $product_query = new WP_Query($args);
        $archive_products = array();

        if ($product_query->have_posts()) {

            foreach ($product_query->posts as $product_id) {

                $parent_product = wc_get_product($product_id);

                if (!$parent_product) {
                    continue;
                }

                if ($parent_product->is_type('variable')) {

                    $variations = $parent_product->get_available_variations();

                    foreach ($variations as $variation_data) {

                        $variation = wc_get_product(
                            $variation_data['variation_id']
                        );

                        if (!$variation) {
                            continue;
                        }

                        if (!$variation->exists()) {
                            continue;
                        }

                        if (!$variation->is_in_stock()) {
                            continue;
                        }

                        $archive_products[] = $variation;
                    }

                } else {

                    $archive_products[] = $parent_product;
                }
            }
        }

        wp_reset_postdata();

        $total_products = count($archive_products);

        $total_pages = ceil(
            $total_products / $posts_per_page
        );

        $offset = ($paged - 1) * $posts_per_page;

        $current_products = array_slice(
            $archive_products,
            $offset,
            $posts_per_page
        );
    ?>

    <?php if (!empty($current_products)): ?>
        <section class="py-5 my-3">
            <div class="container">
                <div id="product-results">
                    <div class="row g-4">
                        <?php
                            global $product;
                            foreach ($current_products as $archive_product):
                                $product = $archive_product;
                                product_grid();
                            endforeach;
                        ?>
                    </div>
                    <?php product_pagination( $paged, $total_pages ); ?>
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