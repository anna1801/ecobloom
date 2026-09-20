<?php
/**
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
	return;
}
?>
<div class="checkout-order-item mb-2 <?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'woocommerce-table__line-item order_item', $item, $order ) ); ?>">
	
	<?php if ( $product ) : ?>
		<div class="checkout-order-image">
			<?php
			echo wp_kses_post(
				$product->get_image(
					'woocommerce_thumbnail',
					array(
						'class' => 'checkout-order-img',
						'alt'   => $item->get_name(),
					)
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="flex-grow-1 woocommerce-table__product-name product-name">
		<?php
		$is_visible        = $product && $product->is_visible();
		$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );

		echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<div class="fw-bold text-dark small">%s</div>', $item->get_name() ) : $item->get_name(), $item, $is_visible ) );

		$qty          = $item->get_quantity();
		$refunded_qty = $order->get_qty_refunded_for_item( $item_id );

		if ( $refunded_qty ) {
			$qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
		} else {
			$qty_display = esc_html( $qty );
		}

		echo apply_filters( 'woocommerce_order_item_quantity_html', ' <div class="text-muted" style="font-size:.78rem;"> Qty: ' . sprintf( '&times;&nbsp;%s', $qty_display ) . '</div>', $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );

		wc_display_item_meta( $item );

		do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );
		?>
	</div>
	<span class="fw-bold text-dark woocommerce-table__product-total product-total"><?php echo $order->get_formatted_line_subtotal( $item ); ?></span>

</div>

<?php if ( $show_purchase_note && $purchase_note ) : ?>

<div class="woocommerce-table__product-purchase-note product-purchase-note"> 
	<div class="text-muted"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></span>
</div>

<?php endif; ?>
