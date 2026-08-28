<?php get_header(); ?>

    <?php inner_hero(); ?>

    <section class="py-5">
        <div class="container py-3">
            <?php 
                $gallery_images = get_field('gallery_images');
                if ($gallery_images): 
                    ?>
                    <div class="masonry-grid" id="masonryGrid">
                        <?php
                            foreach ($gallery_images as $image):
                                ?>
                                <div class="masonry-item" onclick="openLightbox(this.querySelector('img').src)">
                                    <img src="<?php echo esc_url($image['url']); ?>"  alt="<?php echo esc_attr($image['alt'] ?: $image['title']); ?>" loading="lazy" >
                                    <div class="masonry-overlay"> <i class="bi bi-zoom-in"></i> </div>
                                </div>
                                <?php
                            endforeach;

                        ?>
                    </div>
                    <?php
                else :
                    echo '<p class="text-center">No gallery images found.</p>';
                endif;
            ?>
        </div>
    </section>

    <div class="lightbox-overlay" id="lightbox">
        <button class="lb-close" id="lbClose" aria-label="Close">&times;</button>
        <button class="lb-nav lb-prev" id="lbPrev" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
        <img class="lightbox-img" id="lbImg" src="" alt="Gallery Image">
        <button class="lb-nav lb-next" id="lbNext" aria-label="Next"><i class="bi bi-chevron-right"></i></button>
        <div class="lb-counter" id="lbCounter"></div>
    </div>

<?php get_footer(); ?>