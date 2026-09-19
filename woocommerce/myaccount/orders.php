<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<div class="about-story-box p-4 bg-white myaccount-orders">
	
	<h4 class="fw-bold text-dark mb-4">All Past &amp; Active Orders</h4>
	
	<?php if ( $has_orders ) : ?>

		<div class="table-responsive">
			<table class="table align-middle border-light mb-0 woocommerce-orders-table woocommerce-MyAccount-orders account-orders-table">
				<thead class="border-bottom">
					<tr class="text-uppercase text-muted fs-8">
						<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
							<?php $is_last = ( $column_id === array_key_last( wc_get_account_orders_columns() ) ); ?>
							<th scope="col" class="py-3 <?php echo $is_last ? 'text-end' : ''; ?> woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><?php echo esc_html( $column_name ); ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>

				<tbody>
					<?php
					$total = count( $customer_orders->orders );
					$i = 0;
					foreach ( $customer_orders->orders as $customer_order ) {
						$i++;
						$order      = wc_get_order( $customer_order ); 
						$item_count = $order->get_item_count() - $order->get_item_count_refunded();
						?>

						<tr class="<?php echo $i === $total ? '' : 'border-bottom'; ?> woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
							<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) :
								$is_order_number = 'order-number' === $column_id;

								if('order-number' === $column_id ) {
									$class = 'py-4 fw-bold';
								} elseif ('order-date' === $column_id ) {
									$class = 'py-4 text-muted';
								} elseif ('order-status' === $column_id ) {
									$class = 'py-4';									
								} elseif ('order-total' === $column_id ) {
									$class = 'py-4 fw-bold text-dark';								
								} elseif ('order-actions' === $column_id ) {
									$class = 'py-4 text-end';								
								} else {
									$class = 'py-4 fw-bold';
								}

								$order_status = $order->get_status();
								$status_classes = array(
									'completed'  => 'bg-success-subtle text-success',
									'processing' => 'bg-primary-subtle text-primary',
									'shipped'    => 'bg-primary-subtle text-primary',
									'on-hold'    => 'bg-warning-subtle text-warning',
									'pending'    => 'bg-warning-subtle text-warning',
									'cancelled'  => 'bg-danger-subtle text-danger',
									'refunded'   => 'bg-secondary-subtle text-secondary',
									'failed'     => 'bg-danger-subtle text-danger',
								);

								$status_class = $status_classes[ $order_status ] ?? 'bg-secondary-subtle text-secondary';
							?>
								<?php if ( $is_order_number ) : ?>
									<td class="<?php echo $class; ?> text-dark woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>" scope="row">
								<?php else : ?>
									<td class="<?php echo $class; ?> woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
								<?php endif; ?>

									<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
										<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

									<?php elseif ( $is_order_number ) : ?>
										<?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
									<?php elseif ( 'order-date' === $column_id ) : ?>
										<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

									<?php elseif ( 'order-status' === $column_id ) : ?>
										<span class="<?php echo $status_class; ?> badge px-3 py-1 rounded-pill"><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></span>

									<?php elseif ( 'order-total' === $column_id ) : ?>
										<?php echo $order->get_formatted_order_total(); ?>

									<?php elseif ( 'order-actions' === $column_id ) : ?>
										<?php
										$actions = wc_get_account_orders_actions( $order );

										if ( ! empty( $actions ) ) {
											foreach ( $actions as $key => $action ) { 
												if ( empty( $action['aria-label'] ) ) {
													$action_aria_label = sprintf( __( '%1$s order number %2$s', 'woocommerce' ), $action['name'], $order->get_order_number() );
												} else {
													$action_aria_label = $action['aria-label'];
												}
												echo '<a href="' . esc_url( $action['url'] ) . '" class="btn btn-sm btn-primary rounded-pill px-3 woocommerce-button' . esc_attr( $wp_button_class ) . ' ' . sanitize_html_class( $key ) . '" aria-label="' . esc_attr( $action_aria_label ) . '">' . esc_html( $action['name'] ) . '</a>';
												unset( $action_aria_label );
											}
										}
										?>
									<?php endif; ?>

								<?php if ( $is_order_number ) : ?>
									</td>
								<?php else : ?>
									</td>
								<?php endif; ?>
							<?php endforeach; ?>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>

		<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

		<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
			<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
				<?php if ( 1 !== $current_page ) : ?>
					<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button<?php echo esc_attr( $wp_button_class ); ?>" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
				<?php endif; ?>

				<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
					<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button<?php echo esc_attr( $wp_button_class ); ?>" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

	<?php else : ?>

		<?php wc_print_notice( esc_html__( 'No order has been made yet.', 'woocommerce' ) . ' <a class="woocommerce-Button wc-forward button' . esc_attr( $wp_button_class ) . '" href="' . esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) . '">' . esc_html__( 'Browse products', 'woocommerce' ) . '</a>', 'notice' ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment ?>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>

</div>