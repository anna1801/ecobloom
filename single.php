<!-- Blog Single Page Template -->
<?php get_header(); ?>

    <?php
        $author_id = get_post_field('post_author', get_the_ID());
        $author_name = get_the_author_meta('display_name', $author_id);

        $blog_page_id    = get_option('page_for_posts');
        $blog_page_title = get_the_title($blog_page_id);
        $blog_page_url   = get_permalink($blog_page_id);
    ?>

    <section class="page-hero-section blog-hero-section">
        <div class="container">
            <ul class="page-breadcrumb justify-content-center">
                <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                <?php
                    if ($blog_page_id) :
                        echo '<li>/</li>';
                        echo '<li><a href="' . esc_url($blog_page_url) . '">' . esc_html($blog_page_title) . '</a></li>';
                    endif;
                ?>
                <li>/</li>
                <li class="text-dark fw-bold">
                    <?php
                        $post_name = get_field('post_name');
                        if ($post_name) {
                            echo esc_html($post_name);
                        } else {
                            the_title();
                        }
                    ?>
                </li>
            </ul>
            <div class="d-flex justify-content-center mb-3">
                <span class="badge rounded-pill px-3 py-2 fw-600" style="background:#fdf2f8; color:#be185d; border:1.5px solid #fce7f3; font-size:.82rem;">
                    <i class="bi bi-heart-pulse me-1"></i> <?php the_category(', '); ?>
                </span>
            </div>
            <h1 class="page-hero-title"><?php the_title(); ?></h1>
            <div class="article-meta justify-content-center">
                <span class="meta-badge"><i class="bi bi-person-circle"></i> <?php echo $author_name; ?></span>
                <span class="meta-badge"><i class="bi bi-calendar3"></i><?php the_time('F j, Y'); ?></span>
                <?php
                    $reading_time = get_field('reading_time');
                    if ($reading_time) :
                        echo '<span class="meta-badge"><i class="bi bi-clock"></i> ' . esc_html($reading_time) . ' read</span>';
                    endif;
                ?>
                <span class="meta-badge"><i class="bi bi-eye"></i> <?php echo (int) get_post_meta(get_the_ID(), 'post_views', true); ?> views</span>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container py-3">      
            <div class="row g-5">
                <div class="col-12 col-lg-8">
                    <article class="blog-details-content w-100">

                        <?php
                            if (has_post_thumbnail()) :
                                $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                                echo '<div class="blog-details-img" style="background-image: url(' . esc_url($image_url) . ');"></div>';
                            endif;
                        ?>

                        <div class="blog-post-meta">
                            <span><i class="bi bi-calendar3"></i> <?php the_time('F j, Y'); ?></span>
                            <span><i class="bi bi-folder"></i> <?php the_category('<span class="dot"></span>'); ?></span>
                            <span><i class="bi bi-person-circle"></i> <?php echo $author_name; ?></span>
                        </div>

                        <div class="blog-post-body">
                            <h2><?php the_title(); ?></h2>
                            <?php the_content(); ?>
                        </div>

                        <div class="share-bar mt-4 mb-2">
                            <span class="fw-bold text-dark fs-7"><i class="bi bi-hand-thumbs-up text-magenta me-2"></i>Found this helpful? Share it!</span>
                            <?php
                                $post_url   = get_permalink();
                                $post_title = get_the_title();
                            ?>
                            <div class="d-flex align-items-center gap-2 flex-wrap mt-2">
                                <a href="https://api.whatsapp.com/send?text=<?php echo rawurlencode($post_title . ' ' . $post_url); ?>" target="_blank" rel="noopener" class="share-btn wa btn btn-sm text-white" style="background-color:#25D366; border-radius:50%; width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center;"><i class="bi bi-whatsapp"></i></a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode($post_url); ?>" target="_blank" rel="noopener" class="share-btn fb btn btn-sm text-white" style="background-color:#1877F2; border-radius:50%; width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center;"><i class="bi bi-facebook"></i></a>
                                <a href="https://twitter.com/intent/tweet?text=<?php echo rawurlencode($post_title); ?>&url=<?php echo rawurlencode($post_url); ?>" target="_blank" rel="noopener" class="share-btn tw btn btn-sm text-white" style="background-color:#000000; border-radius:50%; width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center;"><i class="bi bi-twitter-x"></i></a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo rawurlencode($post_url); ?>" target="_blank" rel="noopener" class="share-btn li btn btn-sm text-white" style="background-color:#0A66C2; border-radius:50%; width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center;"><i class="bi bi-linkedin"></i></a>
                                <a href="https://instagram.com" target="_blank" rel="noopener" class="share-btn ig btn btn-sm text-white" style="background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%); border-radius:50%; width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center;"><i class="bi bi-instagram"></i></a>
                                <button data-url="<?php echo esc_url($post_url); ?>" class="share-btn cp btn btn-sm text-dark bg-light border" style="border-radius:50%; width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center;"><i class="bi bi-link-45deg"></i></button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="<?php echo esc_url($blog_page_url); ?>" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold small">
                                <i class="bi bi-arrow-left me-2"></i>Back to Blog
                            </a>
                            <?php
                                $categories = get_the_category();

                                if (!empty($categories)) {
                                    $more_link = esc_url(get_category_link($categories[0]->term_id));
                                } else {
                                    $more_link = esc_url($blog_page_url);
                                }
                            ?>
                            <a href="<?php echo $more_link; ?>" class="btn btn-primary rounded-pill px-4 py-2 fw-bold small" style="background-color: var(--bs-primary); border-color: var(--bs-primary);">
                                More Articles <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>

                    </article>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="blog-sidebar" style="position: sticky; top: 120px;">
                        
                        <div class="card border-0 rounded-4 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3 fs-6">Search Articles</h5>
                                <form role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>">
                                    <div class="input-group">
                                        <input type="search" class="form-control bg-light border-0" placeholder="Keywords..." name="s" value="<?php echo get_search_query(); ?>" aria-label="Search">
                                        <input type="hidden" name="post_type" value="post">
                                        <button class="btn text-white" type="submit" style="background-color: var(--bs-primary);" aria-label="Search"><i class="bi bi-search"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card border-0 rounded-4 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3 fs-6">Categories</h5>
                                <ul class="list-unstyled mb-0">
                                    <?php
                                        $categories = get_categories([
                                            'hide_empty' => true,
                                        ]);

                                        if ($categories) :
                                            foreach ($categories as $category) :
                                                ?>
                                                <li class="mb-2">
                                                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                                                    class="text-decoration-none text-muted d-flex justify-content-between align-items-center">

                                                        <span>
                                                            <i class="bi bi-chevron-right me-2 text-magenta" style="font-size: 0.75rem;"></i>
                                                            <?php echo esc_html($category->name); ?>
                                                        </span>

                                                        <span class="badge bg-light text-dark rounded-pill">
                                                            <?php echo esc_html($category->count); ?>
                                                        </span>

                                                    </a>
                                                </li>
                                                <?php
                                            endforeach;
                                        endif;
                                    ?>
                                </ul>
                            </div>
                        </div>

                        <?php
                            $recent_posts = new WP_Query([
                                'post_type'      => 'post',
                                'posts_per_page' => 3,
                                'post_status'    => 'publish',
                                'orderby'        => 'date',
                                'order'          => 'DESC',
                                'post__not_in'   => [get_the_ID()],
                            ]);

                            if ($recent_posts->have_posts()) :
                                ?>
                                <div class="card border-0 rounded-4 shadow-sm mb-4">
                                    <div class="card-body p-4">
                                        <h5 class="fw-bold mb-3 fs-6">Recent Posts</h5>
                                        <?php
                                            while ($recent_posts->have_posts()) :
                                                $recent_posts->the_post();

                                                if (has_post_thumbnail()) {
                                                    $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                                                } else {
                                                    $image_url = get_template_directory_uri() . '/assets/images/placeholder.webp';
                                                }
                                                ?>
                                                <div class="d-flex align-items-center mb-3">
                                                    <img src="<?php echo esc_url($image_url); ?>" class="rounded-3" style="width: 70px; height: 70px; object-fit: cover;" alt="Recent Post">
                                                    <div class="ms-3">
                                                        <div class="small text-muted mb-1"><i class="bi bi-calendar3 me-1"></i> <?php the_time('F j, Y'); ?></div>
                                                        <a href="<?php the_permalink(); ?>" class="fw-bold text-dark text-decoration-none fs-7 line-clamp-2"><?php the_title(); ?></a>
                                                    </div>
                                                </div>
                                                <?php
                                            endwhile;
                                        ?>
                                    </div>
                                </div>
                                <?php
                                wp_reset_postdata();
                            endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php faq_accordion(); ?>

<?php get_footer(); ?>