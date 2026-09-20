<?php
/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.7.0
 */

defined( 'ABSPATH' ) || exit;

$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
?>
<section class="woocommerce-customer-details">

	<?php if ( $show_shipping ) : ?>

	<section class=" woocommerce-columns--addresses  addresses">
		<div class="order-details-addr bg-white woocommerce-column--billing-address ">

	<?php endif; ?>

		<span class="badge bg-dark text-white px-3 py-1 rounded-pill fs-8 mb-3"><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></span>
		<p class="text-dark small mb-3">

			<strong><?php echo esc_html( $order->get_formatted_billing_full_name() ); ?></strong>
			<br>
			<?php 
				echo wp_kses_post(
					WC()->countries->get_formatted_address(
						array(
							'address_1' => $order->get_billing_address_1(),
							'address_2' => $order->get_billing_address_2(),
							'city'      => $order->get_billing_city(),
							'state'     => $order->get_billing_state(),
							'postcode'  => $order->get_billing_postcode(),
							'country'   => $order->get_billing_country(),
						)
					)
				);
			?>

			<?php if ( $order->get_billing_phone() ) : ?>
				<span class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_billing_phone() ); ?></span>
			<?php endif; ?>

			<?php if ( $order->get_billing_email() ) : ?>
				<span class="woocommerce-customer-details--email"><?php echo esc_html( $order->get_billing_email() ); ?></span>
			<?php endif; ?>

			<?php do_action( 'woocommerce_order_details_after_customer_address', 'billing', $order ); ?>
		</p>

	<?php if ( $show_shipping ) : ?>

		</div>

		<div class="order-details-addr bg-white woocommerce-column--shipping-address">
			<span class="badge bg-light text-dark border px-3 py-1 rounded-pill fs-8 mb-3"><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></span>
			<p class="text-dark small mb-3">
				<strong><?php echo esc_html( $order->get_formatted_shipping_full_name() ); ?></strong>
				<br>
				<?php 
					echo wp_kses_post(
						WC()->countries->get_formatted_address(
							array(
								'address_1' => $order->get_shipping_address_1(),
								'address_2' => $order->get_shipping_address_2(),
								'city'      => $order->get_shipping_city(),
								'state'     => $order->get_shipping_state(),
								'postcode'  => $order->get_shipping_postcode(),
								'country'   => $order->get_shipping_country(),
							)
						)
					);
				?>

				<?php if ( $order->get_shipping_phone() ) : ?>
					<span class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_shipping_phone() ); ?></span>
				<?php endif; ?>

				<?php do_action( 'woocommerce_order_details_after_customer_address', 'shipping', $order ); ?>
			</p>
		</div>

	</section>

	<?php endif; ?>

	<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>

</section>
