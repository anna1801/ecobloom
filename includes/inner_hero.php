<?php
    function inner_hero() {
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

        $hero_banner = get_field('hero_banner', $id);

        if (is_category() || is_tax()) {
            $page_name = get_queried_object()->name;
            $heading = 'Category: <span class="text-magenta">' . get_queried_object()->name . '</span>';
            $description = get_queried_object()->description;
        } elseif (is_search()) {
            $page_name = 'Search Results';
            $heading = 'Search Results for: <span class="text-magenta">' . get_search_query() . '</span>';
            $description = '';
        } else {
            $page_name = $hero_banner['page_name'];
            $heading = $hero_banner['heading'];
            $description = $hero_banner['description'];
        }
    
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