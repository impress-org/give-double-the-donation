<?php

namespace GiveDoubleTheDonation\DoubleTheDonation;

use Give\Helpers\Form\Utils as FormUtils;

class DonationForm {

	/**
	 * Adds the employer search field to the frontend.
	 *
	 * @param $form_id
	 *
	 * @return bool
	 */
	public function employerMatchField( $form_id ) {

		if ( ! give_get_meta( $form_id, 'dtd_enable_disable', true, false ) ) {
			return false;
		}

		$dtd_api_key = give_get_option( 'public_dtd_key', false );
		if ( ! $dtd_api_key ) {
			return false;
		}

		// Do not handle legacy donation form.
		$label_style = ! FormUtils::isLegacyForm() ? 'style="display: block !important; font-size: 14px;"'  : '';
		$div_style = ! FormUtils::isLegacyForm() ? 'style="margin: 0 0 20px;"'  : '';


		$dtd_label = give_get_meta( $form_id, 'give_dtd_label', true, esc_html__( 'See if your company will match your donation!', 'give-double-the-donation' ) );
		?>

		<div class="give-double-the-donation-wrap form-row form-row-wide" <?php echo $div_style; ?>>
			<script>
				if ( window.doublethedonation ) {
					var DDCONF = { 'API_KEY': '<?php echo $dtd_api_key; ?>' };
					document.addEventListener( 'give_gateway_loaded', ( e ) => {
						doublethedonation.plugin.load_streamlined_input();
					} );
				}
			</script>
			<label class="give-label" for="give-first" <?php echo $label_style ?>><?php echo $dtd_label; ?></label>
			<div id="dd-company-name-input"></div>
		</div>
		<?php
	}


}
