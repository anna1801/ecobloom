<?php
/*
Template Name: Image Gallery Page
*/

get_header();
?>

    <?php inner_hero(); ?>

    <section class="py-4 bg-white border-bottom">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center gap-2" id="galleryFilter">
                <button type="button" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold fs-7 shadow-sm filter-btn active" data-filter="all"><i class="bi bi-grid-fill me-2"></i>All Photos</button>
                <?php
                    $categories = get_terms([
                        'taxonomy'   => 'gallery-category',
                        'hide_empty' => true,
                    ]);

                    if (!empty($categories) && !is_wp_error($categories)) :
                        foreach ($categories as $category) :
                            $icon = get_field('icon', 'gallery-category_' . $category->term_id);
                            $icon_color = get_field('icon_color', 'gallery-category_' . $category->term_id);
                            if($icon_color) {
                                $color = $icon_color;
                            } else {
                                $color = 'var(--accent-pink)';
                            }
                            ?>
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold fs-7 border-0 bg-light text-dark filter-btn" data-filter="<?php echo esc_attr($category->slug); ?>">
                                <i class="<?php echo esc_attr($icon); ?> me-2" style="color: <?php echo $color; ?>;"></i>
                                <?php echo esc_html($category->name); ?>
                            </button>
                            <?php
                        endforeach;
                    endif;
                ?>
            </div>
        </div>
    </section>

    <section class="py-5 my-3">
        <div class="container">
            <div class="album-grid" id="albumGrid">
                <?php
                    $gallery_query = new WP_Query([
                        'post_type'      => 'image-gallery',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                    ]);

                    if ($gallery_query->have_posts()) :
                        while ($gallery_query->have_posts()) :
                            $gallery_query->the_post();

                            if (has_post_thumbnail()) {
                                $image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                            } else {
                                $image_url = get_template_directory_uri() . '/assets/images/placeholder.webp';
                            }

                            $terms = get_the_terms(get_the_ID(), 'gallery-category');

                            $category_slug = '';
                            if (!empty($terms) && !is_wp_error($terms)) {
                                $category_slug = $terms[0]->slug;
                            }

                            $tagline = get_the_excerpt();
                            $gallery_images = get_field('gallery_images');
                            $photo_count = is_array($gallery_images) ? count($gallery_images) : 0;
                            ?>
                            <a href="<?php the_permalink(); ?>" class="album-card gallery-item" data-category="<?php echo $category_slug; ?>">
                                <span class="album-count"><i class="bi bi-images"></i> <?php echo $photo_count; ?> Photos</span>
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title(); ?>" class="album-thumb" loading="lazy">
                                <div class="album-info">
                                    <h3><?php the_title(); ?></h3>
                                    <p><?php echo $tagline; ?></p>
                                </div>
                            </a>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                ?>
            </div>
        </div>
    </section>

<?php get_footer(); ?>