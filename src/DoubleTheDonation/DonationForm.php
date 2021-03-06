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

		if ( give_get_meta( $form_id, 'dtd_enable_disable', true, 'disabled' ) === 'disabled' ) {
			return false;
		}

		$dtdPublicKey = give_get_option( 'public_dtd_key', false );
		if ( ! $dtdPublicKey ) {
			return false;
		}

		// Do not handle legacy donation form.
		$isLegacyForm = FormUtils::isLegacyForm();
		$labelStyle = ! $isLegacyForm ? 'style="display: block !important; font-size: 14px;"'  : '';
		$divStyle = ! $isLegacyForm ? 'style="margin: 0 0 20px;"'  : '';

		$dtdLabel = give_get_meta( $form_id, 'give_dtd_label', true, esc_html__( 'See if your company will match your donation!', 'give-double-the-donation' ) );
		?>

		<div class="give-double-the-donation-wrap form-row form-row-wide" <?php echo $divStyle; ?>>
			<label class="give-label" for="give-first" <?php echo $labelStyle ?>><?php echo $dtdLabel; ?></label>
			<div class="dd-company-name-input"></div>
			<script>
				if ( window.doublethedonation ) {
					var DDCONF = { 'API_KEY': '<?php echo $dtdPublicKey; ?>' };
				}
			</script>
		</div>
		<?php
	}


}
