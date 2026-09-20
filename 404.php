<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 */

get_header(); ?>

<div class="error-wrap">
	<div class="error-box">
		<div class="error-illustration">🌸</div>
		<div class="error-number">404</div>
		<?php 
			$error_heading = get_field('error_heading', 'option');
			$error_description = get_field('error_description', 'option');
			$error_footer = get_field('error_footer', 'option');

			if($error_heading) :
				echo '<h1 class="fw-bold text-dark mt-3 mb-2" style="font-size:1.6rem;">'.$error_heading.'</h1>';
			endif;

			if($error_description) :
				echo '<p class="text-muted mb-0" style="max-width:420px; margin:0 auto;">'.$error_description.'</p>';
			endif;

			if ( have_rows('error_quick_links', 'option') ) : 
				echo '<div class="quick-links-grid">';
					while ( have_rows('error_quick_links', 'option') ) : the_row(); 
						$icon = get_sub_field('icon');
						$choose_page = get_sub_field('choose_page');

						echo '<a href="'.esc_url(get_permalink($choose_page->ID)).'" class="ql-btn"><i class="'.$icon.'"></i> '.$choose_page->post_title.'</a>';
					endwhile;
				echo '</div>'; 
			endif;

			if($error_footer) :
				echo '<div class="text-muted small mt-4 mb-0">'.$error_footer.'</div>';
			endif;
		?>
	</div>
</div>

<?php get_footer(); ?>