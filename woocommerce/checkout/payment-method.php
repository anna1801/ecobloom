<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="pay-card wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?> <?php echo $gateway->chosen ? ' selected' : ''; ?>">

	<div class="d-flex align-items-center gap-3">

		<div class="flex-grow-1">
			<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
				<?php echo $gateway->get_title(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?> <?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */ ?>
			</label>
		</div>

		<?php
		$cod_fee_enabled = get_option( 'cod_fee_enabled', 'no' );
		$cod_fee_amount  = (float) get_option( 'cod_fee_amount', 0 );
		$cod_fee_type    = get_option( 'cod_fee_type', 'fixed' );
		$cod_fee_label   = get_option( 'cod_fee_label', 'Cash on Delivery Fee' );

		if ( 'yes' === $cod_fee_enabled && 'cod' === $gateway->id && $cod_fee_amount > 0 ) :

			if ( 'percentage' === $cod_fee_type ) {
				$fee_text = $cod_fee_amount . '%';
			} else {
				$fee_text = eco_price( $cod_fee_amount );
			}

			?>

			<span class="badge bg-light text-dark border">
				<?php echo wp_kses_post( $fee_text ); ?> fee
			</span>

		<?php endif; ?>

		<div class="form-check mb-0">
			<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="form-check-input input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
		</div>

	</div>


	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>" <?php if ( ! $gateway->chosen ) : /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>style="display:none;"<?php endif; /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>

</div>