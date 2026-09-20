<?php
    function inner_hero($hero_banner='') {
        if ( is_home() ) {
            $id = get_option('page_for_posts');
        } elseif ( is_page() ) {
            $id = get_the_ID();
        } elseif ( is_singular() ) {
            $id = get_the_ID();
        } elseif (is_category()) {
            $id = 'category_' . get_queried_object_id();
        } elseif (is_tax()) {
            $term = get_queried_object();
            $id = $term->taxonomy . '_' . $term->term_id;
        } else {
            $id = get_queried_object_id();
        }

        if( !$hero_banner ) :
            $hero_banner = get_field('hero_banner', $id);
        endif;

        if (is_category() || is_tax()) {
            $page_name = get_queried_object()->name;
            $heading = 'Category: <span class="text-magenta">' . get_queried_object()->name . '</span>';
            $description = get_queried_object()->description;
        } elseif (is_search()) {
            $page_name = 'Search Results';
            $heading = 'Search Results for: <span class="text-magenta">' . get_search_query() . '</span>';
            $description = '';
        } elseif ($hero_banner) {
            $page_name = $hero_banner['page_name'];
            $heading = $hero_banner['heading'];
            $description = $hero_banner['description'];
        }
        else {
            $page_name = '';
            $heading = '';
            $description = '';
        }
    
        $current_endpoint = WC()->query->get_current_endpoint();
        $my_account_url = wc_get_page_permalink( 'myaccount' );
        $order_id = absint( get_query_var('view-order') );
        ?>
        <section class="page-hero-section">
            <div class="container">
                <ul class="page-breadcrumb justify-content-center">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                    <?php
                        $ancestors = get_post_ancestors($id);
                        
                        if (!empty($ancestors)) :
                            $ancestors = array_reverse($ancestors);
                            foreach ($ancestors as $ancestor) :
                            ?>
                            <li>/</li>
                            <li>
                                <a href="<?php echo esc_url(get_permalink($ancestor)); ?>">
                                    <?php echo esc_html(get_the_title($ancestor)); ?>
                                </a>
                            </li>
                            <?php
                            endforeach;

                        elseif (is_page_template('template/template-video_library.php')) :
                            echo '<li>/</li>';
                            echo '<li>
                                    <a href="javascript:void(0);">
                                        Gallery
                                    </a>
                                </li>';

                        elseif (is_page_template('template/template-image_gallery.php')) :
                            echo '<li>/</li>';
                            echo '<li>
                                    <a href="javascript:void(0);">
                                        Gallery
                                    </a>
                                </li>';

                        elseif (is_account_page() && $current_endpoint) :
                            echo '<li>/</li>';
                            echo '<li>
                                    <a href="'.wc_get_page_permalink('myaccount').'">
                                        My Account
                                    </a>
                                </li>';

                            $endpoint_names = [
                                'orders'            => 'Orders',
                                'view-order'        => 'Order',
                                'downloads'         => 'Downloads',
                                'edit-account'      => 'Account Details',
                                'edit-address'      => 'Addresses',
                                'payment-methods'   => 'Payment Methods',
                            ];

                            $page_name = !empty($page_name)
                                ? $page_name
                                : ($endpoint_names[$current_endpoint] ?? 'Dashboard');

                            if ($current_endpoint === 'view-order') :
                                $page_name = $page_name .' #' . $order_id; 
                            endif;

                        elseif (is_checkout()) :
                            $cart_id = url_to_postid( wc_get_cart_url() );
                            $cart_hero_banner = get_field('hero_banner', $cart_id);
                            $cart_name = $cart_hero_banner['page_name'];

                            if($cart_name) {
                                $parent_cart_name = $cart_name;
                            } else {
                                $parent_cart_name = 'Shopping Bag';
                            }

                            echo '<li>/</li>';
                            echo '<li>
                                    <a href="'.esc_url( wc_get_cart_url() ).'">
                                        '.$parent_cart_name.'
                                    </a>
                                </li>';
                                
                        elseif(is_singular('image-gallery')) :

                            $pages = get_pages([
                                'meta_key'   => '_wp_page_template',
                                'meta_value' => 'template/template-image_gallery.php',
                            ]);
                            
                            if (!empty($pages)) {
                                $page = $pages[0];

                                $gallery_link = get_permalink($page->ID);
                            } else {
                                $gallery_link = 'javascript:void(0);';
                            }

                            echo '<li>/</li>';
                            echo '<li>
                                    <a href="'. esc_url($gallery_link) .'">
                                        Gallery
                                    </a>
                                </li>';
                        endif;
                    ?>
                    <li>/</li>
                    <li class="text-dark fw-bold">
                        <?php
                            if($page_name) {
                                echo $page_name;
                            } else {
                                echo get_the_title($id);
                            }
                        ?>
                    </li>
                </ul>
                <?php
                    if($heading) :
                        echo '<h1 class="page-hero-title">' . $heading . '</h1>';
                    endif;
                    if($description) :
                        echo '<p class="lead text-muted max-w-700 mx-auto mb-0" style="max-width: 680px;">' . $description . '</p>';
                    endif;
                ?>
            </div>
        </section>
        <?php
    }
?>