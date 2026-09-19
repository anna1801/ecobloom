<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 11.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hook - woocommerce_before_edit_account_form.
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_before_edit_account_form' );
?>


	<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

		<?php do_action( 'woocommerce_edit_account_form_start' ); ?>


		<ul class="nav nav-pills mb-4 gap-2" id="accountTabs" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active rounded-pill fw-bold px-4" id="profile-tab" data-bs-toggle="pill" data-bs-target="#profile-pane" type="button" role="tab" aria-selected="true">Personal Details</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link rounded-pill fw-bold px-4 text-dark" id="security-tab" data-bs-toggle="pill" data-bs-target="#security-pane" type="button" role="tab" aria-selected="false">Password & Security</button>
			</li>
		</ul>

		<div class="tab-content" id="accountTabsContent">

			<div class="tab-pane fade show active" id="profile-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
				<div class="about-story-box p-4 bg-white mb-4">
					<h4 class="fw-bold text-dark mb-4">Personal Details</h4>
					<div class="row g-3">
						<div class="col-md-6">
							<label for="account_first_name" class="form-label fw-semibold small text-dark"><?php esc_html_e( 'First Name *', 'woocommerce' ); ?></label>
							<input type="text" class="form-control rounded-pill px-4 py-2 woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" aria-required="true" />
							<!-- display name hidden -->
							<input type="hidden" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" aria-describedby="account_display_name_description" value="<?php echo esc_attr( $user->first_name ); ?>" aria-required="true" />
						</div>
						<div class="col-md-6">
							<label for="account_last_name" class="form-label fw-semibold small text-dark"><?php esc_html_e( 'Last name *', 'woocommerce' ); ?></label>
							<input type="text" class="form-control rounded-pill px-4 py-2 woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" aria-required="true" />
						</div>
						<div class="clear"></div>						
						<div class="col-md-6">
							<label for="account_email" class="form-label fw-semibold small text-dark"><?php esc_html_e( 'Email address *', 'woocommerce' ); ?></label>
							<input type="email" class="form-control rounded-pill px-4 py-2 woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" aria-required="true" />
						</div>
						<div class="col-md-6">
							<label for="account_phone" class="form-label fw-semibold small text-dark"><?php esc_html_e( 'Phone Number *', 'woocommerce' ); ?></label>
							<input type="tel" class="form-control rounded-pill px-4 py-2 woocommerce-Input woocommerce-Input--phone input-text" name="account_phone" id="account_phone" autocomplete="phone" value="<?php echo esc_attr( get_user_meta( $user->ID, 'billing_phone', true ) ); ?>" aria-required="true">
						</div>
					</div>
            	</div>
            </div>

			<?php do_action( 'woocommerce_edit_account_form_fields' ); ?>

			<div class="tab-pane fade" id="security-pane" role="tabpanel" aria-labelledby="security-tab" tabindex="0">
				<div class="about-story-box p-4 bg-white mb-4">
					<h4 class="fw-bold text-dark mb-4"><?php esc_html_e( 'Change Account Password', 'woocommerce' ); ?></h4>

					<div class="row g-3">
						<div class="col-12">
							<label for="password_current" class="form-label fw-semibold small text-dark"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
							<input type="password" class="form-control rounded-pill px-4 py-2 woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="current-password" placeholder="••••••••" />
						</div>
						<div class="col-md-6">
							<label for="password_1" class="form-label fw-semibold small text-dark"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
							<input type="password" class="form-control rounded-pill px-4 py-2 woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="new-password" placeholder="Min. 8 characters" />
						</div>
						<div class="col-md-6">
							<label for="password_2" class="form-label fw-semibold small text-dark"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
							<input type="password" class="form-control rounded-pill px-4 py-2 woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="new-password" placeholder="Repeat new password" />
						</div>
					</div>

					<div class="clear"></div>
				</div>
			</div> 

			<?php	do_action( 'woocommerce_edit_account_form' ); ?>

		</div>








		<p>
			<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
			<button type="submit" class="woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
			<input type="hidden" name="action" value="save_account_details" />
		</p>

		<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
	</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
