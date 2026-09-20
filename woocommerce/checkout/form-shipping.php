<?php
/**
 * Checkout shipping information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;
?>

			<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>
				<div class="form-check" id="ship-to-different-address">
					<input class="form-check-input" type="checkbox" id="ship-to-different-address-checkbox" 
					<?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1"
					onchange="document.getElementById('shippingAddressFields').style.display = this.checked ? 'block' : 'none';">
					<label class="form-check-label small fw-500 text-muted"
						for="ship-to-different-address-checkbox">Ship to a different address?</label>
				</div>
			<?php endif; ?>

		</div> <!-- form-billing.php <div class="col-12 mt-2"> -->
	</div> <!-- form-billing.php <div class="row g-3"> -->

	<!-- Shipping Address Fields -->
	<?php if ( true === WC()->cart->needs_shipping_address() ) : ?>
		<div id="shippingAddressFields" style="display: none;" class="mt-4 pt-3 border-top">
			<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

			<p class="fw-bold text-dark mb-3">Shipping Address</p>

			<?php
				$fields = $checkout->get_checkout_fields( 'shipping' );
				// foreach ( $fields as $key => $field ) {
				// 	woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
				// }
			?>

			<div class="row g-3">

				<div class="col-md-6">
					<?php
						woocommerce_form_field(
							'shipping_first_name',
							array_merge(
								$fields['shipping_first_name'],
								array(
									'label_class'   => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
									'input_class'   => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
									'placeholder'   => 'Priya',
									'class'         => array( 'form-group' ),
								)
							),
							$checkout->get_value( 'shipping_first_name' )
						);
					?>
				</div>
			
				<div class="col-md-6">
					<?php
						woocommerce_form_field(
							'shipping_last_name',
							array_merge(
								$fields['shipping_last_name'],
								array(
									'label_class'   => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
									'input_class'   => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
									'placeholder'   => 'Sharma',
									'class'         => array( 'form-group' ),
								)
							),
							$checkout->get_value( 'shipping_last_name' )
						);
					?>
				</div>

				<div class="col-md-6">
					<?php
						woocommerce_form_field(
							'shipping_email',
							array(
								'type'        => 'email',
								'label'       => 'Email Address',
								//'required'    => true,
								'label_class' => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
								'input_class' => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
								'placeholder' => 'example@email.com',
								'class'       => array( 'form-group' ),
							),
							$checkout->get_value( 'shipping_email' )
						);
					?>
				</div>

				<div class="col-md-6">
					<?php
						woocommerce_form_field(
							'shipping_phone',
							array_merge(
								$fields['shipping_phone'],
								array(
									'label'         => 'Phone / WhatsApp',
									'required'    	=> true,
									'label_class'   => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
									'input_class'   => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
									'placeholder'   => '+91 98765 43210',
									'class'         => array( 'form-group' ),
								)
							),
							$checkout->get_value( 'shipping_phone' )
						);
					?>
				</div>

				<div class="col-12">
					<?php
						woocommerce_form_field(
							'shipping_address_1',
							array_merge(
								$fields['shipping_address_1'],
								array(
									'label'       => 'Address Line 1 ',
									'label_class' => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
									'input_class' => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
									'placeholder' => 'Flat / House No., Building Name',	
								)
							),
							$checkout->get_value( 'shipping_address_1' )
						);
					?>
				</div>

				<div class="col-12">
					<?php
						woocommerce_form_field(
							'shipping_address_2',
							array_merge(
								$fields['shipping_address_2'],
								array(
									'label'       => 'Address Line 2',
									'label_class' => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
									'input_class' => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
									'placeholder' => 'Street Name, Landmark, Area',
								)
							),
							$checkout->get_value( 'shipping_address_2' )
						);
					?>
				</div>

				<div class="col-md-6">
					<?php
						woocommerce_form_field(
							'shipping_city',
							array_merge(
								$fields['shipping_city'],
								array(
									'label'       => 'City',
									'label_class' => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
									'input_class' => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
									'placeholder' => 'City',
								)
							),
							$checkout->get_value( 'shipping_city' )
						);
					?>
				</div>

				<div class="col-md-6">
					<?php
						woocommerce_form_field(
							'shipping_state',
							array_merge(
								$fields['shipping_state'],
								array(
									'label_class' => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
									'input_class' => array( 'form-select', 'checkout-input-modern', 'px-4', 'py-2' ),
									'placeholder' => 'Select State…',
								)
							),
							$checkout->get_value( 'shipping_state' )
						);
					?>
				</div>

				<div class="col-md-6">
					<?php 
						woocommerce_form_field(
							'shipping_country',
							array_merge(
								$fields['shipping_country'],
								array(
									'label_class' => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
									'input_class' => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
									'placeholder' => 'Country',
								)
							),
							$checkout->get_value( 'shipping_country' )
						);
					?>
				</div>

				<div class="col-md-6">
					<?php
						woocommerce_form_field(
							'shipping_postcode',
							array_merge(
								$fields['shipping_postcode'],
								array(
									'label_class' => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
									'input_class' => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
									'placeholder' => 'PIN Code',
									'maxlength'   => 6,
								)
							),
							$checkout->get_value( 'shipping_postcode' )
						);
					?>
				</div>

			</div> 

			<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>
		</div>
	<?php endif; ?>

</div> <!-- form-billing.php <div class="checkout-card mb-4"> -->
	
<!-- Section 3: Order Notes -->
<div class="checkout-card mb-4">

	<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

	<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>

		<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
			<?php if ( 'order_comments' === $key ) : ?>

				<p class="form-section-title"><i class="bi bi-chat-left-text"></i><?php esc_html_e( 'Order Notes', 'woocommerce' ); ?> <span
                                    class="text-muted fw-normal small">(Optional)</span></p>

				<?php
				$field['label'] = '';
				$field['placeholder'] = 'Any special instructions about your order or delivery…';
				$field['input_class'] = array( 'form-control' );

				$field['custom_attributes'] = array(
					'rows'  => '3',
					'style' => 'border-radius:14px; padding:.75rem 1rem;',
				);

				woocommerce_form_field(
					$key,
					$field,
					$checkout->get_value( $key )
				);
				?>

			<?php else : ?>

				<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

			<?php endif; ?>

		<?php endforeach; ?>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
</div>
