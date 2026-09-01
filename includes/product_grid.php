<?php 
    function product_grid() {

        global $product;

        $category = get_the_terms( get_the_ID(), 'product_cat' );
        if ( $category && ! is_wp_error( $category ) ) {
            $cat = esc_html( $category[0]->slug );
        } else {
            $cat = 'all';
        }

        $product_url = get_variation_product_url($product);
        $product_name = get_variation_display_name($product);

        ?>
        <div class="col-12 col-md-6 col-lg-4 product-item-card" data-category="<?php echo $cat; ?>">
            <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden"
                style="transition: transform 0.3s ease;">
                <div class="position-relative bg-pink-light p-4 text-center" style="height: 290px; display: flex; align-items: center; justify-content: center;">
                    <?php
                        $product_badge = '';

                        if ($product) {
                            if ($product->is_type('variation')) {
                                $parent_id = $product->get_parent_id();
                                $product_badge = get_field('product_badge',$parent_id);

                            } else {
                                $product_badge = get_field( 'product_badge', $product->get_id() );
                            }
                        }
                        
                        if($product_badge) :
                            if($product_badge["value"] == 'new') {
                                echo '<span class="badge bg-success text-white position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill">'.$product_badge["label"].'</span>';
                            } elseif($product_badge["value"] == 'coming_soon') {
                                echo '<span class="badge bg-info text-dark position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill fw-bold">'.$product_badge["label"].'</span>';
                            } elseif($product_badge["value"] == 'most_opular') {
                                echo '<span class="badge bg-dark text-white position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill">'.$product_badge["label"].'</span>';
                            } elseif($product_badge["value"] == 'best_seller') {
                                echo '<span class="badge bg-magenta text-white position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill">'.$product_badge["label"].'</span>';
                            }
                        endif;
                    ?>
                    <a href="<?php echo $product_url; ?>">
                        <?php 

                            if ($product->is_type('variation')) {
                                $image_id = $product->get_image_id();
                                if (!$image_id) {
                                    $parent_product = wc_get_product(
                                        $product->get_parent_id()
                                    );

                                    if ($parent_product) {
                                        $image_id = $parent_product->get_image_id();
                                    }
                                }
                            } else {
                                $image_id = $product->get_image_id();
                            }

                            if ($image_id) {
                                $image_url = wp_get_attachment_image_url(
                                    $image_id,
                                    'full'
                                );
                            } else {
                                $image_url = get_template_directory_uri() . '/assets/images/placeholder.webp';
                            }
                        ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo $product_name; ?>" class="img-fluid" style="max-height: 230px; transition: transform 0.3s ease;">
                    </a>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">

                            <?php
                                $tags = get_the_terms( get_the_ID(), 'product_tag' );
                                if ( $tags && ! is_wp_error( $tags ) ) :
                                    echo '<span class="badge bg-light text-magenta border border-light-subtle rounded-pill px-3 py-1 fs-8 fw-semibold">';
                                        echo esc_html( implode( ' • ', wp_list_pluck( $tags, 'name' ) ) );
                                    echo '</span>';
                                endif;
                            ?>
                            <!-- to do -->
                            <div class="text-warning small">
                                <?php
                                $rating = $product->get_average_rating();
                                $count  = $product->get_review_count();

                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= floor($rating)) {
                                        echo '<i class="bi bi-star-fill"></i>';
                                    } elseif ($i - $rating < 1) {
                                        echo '<i class="bi bi-star-half"></i>';
                                    } else {
                                        echo '<i class="bi bi-star"></i>';
                                    }
                                }
                                ?>
                                <span class="text-muted ms-1"> (<?php echo esc_html($count); ?>) </span>
                            </div>
                            <!-- to do end-->
                        </div>
                        <h4 class="card-title fw-bold text-dark mb-2">
                            <a href="<?php echo $product_url; ?>" class="text-dark text-decoration-none"><?php echo $product_name; ?></a>
                        </h4>
                        <p class="card-text text-muted small mb-3">
                            <?php
                                $description = '';

                                if ($product->is_type('variation')) {
                                    $description = $product->get_description();

                                    if (empty(trim(wp_strip_all_tags($description)))) {

                                        $parent_product = wc_get_product(
                                            $product->get_parent_id()
                                        );

                                        if ($parent_product) {
                                            $description = $parent_product->get_short_description();
                                        }
                                    }

                                } else {
                                    $description = $product->get_short_description();
                                }

                                echo apply_filters( 'woocommerce_short_description',$description );
                            ?>
                        </p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                        <?php 
                            $regular_price = $product->get_regular_price();
                            $sale_price    = $product->get_sale_price();

                            if ( $product->is_on_sale() ) {
                                echo '<div>
                                        <span class="text-decoration-line-through text-muted small me-1">' . eco_price( $regular_price ) . '</span>
                                        <span class="fs-4 fw-bold text-dark">' . eco_price( $sale_price ) . '</span>
                                    </div>';
                            } else {
                                echo '<span class="fs-4 fw-bold text-dark">' . eco_price( $regular_price ) . '</span>';
                            }
                        ?>
                        <!-- to do -->
                        <?php 
                            if($product_badge && $product_badge["value"] == 'coming_soon') {
                                echo '<a href="'. $product_url .'" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-semibold fs-7 shadow-sm">
                                        Notify Me <i class="bi bi-bell ms-1"></i>
                                    </a>';
                            } else {
                                echo '<a href="'. $product_url .'" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold fs-7 shadow-sm">
                                        View Details <i class="bi bi-arrow-right ms-1"></i>
                                    </a>';
                            }
                        ?>
                        <!-- to do end-->
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    function product_pagination($current_page, $total_pages) {
        if ($total_pages > 1): 
            ?>
            <div class="d-flex justify-content-center align-items-center mt-5 pt-4">
                <nav aria-label="Product navigation">
                    <ul class="pagination ecobloom-pagination align-items-center mb-0">

                        <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link ajax-pagination" 
                            href="<?php echo esc_url(get_pagenum_link($current_page - 1)); ?>" 
                            data-page="<?php echo $current_page - 1; ?>" 
                            aria-label="Previous">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                                <a class="page-link ajax-pagination"
                                style="width:40px; height:40px;"
                                href="<?php echo esc_url(get_pagenum_link($i)); ?>"
                                data-page="<?php echo $i; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>                       

                        <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link ajax-pagination" 
                            href="<?php echo esc_url(get_pagenum_link($current_page + 1)); ?>" 
                            data-page="<?php echo $current_page + 1; ?>" 
                            aria-label="Next">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>
            <?php 
        endif;
    }

?>