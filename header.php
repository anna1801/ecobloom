<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header class="navbar-sticky-wrapper" id="top">
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 border-bottom border-light-subtle">
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
                            'walker'         => new Bootstrap_Navwalker(),
                        ));
                    ?>


                <!-- to do -->
                    <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                        <div class="account-dropdown">
                            <a href="#" class="nav-icon-btn" aria-label="My Account">
                                <i class="bi bi-person"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item fw-bold text-magenta" href="login.html"><i class="bi bi-box-arrow-in-right"></i> Sign In</a>
                                <a class="dropdown-item" href="register.html"><i class="bi bi-person-plus"></i> Sign Up</a>
                                <a class="dropdown-item" href="forgot-password.html"><i class="bi bi-key"></i> Forgot Password</a>
                                <hr class="dropdown-divider">
                                <a class="dropdown-item" href="dashboard.html"><i class="bi bi-speedometer2"></i> Dashboard</a>
                            </div>
                        </div>

                        <button class="nav-icon-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sideCart"
                            aria-controls="sideCart" aria-label="Open Shopping Bag">
                            <i class="bi bi-bag"></i>
                            <span class="cart-badge-count">2</span>
                        </button>
                    </div>
                <!-- to do end-->





                </div>
            </div>
        </nav>
    </header>

    <main>