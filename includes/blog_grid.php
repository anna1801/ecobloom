<?php 
    function blog_grid() {
        ?>
        <div class="blog-card" data-aos="fade-up">
            <div class="blog-img" style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>');"></div>
            <div class="blog-content">
                <div class="blog-meta"><i class="bi bi-calendar3"></i> <?php echo get_the_date(); ?> &nbsp;|&nbsp; <i class="bi bi-folder"></i> <?php the_category('<span class="dot"></span>'); ?></div>
                <h3><?php the_title(); ?></h3>
                <p><?php echo wp_trim_words(get_the_excerpt(), 22, '...'); ?></p>
                <a href="<?php the_permalink(); ?>" class="blog-btn">Read More</a>
            </div>
        </div>
        <?php
    }

    function blog_pagination($current_page, $total_pages) {
        if ($total_pages > 1): 
            ?>
            <nav aria-label="Blog Page Navigation" class="mt-5">
                <ul class="pagination justify-content-center gap-2">

                    <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link rounded-pill px-3 ajax-pagination"
                        href="<?php echo esc_url(get_pagenum_link($current_page - 1)); ?>"
                        data-page="<?php echo $current_page - 1; ?>">
                            <i class="bi bi-chevron-left ms-1"></i>
                            Previous
                        </a>
                    </li>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                            <a class="page-link rounded-circle text-center ajax-pagination"
                            style="width:40px; height:40px;"
                            href="<?php echo esc_url(get_pagenum_link($i)); ?>"
                            data-page="<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link rounded-pill px-3 ajax-pagination"
                        href="<?php echo esc_url(get_pagenum_link($current_page + 1)); ?>"
                        data-page="<?php echo $current_page + 1; ?>">
                            Next
                            <i class="bi bi-chevron-right ms-1"></i>
                        </a>
                    </li>

                </ul>
            </nav>
            <?php 
        endif;
    }

?>