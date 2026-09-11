<?php
defined( 'ABSPATH' ) || exit;

global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); 
	return;
}
?>
<section class="py-5 product-content">
    <div class="container py-3">
        <div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
            <div class="row g-5 align-items-center">
                
                <?php  do_action( 'woocommerce_before_single_product_summary' ); ?>

                <div class="col-12 col-lg-6">
                    <div class="entry-summary">
                        <?php do_action( 'woocommerce_single_product_summary' ); ?>
                    </div>

                    <?php if ( have_rows( 'product_highlights' ) ) : ?>
                        <div class="row g-3 pt-3 border-top">
                            <?php while ( have_rows( 'product_highlights' ) ) : the_row(); ?>
                                <?php
                                    $icon = get_sub_field( 'icon' );
                                    $label = get_sub_field( 'label' );
                                ?>
                                <div class="col-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <?php 
                                            if($icon) :
                                                echo '<i class="'.$icon.' text-magenta fs-5"></i>';
                                            endif;

                                            if($label) :
                                                echo '<span class="fs-7 fw-500 text-dark">'.$label.'</span>';
                                            endif;
                                        ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php do_action( 'woocommerce_after_single_product_summary' );?>

        </div>
    </div>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>

<?php if ( have_rows('product_details') ) : ?>
    <section class="py-5 bg-pink-light">
        <div class="container py-3">

            <ul class="nav nav-pills justify-content-center mb-5 gap-3 ecobloom-elegant-tabs" id="productTabs" role="tablist">
                <?php 
                    $x = 1;
                    while ( have_rows('product_details') ) : the_row(); 
                        $tab_index = get_row_index();
                        $active_tab = ( $tab_index === 1 ) ? ' active' : '';
                        if ( get_row_layout() === 'tab' ) : 
                            $tab_name = get_sub_field('tab_name');
                            $tab_icon = get_sub_field('tab_icon');
                            ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $active_tab; ?> rounded-pill px-4 py-2 fw-bold" id="tab-1" data-bs-toggle="tab"
                                    data-bs-target="#tab_<?php echo $x; ?>" type="button" role="tab" aria-controls="tab_<?php echo $x; ?>" aria-selected="true">
                                    <i class="<?php echo $tab_icon; ?> me-1"></i> <?php echo $tab_name; ?>
                                </button>
                            </li>
                            <?php 
                        endif;
                        $x++;
                    endwhile; 
                ?>
            </ul>

            <div class="tab-content bg-white p-5 rounded-4 shadow-sm border border-light-subtle" id="productTabsContent">
                <?php 
                    $i = 1;
                    while ( have_rows('product_details') ) : the_row(); 
                        $row_index = get_row_index();
                        $active_tabcontent = ( $row_index === 1 ) ? ' show active' : '';
                        if ( get_row_layout() === 'tab' ) : 
                            $column_type = get_sub_field('column_type');
                            $description = get_sub_field('description');
                            $content_box = get_sub_field('content_box');

                            if($column_type == 'full') {
                                $row_class = 'g-4';

                                $col_1_open = '';
                                $col_1_close = '';

                                $col_2_open = '';
                                $col_2_close = '';
                            } else if($column_type == '7_5') {
                                $row_class = 'g-5 align-items-center';

                                $col_1_open = '<div class="col-12 col-lg-7">';
                                $col_1_close = '</div>';
                                
                                $col_2_open = '<div class="col-12 col-lg-5">';
                                $col_2_close = '</div>';
                            } elseif($column_type == '6_6') {
                                $row_class = 'g-5 align-items-center';

                                $col_1_open = '<div class="col-12 col-lg-6">';
                                $col_1_close = '</div>';
                                
                                $col_2_open = '<div class="col-12 col-lg-6">';
                                $col_2_close = '</div>';
                            } elseif($column_type == '5_7') {
                                $row_class = 'g-5 align-items-center';

                                $col_1_open = '<div class="col-12 col-lg-5">';
                                $col_1_close = '</div>';
                                
                                $col_2_open = '<div class="col-12 col-lg-7">';
                                $col_2_close = '</div>';
                            }
                            ?>

                            <div class="tab-pane fade <?php echo $active_tabcontent; ?>" id="tab_<?php echo $i; ?>" role="tabpanel" aria-labelledby="tab-<?php echo $i; ?>">
                                <div class="row <?php echo $row_class; ?>">

                                    <?php echo $col_1_open; ?>
                                        <?php 
                                            if($description) :
                                                echo '<div class="description">'.$description.'</div>';
                                            endif;
                                        ?>
                                    <?php echo $col_1_close; ?>

                                    <?php echo $col_2_open; ?>

                                        <?php 
                                            $content_box = get_sub_field('content_box');
                                            $total = is_array($content_box) ? count($content_box) : 0;

                                            if ( have_rows( 'content_box' ) ) : 
                                                while ( have_rows( 'content_box' ) ) : the_row(); 
                                                    $number = get_row_index();
                                                    $type = get_sub_field( 'type' );
                                                    $icon = get_sub_field( 'icon' );
                                                    $title = get_sub_field( 'title' );
                                                    $description = get_sub_field( 'description' );

                                                    if($total > 1) :
                                                        if( $total % 4 === 0 ) {
                                                            echo '<div class="col-12 col-md-6 col-lg-3">';
                                                        } elseif( $total % 3 === 0) {
                                                            echo '<div class="col-12 col-md-4 col-lg-4">';
                                                        } elseif( $total % 2 === 0 ) {
                                                            echo '<div class="col-12 col-md-6 col-lg-6">'; 
                                                        } else {
                                                            echo '<div class="col-12 col-md-6 col-lg-3">';
                                                        }
                                                    endif;
                                                    
                                                        if($total == 1) {
                                                            echo '<div class="bg-pink-light p-4 rounded-4 text-center">';
                                                        } else {
                                                            echo '<div class="p-4 bg-pink-light rounded-4 h-100 border border-light-subtle">';
                                                        }
                                                                                                
                                                            if($type == 'icon_box' && $icon) {
                                                                if($total > 1) {
                                                                    $span_class = '';
                                                                    $style = 'style="font-size: 32px; width: 32px; height: 32px;"';
                                                                } else {
                                                                    $span_class = 'display-4 ';
                                                                    $style = '';
                                                                }
                                                                echo '<span class="'.$span_class.'"><i class="'.$icon.' text-magenta mb-3 d-block" '.$style.'></i></span>';
                                                            } elseif($type == 'number_box') {
                                                                if($total > 1) {
                                                                    $span_class = '';
                                                                } else {
                                                                    $span_class = 'display-4 ';
                                                                }
                                                                echo '<span class="'.$span_class.'badge bg-magenta text-white rounded-circle mb-3 d-inline-flex align-items-center justify-content-center" 
                                                                        style="width: 32px; height: 32px; font-weight: bold;">
                                                                        '.$number.'
                                                                    </span>';
                                                            }

                                                            if($title) :
                                                                echo '<h5 class="fw-bold text-dark mb-2">'.$title.'</h5>';
                                                            endif;

                                                            if($description) :
                                                                echo '<p class="text-muted small mb-0">'.$description.'</p>';
                                                            endif;
                                                            
                                                        echo '</div>';

                                                    if($total > 1) :
                                                        echo '</div>';
                                                    endif;
                                                endwhile; 
                                            endif; 
                                        ?>
                                    <?php echo $col_2_close; ?>
                                </div>
                            </div>

                            <?php 
                        endif; 
                        $i++;
                    endwhile; 
                ?>
            </div>

        </div>
    </section>
<?php endif; ?>

<?php faq_accordion(); ?>
