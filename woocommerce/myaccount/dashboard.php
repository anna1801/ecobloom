<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>

<div class="about-story-box p-4 bg-pink-light border border-magenta mb-4 myaccount-dashboard">

	<h4 class="fw-bold text-dark mb-1">Hello, <span class="text-magenta" style="text-transform: capitalize;"><?php echo esc_html( $current_user->display_name ); ?>!</span></h4>

	<p class="text-muted small mb-0">
		<?php
		$dashboard_desc = __( 'From your dashboard you can check your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">billing address</a>, and <a href="%3$s">payment methods</a>, or <a href="%4$s">download </a>our reproductive health guides.', 'woocommerce' );
		if ( wc_shipping_enabled() ) {
			$dashboard_desc = __( 'From your dashboard you can check your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">payment methods</a>, or <a href="%4$s">download </a>our reproductive health guides.', 'woocommerce' );
		}
		printf(
			wp_kses( $dashboard_desc, $allowed_html ),
			esc_url( wc_get_endpoint_url( 'orders' ) ),
			esc_url( wc_get_endpoint_url( 'edit-address' ) ),
			esc_url( wc_get_endpoint_url( 'payment-methods' ) ),
			esc_url( wc_get_endpoint_url( 'downloads' ) )
		);
		?>
	</p>
</div>

<!-- <div class="row g-4 mb-4">
	<div class="col-12 col-md-4">
		<div class="about-value-card p-4 text-center h-100">
			<i class="bi bi-recycle text-magenta mb-2" style="font-size: 2rem;"></i>
			<h3 class="fw-bold text-dark mb-0">3,000+</h3>
			<p class="text-muted small mb-0">Disposable Pads Saved</p>
		</div>
	</div>
	<div class="col-12 col-md-4">
		<div class="about-value-card p-4 text-center h-100">
			<i class="bi bi-tree text-success mb-2" style="font-size: 2rem;"></i>
			<h3 class="fw-bold text-dark mb-0">45 kg</h3>
			<p class="text-muted small mb-0">Landfill CO₂ Prevented</p>
		</div>
	</div>
	<div class="col-12 col-md-4">
		<div class="about-value-card p-4 text-center h-100">
			<i class="bi bi-stars text-warning mb-2" style="font-size: 2rem;"></i>
			<h3 class="fw-bold text-dark mb-0">240 Pts</h3>
			<p class="text-muted small mb-0">EcoBloom Rewards Balance</p>
		</div>
	</div>
</div> -->

<?php

$customer_orders = wc_get_orders(
    array(
        'customer_id' => get_current_user_id(),
        'limit'       => 2,
        'orderby'     => 'date',
        'order'       => 'DESC',
        'status'      => array_keys( wc_get_order_statuses() ),
    )
);
?>

<?php if ( $customer_orders ) : ?>
	<div class="about-story-box p-4 bg-white">

		<div class="d-flex justify-content-between align-items-center mb-4">
			<h5 class="fw-bold text-dark mb-0">Recent Orders</h5>

			<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>"
			class="text-magenta fw-bold text-decoration-none fs-7">
				View All Orders <i class="bi bi-arrow-right"></i>
			</a>
		</div>

		<div class="table-responsive">
			<table class="table align-middle border-light mb-0">

				<thead class="border-bottom">
					<tr class="text-uppercase text-muted fs-8">
						<th scope="col" class="py-2">Order ID</th>
						<th scope="col" class="py-2">Date</th>
						<th scope="col" class="py-2">Status</th>
						<th scope="col" class="py-2">Total (Incl. GST)</th>
						<th scope="col" class="py-2 text-end">Action</th>
					</tr>
				</thead>

				<tbody>

					<?php foreach ( $customer_orders as $index => $order ) : ?>

						<?php
						$is_last = ( $index === array_key_last( $customer_orders ) );

						$order_date   = $order->get_date_created();
						$order_status = $order->get_status();
						$status_label = wc_get_order_status_name( $order_status );
						?>

						<tr class="<?php echo $is_last ? '' : 'border-bottom'; ?>">

							<td class="py-3 fw-bold text-dark">
								#<?php echo esc_html( $order->get_order_number() ); ?>
							</td>

							<td class="py-3 text-muted">
								<?php
								echo esc_html(
									$order_date
										? wc_format_datetime( $order_date, 'F j, Y' )
										: ''
								);
								?>
							</td>

							<td class="py-3">
								<?php
								$status_classes = array(
									'completed'  => 'bg-success-subtle text-success',
									'processing' => 'bg-primary-subtle text-primary',
									'on-hold'    => 'bg-warning-subtle text-warning',
									'pending'    => 'bg-warning-subtle text-warning',
									'cancelled'  => 'bg-danger-subtle text-danger',
									'refunded'   => 'bg-secondary-subtle text-secondary',
									'failed'     => 'bg-danger-subtle text-danger',
								);

								$status_class = $status_classes[ $order_status ] ?? 'bg-secondary-subtle text-secondary';
								?>
								<span class="badge <?php echo esc_attr( $status_class ); ?> px-3 py-1 rounded-pill">
									<?php echo esc_html( $status_label ); ?>
								</span>
							</td>

							<td class="py-3 fw-bold text-dark">
								<?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
							</td>

							<td class="py-3 text-end">
								<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>"
								class="btn btn-sm btn-outline-dark rounded-pill px-3">
									View GST Invoice
								</a>
							</td>

						</tr>

					<?php endforeach; ?>

				</tbody>
			</table>
		</div>

	</div>
<?php endif; ?>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
