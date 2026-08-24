</main>
<?php wp_footer(); ?>


    <footer class="ecobloom-footer" id="faqs-section">
        <div class="container">
            <div class="row g-4 g-lg-5 align-items-start justify-content-between">

                <?php  if ( have_rows('footer_services', 'option') ) : ?>
                    <div class="col-12 col-lg-4">
                        <div class="row g-3">
                            <?php
                                while ( have_rows('footer_services', 'option') ) : the_row(); 
                                    $icon = get_sub_field('icon');
                                    $label = get_sub_field('label');
                                    echo '<div class="col-6">';
                                        echo '<div class="footer-trust-item">';
                                            echo '<div class="footer-trust-icon">';
                                                if($icon) :
                                                    echo '<i class="' . esc_attr($icon) . '"></i>';
                                                endif;
                                            echo '</div>';
                                            if($label) :
                                                echo '<p class="footer-trust-label">' . $label . '</p>';
                                            endif;
                                        echo '</div>';
                                    echo '</div>';
                                endwhile; 
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-12 col-lg-3 text-center">
                    <?php
                        $logo = get_field('logo', 'option');
                        if ($logo) { 
                            echo '<img src="' . esc_url($logo['url']) . '" alt="' . esc_attr($logo['alt']) . '" style="max-height: 60px; width: auto; margin-bottom: 0.2rem;" class="img-fluid footer-brand-logo">';
                        } else {
                            echo get_bloginfo('name');
                        }

                        $footer_about = get_field('footer_about', 'option');
                        if ($footer_about) :
                            echo '<p class="footer-tagline">' . $footer_about . '</p>';
                        endif;

                        if ( have_rows('social_links', 'option') ) : 
                            echo '<div class="footer-socials">';
                                while ( have_rows('social_links', 'option') ) : the_row(); 
                                    $icon = get_sub_field('icon');
                                    $link = get_sub_field('link');
                                    echo '<a href="' . esc_url($link) . '" class="social-icon-btn" target="_blank" aria-label="' . esc_attr($icon) . '">';
                                        if($icon) :
                                            echo '<i class="' . esc_attr($icon) . '"></i>';
                                        endif;
                                    echo '</a>';
                                endwhile; 
                            echo '</div>';
                        endif; 
                    ?> 
                </div>

                <?php if (has_nav_menu('footer-menu')) : ?>
                    <div class="col-12 col-lg-2-5">
                        <?php
                            $menu = wp_get_nav_menu_object(get_nav_menu_locations()['footer-menu'] ?? 0);
                            if ($menu) :
                                echo '<h4 class="footer-heading">' . $menu->name . '</h4>';
                            endif;

                            wp_nav_menu(array(
                                'theme_location' => 'footer-menu',
                                'container'      => false,
                                'fallback_cb'    => false,
                                'items_wrap'     => '%3$s',
                                'walker'         => new Footer_Navwalker(),
                            ));
                        ?>
                    </div>
                <?php endif; ?>

                <?php
                    $contact = get_field('contact', 'option');
                    if($contact) :
                        ?>
                        <div class="col-12 col-lg-2-5">
                            <div class="footer-help-box">
                                <?php if (!empty($contact['heading'])) : ?>
                                    <h4 class="footer-heading"><?php echo $contact['heading']; ?></h4>
                                <?php endif; ?>
                                <?php 
                                    if (!empty($contact['contact_details'])) : 
                                        foreach ($contact['contact_details'] as $item) : 

                                            $type = $item['type'] ?? null;
                                            $value = $item['value'] ?? null;
                                        
                                            if($type == 'email' && !empty($value)) {
                                                echo '<a href="mailto:' . esc_attr($value) . '" class="footer-help-email">' . $value . '</a>';
                                            } elseif($type == 'phone' && !empty($value)) {
                                                echo '<a href="tel:' . esc_attr($value) . '" class="footer-help-phone">' . $value . '</a>';
                                            }
        
                                        endforeach;
                                    endif; 
                                ?>
                            </div>
                        </div>
                        <?php 
                    endif; 
                ?>

            </div>

            <?php 
                $copyright_text = get_field('copyright_text', 'option');
                if($copyright_text) :
                    echo '<p class="footer-copyright">' . esc_html($copyright_text) . '</p>';
                endif;
            ?>

        </div>
    </footer>








    <!-- to do -->

    <div class="offcanvas offcanvas-end side-cart-offcanvas" tabindex="-1" id="sideCart"
        aria-labelledby="sideCartLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title d-flex align-items-center gap-2" id="sideCartLabel">
                <i class="bi bi-bag-heart-fill text-magenta"></i> Your EcoBloom Bag
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body p-0 d-flex flex-column justify-content-between">
            <!-- Cart Items List Container -->
            <div class="p-4" id="cartItemsList">
                <!-- Dynamically filled by JavaScript -->
            </div>

            <!-- Cart Footer (Subtotal & Checkout) -->
            <div class="cart-footer-box">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-bold" id="cartSubtotal">₹1,499</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Shipping</span>
                    <span class="text-success fw-bold">FREE</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between mb-4">
                    <span class="fs-5 fw-bold text-dark">Total</span>
                    <span class="fs-5 fw-bold text-magenta" id="cartTotalPrice">₹1,499</span>
                </div>

                <button class="btn btn-ecobloom-primary w-100 justify-content-center mb-2"
                    onclick="alert('Proceeding to EcoBloom secure checkout. Thank you for choosing sustainable period care! 🌿');">
                    <span>Proceed to Checkout</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
                <button class="btn btn-outline-secondary w-100 rounded-pill btn-sm mt-2" data-bs-dismiss="offcanvas">
                    Continue Shopping
                </button>
            </div>
        </div>
    </div>

    <!-- to do end -->






</body>
</html>