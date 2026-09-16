<?php
/**
 * My Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-address.php.
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

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => __( 'Billing address', 'woocommerce' ),
			'shipping' => __( 'Shipping address', 'woocommerce' ),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'Billing address', 'woocommerce' ),
		),
		$customer_id
	);
}

$oldcol = 1;
$col    = 1;
?>

<div class="about-story-box p-4 bg-white woocommerce-Address">

	<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
		<h4 class="fw-bold text-dark mb-0">Your Saved Addresses</h4>
		<!-- <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addAddressModal">
			<i class="bi bi-plus-circle me-1"></i> Add New Address
		</button> -->
	</div>

	<div class="row g-4">
		<?php foreach ( $get_addresses as $name => $address_title ) : ?>
			
			<?php
				$address = wc_get_account_formatted_address( $name );

				if($name == 'billing') {

				} elseif($name == 'shipping') {

				}
			?>

			<div class="col-12 col-md-6">
				<div class="about-value-card p-4 h-100 border border-magenta bg-pink-light">

					<div class="d-flex justify-content-between align-items-start mb-2">
						
						<?php 
							if($name == 'billing') {
								$icon = '<i class="bi bi-house-door text-magenta fs-5"></i>';
								$badge_class = 'bg-dark text-white';
							} elseif($name == 'shipping') {
								$icon = '<i class="bi bi-building text-dark fs-5"></i>';
								$badge_class = 'bg-light text-dark border';
							}
						?>

						<span class="badge <?php echo $badge_class; ?> px-3 py-1 rounded-pill fs-8"><?php echo esc_html( $address_title ); ?></span>
						<?php echo $icon; ?>

					</div>

					<address class="text-dark small mb-3">
						<?php
							echo $address ? wp_kses_post( $address ) : esc_html_e( 'You have not set up this type of address yet.', 'woocommerce' );

							do_action( 'woocommerce_my_account_after_my_address', $name );
						?>
					</address>

					<div class="d-flex justify-content-between align-items-center pt-3 border-top">
						<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="btn btn-sm btn-outline-dark rounded-pill px-3 edit">
							<?php
								printf(
									/* translators: %s: Address title */
									$address ? esc_html__( 'Edit %s', 'woocommerce' ) : esc_html__( 'Add %s', 'woocommerce' ),
									esc_html( $address_title )
								);
							?>
						</a>
						<!-- <button class="btn btn-sm btn-link text-danger p-0 text-decoration-none" onclick="alert('Address removed.');">Remove</button> -->
					</div>

				</div>
			</div>
		<?php endforeach; ?>
	</div>

</div>