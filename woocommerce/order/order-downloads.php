<?php
/**
 * Order Downloads.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-downloads.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="d-flex flex-column gap-3">
	
	<?php foreach ( $downloads as $download ) : ?>

		<?php foreach ( wc_get_account_downloads_columns() as $column_id => $column_name ) : ?>
			<?php if($column_id === 'download-file') : ?>
				<div class="about-value-card p-4 d-flex align-items-center justify-content-between flex-wrap gap-3 <?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">

					<?php
						if ( has_action( 'woocommerce_account_downloads_column_' . $column_id ) ) {
							do_action( 'woocommerce_account_downloads_column_' . $column_id, $download );
						} else {
							switch ( $column_id ) {
								
								case 'download-file':

									$file = $download['download_url'];

									if($file) :
										
										$filename = $download['download_name'];
										if($filename) {
											$file_name = $filename;
										} else {
											$file_name = 'Eco Bloom File';
										}

										echo'<div class="d-flex align-items-center gap-3">
											<div class="about-value-icon mb-0 flex-shrink-0" style="width: 54px; height: 54px; font-size: 1.5rem;">
												<i class="bi bi-file-earmark-pdf-fill"></i>
											</div>
											<div>
												<h6 class="fw-bold text-dark mb-1">'.esc_html( $file_name ).'</h6>
											</div>
										</div>';

										echo '<a href="' . esc_url( $file ) . '" target="_blank" class="btn btn-outline-dark rounded-pill px-4 btn-sm woocommerce-MyAccount-downloads-file alt">
												<i class="bi bi-download me-1"></i>Download PDF
											</a>';
										break;
									endif;
								
							}
						}
					?>

				</div>
			<?php endif; ?>
		<?php endforeach; ?>
		
	<?php endforeach; ?>
</div>
