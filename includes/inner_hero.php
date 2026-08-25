<?php
    function inner_hero() {
        $hero_banner = get_field('hero_banner');
        if($hero_banner) : 
            
            $heading = $hero_banner['heading'];
            $description = $hero_banner['description'];
            ?>

            <section class="page-hero-section">
                <div class="container">
                    <ul class="page-breadcrumb justify-content-center">
                        <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                        <?php
                            $ancestors = get_post_ancestors(get_the_ID());
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
                        <li class="text-dark fw-bold"><?php echo esc_html(get_the_title()); ?></li>
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
        endif;
    }
?>