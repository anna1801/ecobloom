<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<div class="row g-4"> 
	<div class="col-12 col-lg-3">

		<div class="about-story-box p-3 bg-white woocommerce-MyAccount-navigations" aria-label="<?php esc_html_e( 'Account pages', 'woocommerce' ); ?>">
			
			<?php
				defined( 'ABSPATH' ) || exit;

				$current_user = wp_get_current_user();

				$first_name = $current_user->first_name;
				$last_name  = $current_user->last_name;

				// Fallback to display name if first/last name are not set.
				$display_name = trim( $first_name . ' ' . $last_name );

				if ( ! $display_name ) {
					$display_name = $current_user->display_name;
				}

				// Get registration date.
				$registered_date = $current_user->user_registered;
				$member_since    = date_i18n( 'Y', strtotime( $registered_date ) );

				// Get initials.
				$initials = '';

				if ( $first_name ) {
					$initials .= strtoupper( substr( $first_name, 0, 1 ) );
				}

				if ( $last_name ) {
					$initials .= strtoupper( substr( $last_name, 0, 1 ) );
				}

				if ( ! $initials ) {
					$initials = strtoupper( substr( $display_name, 0, 2 ) );
				}
			?>

			<div class="d-flex align-items-center gap-3 p-3 mb-3 border-bottom">
				<div class="rounded-circle bg-magenta text-white d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 48px; height: 48px;">
					<?php echo esc_html( $initials ); ?>
				</div>
				<div>
					<h6 class="fw-bold text-dark mb-0"><?php echo esc_html( $display_name ); ?></h6>
					<small class="text-muted">Member since <?php echo esc_html( $member_since ); ?></small>
				</div>
			</div>

			<?php 
				$items = wc_get_account_menu_items();

				$icons = array(
					'dashboard'       => 'bi-speedometer2',
					'orders'          => 'bi-box-seam',
					'downloads'       => 'bi-download',
					'edit-address'    => 'bi-geo-alt',
					'payment-methods' => 'bi-credit-card',
					'edit-account'    => 'bi-person-gear',
					'customer-logout' => 'bi-box-arrow-right',
				);
			?>
			
			<ul class="list-unstyled mb-0 d-flex flex-column gap-1">
				<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
					<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
						<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" <?php echo wc_is_current_account_menu_item( $endpoint ) ? 'aria-current="page"' : ''; ?>
							class="d-flex align-items-center gap-3 px-3 py-2 rounded-3 text-decoration-none text-dark fw-500 hover-pink">
							
							<?php if ( isset( $icons[ $endpoint ] ) ) : ?>
								<i class="bi <?php echo esc_attr( $icons[ $endpoint ] ); ?>"></i>
							<?php endif; ?>
							
							<?php echo esc_html( $label ); ?>

						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<?php do_action( 'woocommerce_after_account_navigation' ); ?>
	</div>