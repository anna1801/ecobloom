<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header class="navbar-sticky-wrapper" id="top">
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 border-bottom border-light-subtle ecobloom-navbar">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php
                        $logo = get_field('logo', 'option');
                        if ($logo) { 
                            echo '<img src="' . esc_url($logo['url']) . '" alt="' . esc_attr($logo['alt']) . '" style="max-height: 45px; width: auto;" class="img-fluid brand-logo">';
                        } else {
                            echo get_bloginfo('name');
                        }
                    ?>
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarContent">
                    <?php
                        wp_nav_menu(array(
                            'theme_location' => 'header-menu',
                            'container'      => false,
                            'menu_class'     => 'navbar-nav mx-auto mb-2 mb-lg-0',
                            'fallback_cb'    => false,
                            'walker'         => new Header_Navwalker(),
                        ));
                    ?>

                    <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">

                        <?php 
                            $my_account_url = wc_get_page_permalink( 'myaccount' ); 
                        ?>

                        <div class="account-dropdown">
                            <a href="#" class="nav-icon-btn" aria-label="My Account">
                                <i class="bi bi-person"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">

                                <?php if ( is_user_logged_in() ) : ?>

                                    <a class="dropdown-item" href="<?php echo $my_account_url; ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
                                    
                                <?php else : ?>

                                    <?php
                                        $loginpages = get_pages( array(
                                            'meta_key'   => '_wp_page_template',
                                            'meta_value' => 'template/template-login.php',
                                            'number'     => 1,
                                        ) );

                                        if ( ! empty( $loginpages ) ) :
                                            $login_url = get_permalink( $loginpages[0]->ID );
                                            echo '<a class="dropdown-item" href="'.$login_url.'"><i class="bi bi-box-arrow-in-right"></i> Sign In</a>';
                                        endif;
                                    ?>

                                    <?php
                                        $registerpages = get_pages( array(
                                            'meta_key'   => '_wp_page_template',
                                            'meta_value' => 'template/template-register.php',
                                            'number'     => 1,
                                        ) );

                                        if ( ! empty( $registerpages ) ) :
                                            $register_url = get_permalink( $registerpages[0]->ID );
                                            echo '<a class="dropdown-item" href="'.$register_url.'"><i class="bi bi-person-plus"></i> Sign Up</a>';
                                        endif;
                                    ?>

                                    <a class="dropdown-item" href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><i class="bi bi-key"></i> Forgot Password</a>

                                <?php endif; ?>

                            </div>
                        </div>

                        <button class="nav-icon-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sideCart"
                            aria-controls="sideCart" aria-label="Open Shopping Bag">
                            <i class="bi bi-bag"></i>
                            <span class="cart-badge-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                        </button>
                    </div>

                </div>
            </div>
        </nav>
    </header>

    <main>