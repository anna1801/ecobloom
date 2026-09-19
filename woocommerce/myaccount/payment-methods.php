<?php
/**
 * Payment methods
 *
 * Shows customer payment methods on the account page.
 *
 * @package WooCommerce\Templates
 * @version 8.9.0
 */

defined( 'ABSPATH' ) || exit;

$saved_methods = wc_get_customer_saved_methods_list( get_current_user_id() );
$has_methods   = (bool) $saved_methods;
$types         = wc_get_account_payment_methods_types();

do_action( 'woocommerce_before_account_payment_methods', $has_methods );
?>

<div class="about-story-box p-4 bg-white">

	<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
		<h4 class="fw-bold text-dark mb-0"> <?php esc_html_e( 'Your Saved Payment Options', 'woocommerce' ); ?></h4>
		<?php if ( WC()->payment_gateways->get_available_payment_gateways() ) : ?>
			<button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addPaymentModal" id="openAddPaymentModal">
				<i class="bi bi-plus-circle me-1"></i> <?php esc_html_e( 'Add New Payment Method', 'woocommerce' ); ?>
			</button>
		<?php endif; ?>
	</div>

	<?php if ( $has_methods ) : ?>

		<div class="row g-4">

			<?php foreach ( $saved_methods as $type => $methods ) : ?>
				<?php foreach ( $methods as $method ) : ?>

					<?php
					$is_default = ! empty( $method['is_default'] );

					$brand = ! empty( $method['method']['brand'] )
						? wc_get_credit_card_type_label( $method['method']['brand'] )
						: '';

					$last4 = ! empty( $method['method']['last4'] )
						? $method['method']['last4']
						: '';

					$expires = ! empty( $method['expires'] )
						? $method['expires']
						: '';

					$is_card = ! empty( $last4 ) || ! empty( $brand );

					$method_title = $is_card
						? $brand . ' ' . __( 'Card', 'woocommerce' )
						: ucfirst( $type );
					?>

					<div class="col-12 col-md-6">

						<div class="about-value-card p-4 h-100 border <?php echo $is_default ? 'border-magenta bg-pink-light' : ''; ?>">

							<div class="d-flex justify-content-between align-items-start mb-3">

								<span class="badge <?php echo $is_default ? 'bg-dark text-white' : 'bg-light text-dark border'; ?> px-3 py-1 rounded-pill fs-8">

									<?php
									if ( $is_default ) {
										esc_html_e( 'Primary Payment Method', 'woocommerce' );
									} else {
										echo esc_html( $method_title );
									}
									?>

								</span>

								<?php if ( $is_card ) : ?>

									<i class="bi bi-credit-card-2-front <?php echo $is_default ? 'text-magenta' : 'text-dark'; ?> fs-4"></i>

								<?php else : ?>

									<i class="bi bi-wallet2 <?php echo $is_default ? 'text-magenta' : 'text-dark'; ?> fs-4"></i>

								<?php endif; ?>

							</div>

							<?php if ( $is_card ) : ?>

								<h5 class="fw-bold text-dark mb-1">
									<?php echo esc_html( $brand ); ?>
									<?php esc_html_e( 'Card', 'woocommerce' ); ?>
								</h5>

								<p class="text-muted mb-3">

									•••• •••• •••• <?php echo esc_html( $last4 ); ?>

									<?php if ( $expires ) : ?>
										(<?php esc_html_e( 'Exp:', 'woocommerce' ); ?>
										<?php echo esc_html( $expires ); ?>)
									<?php endif; ?>

								</p>

							<?php else : ?>

								<h5 class="fw-bold text-dark mb-1">
									<?php echo esc_html( $method_title ); ?>
								</h5>

								<p class="text-dark fw-semibold mb-3">

									<?php
									if ( has_action( 'woocommerce_account_payment_methods_column_method' ) ) {
										do_action(
											'woocommerce_account_payment_methods_column_method',
											$method
										);
									}
									?>

								</p>

							<?php endif; ?>

							<div class="d-flex justify-content-between align-items-center pt-3 border-top">

								<?php if ( $is_default ) : ?>

									<span class="text-success small fw-bold">
										<i class="bi bi-check-circle-fill me-1"></i>
										<?php esc_html_e( 'Primary', 'woocommerce' ); ?>
									</span>

								<?php else : ?>

									<?php
									if ( ! empty( $method['actions']['default'] ) ) :
										$default_action = $method['actions']['default'];
										?>

										<a
											href="<?php echo esc_url( $default_action['url'] ); ?>"
											class="btn btn-sm btn-outline-dark rounded-pill px-3"
										>
											<?php echo esc_html( $default_action['name'] ); ?>
										</a>

									<?php endif; ?>

								<?php endif; ?>


								<?php
								if ( ! empty( $method['actions'] ) ) :

									foreach ( $method['actions'] as $key => $action ) :

										if ( 'default' === $key ) {
											continue;
										}
										?>

										<a href="<?php echo esc_url( $action['url'] ); ?>"
											class="btn btn-sm btn-link text-danger p-0 text-decoration-none <?php echo sanitize_html_class( $key ); ?>">
											<?php echo esc_html( $action['name'] ); ?>
										</a>

									<?php endforeach; ?>

								<?php endif; ?>

							</div>

						</div>

					</div>

				<?php endforeach; ?>
			<?php endforeach; ?>

		</div>

	<?php else : ?>

		<?php wc_print_notice( esc_html__( 'No saved payment methods found.', 'woocommerce' ), 'notice' ); ?>

	<?php endif; ?>

</div>

<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content rounded-4 border-0 shadow-lg p-3">
			<div class="modal-header border-0 pb-1">
				<h5 class="modal-title fw-bold text-dark" id="addPaymentModalLabel"><i class="bi bi-plus-circle-fill text-magenta me-2"></i> Add New Payment Method</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="payment-error"></div>
				<?php  wc_get_template( 'myaccount/form-add-payment-method.php' ); ?>
			</div>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_account_payment_methods', $has_methods ); ?>
