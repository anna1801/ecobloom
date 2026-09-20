<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.9.0
 *
 * @var bool $show_downloads Controls whether the downloads table should be rendered.
 */

 // phpcs:disable WooCommerce.Commenting.CommentHooks.MissingHookComment

defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id ); 

if ( ! $order ) {
	return;
}

$order_items        = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$downloads          = $order->get_downloadable_items();
$actions            = array_filter(
	wc_get_account_orders_actions( $order ),
	function ( $key ) {
		return 'view' !== $key;
	},
	ARRAY_FILTER_USE_KEY
);

$show_customer_details = $order->get_user_id() === get_current_user_id();
?>

<?php 
	$allowed_invoice_statuses = [
		'processing',
		'completed',
		'shipped'
	];

	if ( in_array( $order->get_status(), $allowed_invoice_statuses, true ) ) :
		wc_get_template(
			'order/order-invoice.php',
			array(
				'order' => $order,
			)
		);
?>

<?php else : ?>
	<div class="row g-4">
		<div class="col-12 col-lg-7">
			<section class="about-value-card p-4 bg-pink-light border border-magenta woocommerce-order-details">
				<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>

				<h4 class="fw-bold text-dark mb-4"><?php esc_html_e( 'Order details', 'woocommerce' ); ?></h4>

				<div class="woocommerce-table woocommerce-table--order-details shop_table order_details">

					<?php
						do_action( 'woocommerce_order_details_before_order_table_items', $order );

						foreach ( $order_items as $item_id => $item ) {
							$product = $item->get_product();

							wc_get_template(
								'order/order-details-item.php',
								array(
									'order'              => $order,
									'item_id'            => $item_id,
									'item'               => $item,
									'show_purchase_note' => $show_purchase_note,
									'purchase_note'      => $product ? $product->get_purchase_note() : '',
									'product'            => $product,
								)
							);
						}

						do_action( 'woocommerce_order_details_after_order_table_items', $order );
					?>

					<?php foreach ( $order->get_order_item_totals() as $key => $total ) { ?> 
						<div class="d-flex justify-content-between mb-2 type-<?php echo esc_html( $total['type'] ); ?>">
							<?php 
								if($total['type'] == 'total' ) {
									$label = 'fs-5 fw-bold text-dark';
									$value = 'fs-4 fw-bold text-magenta';
								} else {
									$label = 'text-muted';
									$value = 'fw-500 text-dark';
								}
							?>
							<span class="<?php echo $label; ?> label "><?php echo esc_html( $total['label'] ); ?></span>
							<span class="<?php echo $value; ?> value type-<?php echo esc_html( $total['type'] ); ?>-value">
								<?php echo wp_kses_post( $total['value'] ); ?>
							</span>
						</div>
					<?php } ?>

					<?php if ( $order->get_customer_note() ) : ?>
						<div class="d-flex justify-content-between mb-2">
							<span class="text-muted"><?php esc_html_e( 'Note:', 'woocommerce' ); ?></span>
							<span class="fw-500 text-dark">
								<?php
									$customer_note = wc_wptexturize_order_note( $order->get_customer_note() );
									echo wp_kses( nl2br( $customer_note ), array( 'br' => array() ) );
								?>
							</span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $actions ) ) : ?>
						<div class="d-flex justify-content-between mb-2">
							<span class="text-muted"><?php esc_html_e( 'Actions', 'woocommerce' ); ?>:</span>
							<span class="fw-500 text-dark">
								<?php
									$wp_button_class = wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '';
									foreach ( $actions as $key => $action ) { 
										if ( empty( $action['aria-label'] ) ) {
											$action_aria_label = sprintf( __( '%1$s order number %2$s', 'woocommerce' ), $action['name'], $order->get_order_number() );
										} else {
											$action_aria_label = $action['aria-label'];
										}
											echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button' . esc_attr( $wp_button_class ) . ' button ' . sanitize_html_class( $key ) . ' order-actions-button " aria-label="' . esc_attr( $action_aria_label ) . '">' . esc_html( $action['name'] ) . '</a>';
											unset( $action_aria_label );
									}
								?>
							</span>
						</div>
					<?php endif; ?>
					
				</div>

				<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
			</section>

			<?php do_action( 'woocommerce_after_order_details', $order ); ?>
		</div>
		<div class="col-12 col-lg-5">
			<?php
				if ( $show_customer_details ) {
					wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
				}
			?>
		</div>
	</div>
<?php endif; ?>