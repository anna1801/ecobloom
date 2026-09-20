<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
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

<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<?php
	$fields = $checkout->get_checkout_fields( 'billing' );

	// foreach ( $fields as $key => $field ) {
	// 	woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
	// }
	?>

	<!-- Billing Address Fields -->
	<div class="checkout-card mb-4">
		<p class="form-section-title"><i class="bi bi-person-fill"></i><?php esc_html_e( 'Contact Information', 'woocommerce' ); ?></p>
		<div class="row g-3">
			<div class="col-md-6">
				<?php
					woocommerce_form_field(
						'billing_first_name',
						array_merge(
							$fields['billing_first_name'],
							array(
								'label_class'   => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
								'input_class'   => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
								'placeholder'   => 'Priya',
								'class'         => array( 'form-group' ),
							)
						),
						$checkout->get_value( 'billing_first_name' )
					);
				?>
			</div>
			<div class="col-md-6">
				<?php
					woocommerce_form_field(
						'billing_last_name',
						array_merge(
							$fields['billing_last_name'],
							array(
								'label_class'   => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
								'input_class'   => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
								'placeholder'   => 'Sharma',
								'class'         => array( 'form-group' ),
							)
						),
						$checkout->get_value( 'billing_last_name' )
					);
				?>
			</div>
			<div class="col-md-6">
				<?php
					woocommerce_form_field(
						'billing_email',
						array_merge(
							$fields['billing_email'],
							array(
								'label_class'   => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
								'input_class'   => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
								'placeholder'   => 'priya@example.com',
								'class'         => array( 'form-group' ),
							)
						),
						$checkout->get_value( 'billing_email' )
					);
				?>
			</div>
			<div class="col-md-6">
				<?php
					woocommerce_form_field(
						'billing_phone',
						array_merge(
							$fields['billing_phone'],
							array(
								'label'         => 'Phone / WhatsApp',
								'required'    	=> true,
								'label_class'   => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
								'input_class'   => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
								'placeholder'   => '+91 98765 43210',
								'class'         => array( 'form-group' ),
							)
						),
						$checkout->get_value( 'billing_phone' )
					);
				?>
			</div>
		</div>
	</div>

	<div class="checkout-card mb-4">
		<p class="form-section-title"><i class="bi bi-geo-alt-fill"></i>Delivery Address</p>
		<div class="row g-3">
			<div class="col-12">
				<?php
					woocommerce_form_field(
						'billing_address_1',
						array_merge(
							$fields['billing_address_1'],
							array(
								'label'       => 'Address Line 1 ',
								'label_class' => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
								'input_class' => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
								'placeholder' => 'Flat / House No., Building Name',
							)
						),
						$checkout->get_value( 'billing_address_1' )
					);
				?>
			</div>

			<div class="col-12">
				<?php
					woocommerce_form_field(
						'billing_address_2',
						array_merge(
							$fields['billing_address_2'],
							array(
								'label'       => 'Address Line 2',
								'label_class' => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
								'input_class' => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
								'placeholder' => 'Street Name, Landmark, Area',
							)
						),
						$checkout->get_value( 'billing_address_2' )
					);
				?>
			</div>

			<div class="col-md-6">
				<?php
					woocommerce_form_field(
						'billing_city',
						array_merge(
							$fields['billing_city'],
							array(
								'label'       => 'City',
								'label_class' => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
								'input_class' => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
								'placeholder' => 'Bengaluru',
							)
						),
						$checkout->get_value( 'billing_city' )
					);
				?>
			</div>

			<div class="col-md-6">
				<?php
					woocommerce_form_field(
						'billing_state',
						array_merge(
							$fields['billing_state'],
							array(
								'label_class' => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
								'input_class' => array( 'form-select', 'checkout-input-modern', 'px-4', 'py-2' ),
								'placeholder' => 'Select State…',
							)
						),
						$checkout->get_value( 'billing_state' )
					);
				?>
			</div>

			<div class="col-md-6">
				<?php 
					woocommerce_form_field(
						'billing_country',
						array_merge(
							$fields['billing_country'],
							array(
								'label_class' => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
								'input_class' => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
								'placeholder' => 'Country',
							)
						),
						$checkout->get_value( 'billing_country' )
					);
				?>
			</div>

			<div class="col-md-6">
				<?php
					woocommerce_form_field(
						'billing_postcode',
						array_merge(
							$fields['billing_postcode'],
							array(
								'label_class' => array( 'form-label', 'fw-semibold', 'small', 'text-dark' ),
								'input_class' => array( 'form-control', 'checkout-input-modern', 'px-4', 'py-2' ),
								'placeholder' => '560095',
								'maxlength'   => 6,
							)
						),
						$checkout->get_value( 'billing_postcode' )
					);
				?>
			</div>
			
			<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>

			<div class="col-12 mt-2">

				<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
					
					<?php if ( ! $checkout->is_registration_required() ) : ?>
						<div class="form-check mb-2">
							<input class="form-check-input" type="checkbox" id="createaccount" 
									 <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1"
									onchange="document.getElementById('createaccountFields').style.display = this.checked ? 'block' : 'none';">
							<label class="form-check-label small fw-500 text-muted" for="createaccount"><?php esc_html_e( 'Create an account?', 'woocommerce' ); ?></label>
						</div>
					<?php endif; ?>

					<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

					<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>
						<div id="createaccountFields" style="display: none;" class="mt-2 mb-3">
							<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
								<?php
									if ( 'account_password' === $key ) {
										$field['label'] = 'Account Password';
										$field['label_class'] = array( 'form-label', 'fw-semibold', 'small', 'text-dark' );
										$field['input_class'] = array(
											'form-control',
											'checkout-input-modern',
											'px-4',
											'py-2',
										);
										$field['placeholder'] = 'Enter a secure password';
										$field['autocomplete'] = 'new-password';
									}

									woocommerce_form_field(
										$key,
										$field,
										$checkout->get_value( $key )
									);
								?>
							<?php endforeach; ?>
							<div class="clear"></div>
						</div>
					<?php endif; ?>

					<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
					
				<?php endif; ?>