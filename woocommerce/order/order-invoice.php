<?php
defined( 'ABSPATH' ) || exit;

if ( ! isset( $order ) || ! $order instanceof WC_Order ) {
	return;
}

$order_items = $order->get_items(
	apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' )
);

$show_purchase_note = $order->has_status(
	apply_filters(
		'woocommerce_purchase_note_order_statuses',
		array( 'completed', 'processing' )
	)
);

$downloads = $order->get_downloadable_items();

$actions = array_filter(
	wc_get_account_orders_actions( $order ),
	function ( $key ) {
		return 'view' !== $key;
	},
	ARRAY_FILTER_USE_KEY
);

$show_customer_details = $order->get_user_id() === get_current_user_id();

$store_name   = get_option( 'woocommerce_store_name' );
$store_gstin   = get_option( 'woocommerce_store_gstin' );

$store_address_1 = get_option( 'woocommerce_store_address' );
$store_address_2 = get_option( 'woocommerce_store_address_2' );
$store_city      = get_option( 'woocommerce_store_city' );
$store_postcode  = get_option( 'woocommerce_store_postcode' );
$store_country   = get_option( 'woocommerce_default_country' );
$parts = explode( ':', $store_country );

$country = $parts[0] ?? '';
$state   = $parts[1] ?? '';

$state_name = $state;

if ( $country && $state ) {
    $states = WC()->countries->get_states( $country );

    if ( isset( $states[ $state ] ) ) {
        $state_name = $states[ $state ];
    }
}

$store_address = array_filter(
	array(
		$store_address_1,
		$store_address_2,
		$store_city,
	)
);

$store_location = array_filter(
	array(
		$store_city,
		$state_name
	)
);

$billing_name = trim(
	$order->get_billing_first_name() . ' ' . $order->get_billing_last_name()
);

$billing_address_1 = $order->get_billing_address_1();
$billing_address_2 = $order->get_billing_address_2();
$billing_city      = $order->get_billing_city();
$billing_state     = $order->get_billing_state();
$billing_postcode  = $order->get_billing_postcode();
$billing_country   = $order->get_billing_country();
$billing_phone     = $order->get_billing_phone();

$billing_state_name = $billing_state;

if ( $billing_country && $billing_state ) {
	$states = WC()->countries->get_states( $billing_country );

	if ( isset( $states[ $billing_state ] ) ) {
		$billing_state_name = $states[ $billing_state ];
	}
}

$shipping_method = $order->get_shipping_method();

$shipping_zone = WC_Shipping_Zones::get_zone_matching_package(
    array(
        'destination' => array(
            'country'  => $order->get_shipping_country(),
            'state'    => $order->get_shipping_state(),
            'postcode' => $order->get_shipping_postcode(),
            'city'     => $order->get_shipping_city(),
        ),
    )
);

$shipping_zone_name = $shipping_zone->get_zone_name();

$shipping__postcode = $order->get_shipping_postcode();

$shipping_country = $order->get_shipping_country();
$shipping_state   = $order->get_shipping_state();

$shipping_states = WC()->countries->get_states( $shipping_country );

$shipping_state_name = isset( $shipping_states[ $shipping_state ] ) ? $shipping_states[ $shipping_state ] : $shipping_state;

$shipping_name = $order->get_formatted_shipping_full_name();
$shipping_address = array_filter(
    array(
		$order->get_shipping_address_1(),
        $order->get_shipping_address_1(),
        $order->get_shipping_address_2(),
        $order->get_shipping_city(),
        $shipping_state_name,
    ),
    'strlen'
);

$subtotal       = (float) $order->get_subtotal();
$discount_total = (float) $order->get_discount_total();
$shipping_total = (float) $order->get_shipping_total();
$total_tax      = (float) $order->get_total_tax();
$order_total    = (float) $order->get_total();

$cgst_total = 0;
$sgst_total = 0;
$igst_total = 0;

foreach ( $order->get_tax_totals() as $tax ) {

	$label = strtolower( wp_strip_all_tags( $tax->label ) );
	$value = (float) $tax->amount;

	if ( strpos( $label, 'cgst' ) !== false ) {
		$cgst_total += $value;
	} elseif ( strpos( $label, 'sgst' ) !== false ) {
		$sgst_total += $value;
	} elseif ( strpos( $label, 'igst' ) !== false ) {
		$igst_total += $value;
	}
}

$taxable_value = $subtotal - $discount_total;

$cgst_rate = 0;
$sgst_rate = 0;
$igst_rate = 0;

foreach ( $order->get_items( 'tax' ) as $tax_item ) {

	$tax_label = strtolower( $tax_item->get_name() );
	$tax_rate  = (float) $tax_item->get_rate_percent();

	if ( strpos( $tax_label, 'cgst' ) !== false ) {
		$cgst_rate = $tax_rate;
	} elseif ( strpos( $tax_label, 'sgst' ) !== false ) {
		$sgst_rate = $tax_rate;
	} elseif ( strpos( $tax_label, 'igst' ) !== false ) {
		$igst_rate = $tax_rate;
	}
}

?>

<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>


<div class="about-story-box p-5 bg-white border" id="gstInvoice">

	<div class="d-flex flex-wrap justify-content-between align-items-start border-bottom pb-4 mb-4">

		<div>
			<h3 class="fw-bold text-dark mb-1">
				<?php esc_html_e( 'TAX INVOICE (CUM BILL OF SUPPLY)', 'woocommerce' ); ?>
			</h3>

			<p class="text-muted small mb-0"><?php esc_html_e( 'Order ID: ', 'woocommerce' ); ?>
				<strong class="text-dark">#<?php echo esc_html( $order->get_order_number() ); ?></strong>
				• 
				<?php esc_html_e( 'Date:', 'woocommerce' ); ?> 
				<?php
				echo esc_html(
					wc_format_datetime(
						$order->get_date_created(),
						get_option( 'date_format' )
					)
				);
				?>
			</p>

			<p class="text-muted small mb-0">
				<?php if($store_gstin) : ?>
					EcoBloom GSTIN: 
					<strong class="text-dark">
						<?php echo esc_html( $store_gstin ); ?>
					</strong> 
				<?php endif; ?>

				<?php
					if( $store_location ) :
						echo '(' . esc_html( implode( ', ', $store_location ) ) .')';
					endif;
				?>
				
			</p>
		</div>

		<div class="mt-2 mt-md-0" id="button-div">
			<button type="button" class="btn btn-outline-dark rounded-pill px-4 btn-sm" onclick="printGSTInvoice();">
				<i class="bi bi-printer me-1"></i>
				<?php esc_html_e( 'Print / Download GST Invoice', 'woocommerce' ); ?>
			</button>
		</div>

	</div>


	<div class="row g-4 mb-4">
		<div class="col-md-6">
			<h6 class="fw-bold text-dark text-uppercase fs-8 text-muted mb-2">
				<?php esc_html_e( 'Billed To:', 'woocommerce' ); ?>
			</h6>

			<p class="mb-0 small text-dark">
				<strong>
					<?php echo esc_html( $billing_name ); ?>
					<br>
				</strong>

				<?php 
					$billing_address = array_filter(
						array(
							$billing_address_1,
							$billing_address_2,
						)
					);

					if ( $billing_address ) :
						echo esc_html( implode( ', ', $billing_address ) );
						echo '<br>';
					endif;
				?>

				<?php if ( $billing_city || $billing_state_name || $billing_postcode ) : ?>

					<?php

					$location_parts = array_filter(
						array(
							$billing_city,
							$billing_state_name,
						),
						'strlen'
					);

					echo esc_html(
						implode( ', ', $location_parts )
					);

					if ( $billing_postcode ) {
						echo ' - ' . esc_html( $billing_postcode );
					}

					?>

				<?php endif; ?>


				<?php if ( $billing_phone ) : ?>

					<br>

					<?php esc_html_e( 'Phone:', 'woocommerce' ); ?>

					<?php echo esc_html( $billing_phone ); ?>

				<?php endif; ?>
			</p>
		</div>

		<div class="col-md-6">
			<h6 class="fw-bold text-dark text-uppercase fs-8 text-muted mb-2">
				<?php esc_html_e( 'Shipped By:', 'woocommerce' ); ?>
			</h6>

			<p class="mb-0 small text-dark">
				<strong>
					<?php echo esc_html( $store_name ); ?>
				</strong>

				<br>

				<?php 
					if($store_address) :
						echo esc_html( implode( ', ', $store_address ) );
					endif;

					if($store_postcode) :
						echo ' - ' . $store_postcode;
					endif;
				?>

				<?php if ( ! empty( $shipping_address ) ) : ?>

					<br><br>

					<strong>
						<?php esc_html_e( 'Shipping Address:', 'woocommerce' ); ?>
					</strong>

					<?php 
						if($shipping_name) :
							echo '<br>';
							echo '<strong>'.$shipping_name.'</strong>';
						endif;
					?>

					<br>

					<?php echo wp_kses_post( implode( ', ', array_map( 'esc_html', $shipping_address ) ) ); ?>
					<?php 
						if($shipping__postcode) :
							echo ' - ' . $shipping__postcode;
						endif;
					?>

				<?php endif; ?>


				<?php if ( $shipping_method ) : ?>

					<br>

					<?php esc_html_e( 'Shipping Method:', 'woocommerce' ); ?>

					<?php echo esc_html( $shipping_zone_name ); ?>

				<?php endif; ?>
			</p>
		</div>
	</div>


	<!-- =========================================================
			ITEMS TABLE
	========================================================= -->

	<div class="table-responsive mb-4">

		<table class="table align-middle border">

			<thead class="bg-light">

				<tr class="fs-8 text-muted text-uppercase">

					<th class="py-3 px-3">
						<?php esc_html_e( 'Item Description', 'woocommerce' ); ?>
					</th>

					<th class="py-3">
						<?php esc_html_e( 'Qty', 'woocommerce' ); ?>
					</th>

					<th class="py-3">
						<?php esc_html_e( 'Unit Price (Excl. GST)', 'woocommerce' ); ?>
					</th>

					<th class="py-3">
						<?php esc_html_e( 'GST Rate', 'woocommerce' ); ?>
					</th>

					<th class="py-3 text-end px-3">
						<?php esc_html_e( 'Amount (Incl. GST)', 'woocommerce' ); ?>
					</th>

				</tr>

			</thead>


			<tbody>

				<?php

				do_action(
					'woocommerce_order_details_before_order_table_items',
					$order
				);

				foreach ( $order_items as $item_id => $item ) :

					$product = $item->get_product();

					if ( ! $product ) {
						continue;
					}


					$product_name = $item->get_name();

					$quantity = (float) $item->get_quantity();


					$line_subtotal = (float) $item->get_subtotal();

					$line_tax = (float) $item->get_total_tax();

					$unit_price_excl_tax = 0;

					if ( $quantity > 0 ) {
						$unit_price_excl_tax =
							$line_subtotal / $quantity;
					}

					$line_total_incl_tax =
						(float) $item->get_total() + $line_tax;

					$item_tax_rates = array();

					$item_taxes = $item->get_taxes();

					if ( ! empty( $item_taxes['total'] ) ) {

						foreach ( $item_taxes['total'] as $tax_rate_id => $tax_amount ) {

							if ( (float) $tax_amount <= 0 ) {
								continue;
							}

							$tax_rate = WC_Tax::get_rate_percent(
								$tax_rate_id
							);

							if ( $tax_rate ) {
								$item_tax_rates[] = $tax_rate;
							}
						}
					}
					?>

					<?php
						$gst_rate_text = '0%';

						if ( ! empty( $item_tax_rates ) ) {

							$item_tax_rates = array_values( $item_tax_rates );

							$main_rate = array_shift( $item_tax_rates );

							$main_rate = wc_format_decimal( $main_rate, 0 );

							$gst_rate_text = $main_rate . '%';

							if ( ! empty( $item_tax_rates ) ) {

								$component_rates = array_map(
									function ( $rate ) {
										return wc_format_decimal( $rate, 0 ) . '%';
									},
									$item_tax_rates
								);

								$gst_rate_text .= ' (' . implode( '+', $component_rates ) . ')';
							}
						}
					?>

					<tr>

						<td class="py-3 px-3 fw-bold text-dark">

							<?php echo esc_html( $product_name ); ?>

						</td>

						<td class="py-3">

							<?php
							echo esc_html(
								wc_format_decimal(
									$quantity,
									0
								)
							);
							?>

						</td>


						<td class="py-3">

							<?php
							echo wp_kses_post(
								wc_price(
									$unit_price_excl_tax,
									array(
										'currency' =>
											$order->get_currency(),
									)
								)
							);
							?>

						</td>


						<td class="py-3">

							<span class="badge bg-light text-dark border">

								<?php echo esc_html( $gst_rate_text ); ?>

							</span>

						</td>


						<td class="py-3 text-end px-3 fw-bold text-dark">

							<?php
							echo wp_kses_post(
								wc_price(
									$line_total_incl_tax,
									array(
										'currency' =>
											$order->get_currency(),
									)
								)
							);
							?>

						</td>

					</tr>

				<?php endforeach; ?>


				<?php

				do_action(
					'woocommerce_order_details_after_order_table_items',
					$order
				);

				?>

			</tbody>

		</table>

	</div>


	<!-- =========================================================
			GST BREAKDOWN
	========================================================= -->

	<div class="row justify-content-end">

		<div class="col-12 col-md-6">

			<div class="bg-pink-light p-4 rounded-3 border border-magenta">

				<h6 class="fw-bold text-dark mb-3">

					<?php esc_html_e(
						'GST & Tax Calculation Breakdown',
						'woocommerce'
					); ?>

				</h6>


				<!-- Taxable Value -->

				<div class="d-flex justify-content-between mb-2 small">

					<span class="text-muted">
						<?php esc_html_e(
							'Total Taxable Value (Excl. GST):',
							'woocommerce'
						); ?>
					</span>

					<span class="fw-bold text-dark">

						<?php
						echo wp_kses_post(
							wc_price(
								$taxable_value,
								array(
									'currency' =>
										$order->get_currency(),
								)
							)
						);
						?>

					</span>

				</div>


				<!-- CGST -->

				<?php if ( $cgst_total > 0 ) : ?>

					<div class="d-flex justify-content-between mb-2 small">

						<span class="text-muted">

							<?php
							printf(
								esc_html__( 'CGST @ %s%%:', 'woocommerce' ),
								esc_html( $cgst_rate )
							);
							?>

						</span>

						<span class="text-dark">

							<?php
							echo wp_kses_post(
								wc_price(
									$cgst_total,
									array(
										'currency' =>
											$order->get_currency(),
									)
								)
							);
							?>

						</span>

					</div>

				<?php endif; ?>


				<!-- SGST -->

				<?php if ( $sgst_total > 0 ) : ?>

					<div class="d-flex justify-content-between mb-2 small">

						<span class="text-muted">

							<?php
							printf(
								esc_html__( 'SGST @ %s%%:', 'woocommerce' ),
								esc_html( $sgst_rate )
							);
							?>

						</span>

						<span class="text-dark">

							<?php
							echo wp_kses_post(
								wc_price(
									$sgst_total,
									array(
										'currency' =>
											$order->get_currency(),
									)
								)
							);
							?>

						</span>

					</div>

				<?php endif; ?>


				<!-- IGST -->

				<?php if ( $igst_total > 0 ) : ?>

					<div class="d-flex justify-content-between mb-2 small">

						<span class="text-muted">

							<?php
							printf(
								esc_html__( 'IGST @ %s%%:', 'woocommerce' ),
								esc_html( $igst_rate )
							);
							?>

						</span>

						<span class="text-dark">

							<?php
							echo wp_kses_post(
								wc_price(
									$igst_total,
									array(
										'currency' =>
											$order->get_currency(),
									)
								)
							);
							?>

						</span>

					</div>

				<?php endif; ?>


				<!-- Discount -->

				<?php if ( $discount_total > 0 ) : ?>

					<div class="d-flex justify-content-between mb-2 small">

						<span class="text-muted">
							<?php esc_html_e(
								'Discount:',
								'woocommerce'
							); ?>
						</span>

						<span class="text-success fw-bold">

							-
							<?php
							echo wp_kses_post(
								wc_price(
									$discount_total,
									array(
										'currency' =>
											$order->get_currency(),
									)
								)
							);
							?>

						</span>

					</div>

				<?php endif; ?>


				<!-- Shipping -->

				<div class="d-flex justify-content-between mb-3 small">

					<span class="text-muted">

						<?php
						echo $shipping_zone_name
							? esc_html( $shipping_zone_name ) . ':'
							: esc_html__( 'Shipping:', 'woocommerce' );
						?>

					</span>


					<?php if ( $shipping_total > 0 ) : ?>

						<span class="text-dark">

							<?php
							echo wp_kses_post(
								wc_price(
									$shipping_total,
									array(
										'currency' =>
											$order->get_currency(),
									)
								)
							);
							?>

						</span>

					<?php else : ?>

						<span class="text-success fw-bold">
							<?php esc_html_e(
								'FREE',
								'woocommerce'
							); ?>
						</span>

					<?php endif; ?>

				</div>


				<hr>


				<!-- TOTAL -->

				<div class="d-flex justify-content-between align-items-center">

					<span class="fw-bold text-dark">

						<?php esc_html_e(
							'Total Invoice Value:',
							'woocommerce'
						); ?>

					</span>


					<span class="fs-5 fw-bold text-magenta">

						<?php
						echo wp_kses_post(
							wc_price(
								$order_total,
								array(
									'currency' =>
										$order->get_currency(),
								)
							)
						);
						?>

					</span>

				</div>

			</div>

		</div>

	</div>


	<?php if ( $order->get_customer_note() ) : ?>

		<div class="mt-4 p-3 bg-light border rounded">

			<strong class="small text-dark">
				<?php esc_html_e(
					'Order Note:',
					'woocommerce'
				); ?>
			</strong>

			<div class="small text-muted mt-1">

				<?php
				$customer_note =
					wc_wptexturize_order_note(
						$order->get_customer_note()
					);

				echo wp_kses(
					nl2br( $customer_note ),
					array(
						'br' => array(),
					)
				);
				?>

			</div>

		</div>

	<?php endif; ?>


	<?php if ( ! empty( $actions ) ) : ?>

		<div class="mt-4 pt-3 border-top d-flex flex-wrap gap-2">

			<?php

			$wp_button_class =
				wc_wp_theme_get_element_class_name( 'button' )
					? ' ' . wc_wp_theme_get_element_class_name( 'button' )
					: '';

			foreach ( $actions as $key => $action ) {

				if ( empty( $action['aria-label'] ) ) {

					$action_aria_label = sprintf(
						__( '%1$s order number %2$s', 'woocommerce' ),
						$action['name'],
						$order->get_order_number()
					);

				} else {

					$action_aria_label =
						$action['aria-label'];

				}

				?>

				<a href="<?php echo esc_url( $action['url'] ); ?>"
					class="woocommerce-button<?php echo esc_attr( $wp_button_class ); ?> button <?php echo esc_attr( sanitize_html_class( $key ) ); ?> order-actions-button"
					aria-label="<?php echo esc_attr( $action_aria_label ); ?>" >
					<?php echo esc_html( $action['name'] ); ?>
				</a>

				<?php
			}

			?>

		</div>

	<?php endif; ?>

	<div class="mt-4 pt-3 border-top text-center small text-muted">

		<?php esc_html_e(
			'This is an electronically generated GST tax invoice. No signature required.',
			'woocommerce'
		); ?>

	</div>


</div>

<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>


<!-- to print GST invoice -->
<script>
	function printGSTInvoice() {

		const invoice = document.getElementById('gstInvoice');

		if (!invoice) {
			alert('Invoice section not found.');
			return;
		}

		document.body.classList.add('printing-invoice');

		window.print();

		setTimeout(function () {
			document.body.classList.remove('printing-invoice');
		}, 1000);
	}
</script>

<style>
	@media print {

		body.printing-invoice * {
			visibility: hidden !important;
		}

		body.printing-invoice #gstInvoice,
		body.printing-invoice #gstInvoice * {
			visibility: visible !important;
		}

		body.printing-invoice #gstInvoice {
			position: absolute !important;
			display: block !important;
			left: 0 !important;
			top: 0 !important;
			width: 100% !important;
			max-width: none !important;
			margin: 0 !important;
			padding: 20px 0px 20px 20px !important;
		}

		body.printing-invoice .no-print,
		body.printing-invoice #button-div {
			display: none !important;
		}
	}
</style>


<?php
do_action( 'woocommerce_after_order_details', $order );
