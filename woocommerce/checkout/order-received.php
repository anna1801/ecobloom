<?php
/**
 * "Order received" message.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/order-received.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.8.0
 *
 * @var WC_Order|false $order
 */

defined( 'ABSPATH' ) || exit;
?>

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

		<h1 class="fw-bold text-dark mb-2" style="font-size:1.8rem;">
			<?php 
				$message = apply_filters(
					'woocommerce_thankyou_order_received_text',
					esc_html( __( 'Order Confirmed!', 'woocommerce' ) ),
					$order
				);

				echo $message;
			?>
		</h1>
		<p class="text-muted mb-3">Thank you for choosing EcoBloom. Your order has been successfully placed and is being prepared with care.</p>
	</div>
</div>