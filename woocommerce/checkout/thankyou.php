<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="woocommerce-order">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<div class="error-page-wrap">
				<div class="error-card">

					<div class="error-icon-ring">
						<i class="bi bi-x-circle-fill"></i>
					</div>

					<h1 class="fw-bold text-dark mb-2" style="font-size:1.8rem;">Payment Failed</h1>
					<p class="text-muted mb-4">We couldn't process your payment. Your order has <strong>not</strong> been placed and you have <strong>not</strong> been charged.</p>

					<div class="about-story-box p-3 bg-white text-start mb-4">
						<div class="fw-bold text-dark small mb-2">Possible reasons:</div>
						<ul class="reason-list list-unstyled mb-0">
							<li><i class="bi bi-exclamation-circle-fill"></i> Insufficient balance in your account</li>
							<li><i class="bi bi-exclamation-circle-fill"></i> Card declined by your bank</li>
							<li><i class="bi bi-exclamation-circle-fill"></i> Incorrect card details entered</li>
							<li><i class="bi bi-exclamation-circle-fill"></i> Session timed out during payment</li>
							<li><i class="bi bi-exclamation-circle-fill"></i> UPI transaction limit reached</li>
						</ul>
					</div>

					<div class="small text-muted mb-4">
						<span class="badge bg-light text-muted border px-3 py-2">Ref: #<?php echo $order->get_order_number(); ?></span>
						&nbsp;·&nbsp; <span><?php echo wc_format_datetime( $order->get_date_created(), 'j M Y h:i a' );  ?></span>
					</div>
					
					<div class="action-btns">

						<?php
							$actions = array_filter(
								wc_get_account_orders_actions( $order ),
								function ( $key ) {
									return 'view' !== $key;
								},
								ARRAY_FILTER_USE_KEY
							);
							$wp_button_class = wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '';
							foreach ( $actions as $key => $action ) {
								if ( empty( $action['aria-label'] ) ) {
									$action_aria_label = sprintf( __( '%1$s order number %2$s', 'woocommerce' ), $action['name'], $order->get_order_number() );
								} else {
									$action_aria_label = $action['aria-label'];
								}

								if ( 'pay' === $key ) :
									$icon = '<i class="bi bi-arrow-counterclockwise me-2"></i>';
									$class = 'pay btn btn-primary rounded-pill px-5 py-2 fw-bold';
									$label = 'Try Again';
								elseif ( 'cancel' === $key ) : 
									$icon = '<i class="bi bi-stop-circle me-2"></i>';
									$class = 'btn btn-outline-dark rounded-pill px-4 py-2 fw-bold';
									$label = $action['name'];
								endif;

								echo '<a href="' . esc_url( $action['url'] ) . '" class="'.$class.' woocommerce-button' . esc_attr( $wp_button_class ) . '  ' . sanitize_html_class( $key ) . ' order-actions-button " aria-label="' . esc_attr( $action_aria_label ) . '">' . $icon . $label . '</a>';
								unset( $action_aria_label );
							}
						?>

					</div>

					<div class="mt-4 p-3 rounded-3" style="background:#fdf2f8;">
						<div class="fw-bold text-dark small mb-1"><i class="bi bi-headset text-magenta me-1"></i>Still having trouble?</div>
						<?php 
							$order_received_failed_footer = get_field('order_received_failed_footer', 'option');
							if($order_received_failed_footer) :
								echo '<div class="text-muted small order_received_footer">'.$order_received_failed_footer.'</div>';
							endif;

							$show_contacts_in = get_field('show_contacts_in', 'option');
							if ( is_array($show_contacts_in) && in_array('failed', $show_contacts_in, true) ) :
								order_receive_footer();
							endif;
						?>
					</div>
				</div>
			</div>

		<?php elseif ( $order->has_status( 'cancelled' ) ) : ?>

			<div class="error-page-wrap">
				<div class="error-card">

					<div class="error-icon-ring">
						<i class="bi bi-stop-circle"></i>
					</div>

					<h1 class="fw-bold text-dark mb-2" style="font-size:1.8rem;">Order Cancelled!</h1>
					<p class="text-muted mb-4">Your order has been cancelled successfully. No further payment will be taken for this order.</p>

				</div>
			</div>

		<?php else : ?>

			<div class="success-page-wrap">
				<div class="success-card">

					<div class="mb-2">
						<span class="confetti-emoji" style="animation-delay:0s;">🌸</span>
						<span class="confetti-emoji" style="animation-delay:.2s;">🎉</span>
						<span class="confetti-emoji" style="animation-delay:.4s;">🌱</span>
					</div>

					<div class="success-icon-ring">
						<i class="bi bi-check-circle-fill"></i>
					</div>

					<h1 class="fw-bold text-dark mb-2" style="font-size:1.8rem;">Order Confirmed!</h1>
					<p class="text-muted mb-3">Thank you for choosing EcoBloom. Your order has been successfully placed and is being prepared with care.</p>

					<div class="order-ref">Order #<span><?php echo $order->get_order_number(); ?></span></div>

					<div class="about-story-box p-3 bg-white text-start mb-3">
						<div class="detail-row">
							<span class="text-muted"> <?php esc_html_e( 'Order Date', 'woocommerce' ); ?></span>
							<span class="fw-bold text-dark"><?php echo wc_format_datetime( $order->get_date_created() );  ?></span>
						</div>
						<?php if ( $order->get_payment_method_title() ) : ?>
							<div class="detail-row">
								<span class="text-muted"> <?php esc_html_e( 'Payment Method', 'woocommerce' ); ?></span>
								<span class="fw-bold text-dark"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></span>
							</div>
						<?php endif; ?>
						<div class="detail-row">
							<span class="text-muted"> <?php esc_html_e( 'Items Ordered', 'woocommerce' ); ?></span>
							<span class="fw-bold text-dark">
								<?php 
								foreach ( $order->get_items() as $item ) {
									$product_name = $item->get_name();
									$quantity     = $item->get_quantity();

									echo esc_html( $product_name ) . ' × ' . esc_html( $quantity );
								}
								?>
							</span>
						</div>
						<div class="detail-row">
							<span class="text-muted"> <?php esc_html_e( 'Total Paid (incl. GST)', 'woocommerce' ); ?></span>
							<span class="fw-bold text-magenta fs-5"><?php echo $order->get_formatted_order_total(); ?></span>
						</div>
						<div class="detail-row">
							<span class="text-muted"> <?php esc_html_e( 'Delivery To', 'woocommerce' ); ?></span>
							<span class="fw-bold text-dark">
								<?php 
									$country_code = $order->get_shipping_country();
									$state_code   = $order->get_shipping_state();

									$country = WC()->countries->countries[ $country_code ] ?? '';
									$state   = WC()->countries->get_states( $country_code )[ $state_code ] ?? $state_code;

									$address = array_filter([
										//$order->get_shipping_address_1(),
										//$order->get_shipping_address_2(),
										$order->get_shipping_city(),
										$state,
										$order->get_shipping_postcode(),
										//$country,
									]);

									echo implode(', ', array_map('esc_html', $address));
								?>
							</span>
						</div>
						<div class="detail-row">
							<span class="text-muted"> <?php esc_html_e( 'Estimated Delivery', 'woocommerce' ); ?></span>
							<span class="fw-bold text-success"> <?php esc_html_e( '3–5 Business Days', 'woocommerce' ); ?></span>
						</div>
					</div>

					<?php
						$order_status = $order ? $order->get_status() : '';

						$status_steps = array(
							'pending'    => 1,
							'on-hold'    => 1,
							'processing' => 2,
							'shipped'    => 3,
							'completed'  => 4,
						);

						$current_step = isset( $status_steps[ $order_status ] )
							? $status_steps[ $order_status ]
							: 1;
					?>

					<div class="about-story-box p-3 bg-white text-start">
						<div class="fw-bold text-dark mb-2 small text-uppercase tracking-wide" style="letter-spacing:.05em;">What happens next?</div>
						<div class="step-timeline">

							<?php
							$steps = array(
								1 => array(
									'title'       => 'Order Placed',
									'description' => "Confirmation sent to your email",
								),
								2 => array(
									'title'       => 'Processing & Packing',
									'description' => "We'll discreetly pack your order within 24 hours",
								),
								3 => array(
									'title'       => 'Shipped',
									'description' => "You'll receive a tracking link via SMS & email",
								),
								4 => array(
									'title'       => 'Delivered 🌸',
									'description' => "3–5 business days in a plain, unmarked kraft box",
								),
							);

							foreach ( $steps as $step_number => $step ) :

								if ( $step_number < $current_step ) {
									$class = 'done';
								} elseif ( $step_number === $current_step ) {
									$class = 'next';
								} else {
									$class = 'later';
								}
							?>

								<div class="timeline-item">
									<div class="timeline-dot <?php echo esc_attr( $class ); ?>">
										<?php if ( 'done' === $class ) : ?>
											<i class="bi bi-check-lg"></i>
										<?php else : ?>
											<?php echo esc_html( $step_number ); ?>
										<?php endif; ?>
									</div>
									<div>
										<div class="fw-bold text-dark small"><?php echo esc_html( $step['title'] ); ?></div>
										<div class="text-muted" style="font-size:.78rem;"><?php echo esc_html( $step['description'] ); ?></div>
									</div>
								</div>

							<?php endforeach; ?>
							
						</div>
					</div>

					<div class="action-btns">
						<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="btn btn-primary rounded-pill px-4 py-2 fw-bold">
							<i class="bi bi-receipt me-2"></i>View Order (GST Invoice)
						</a>
						<a href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold">
							<i class="bi bi-bag me-2"></i>Continue Shopping
						</a>
					</div>

					<?php 
						$order_received_success_footer = get_field('order_received_success_footer', 'option');
						if($order_received_success_footer) :
							echo '<div class="order_received_footer text-muted small mt-3 mb-0">'.$order_received_success_footer.'</div>';
						endif;

						$show_contacts_in = get_field('show_contacts_in', 'option');
						if ( is_array($show_contacts_in) && in_array('success', $show_contacts_in, true) ) :
							order_receive_footer();
						endif;
					?>
				</div>
			</div>

		<?php endif; ?>

	<?php else : ?>

		<?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>

	<?php endif; ?>

</div>
