<?php


namespace GiveDoubleTheDonation\DoubleTheDonation;


class Payment {

	/**
	 * @param $payment_id
	 * @param $payment_data
	 *
	 * @return bool
	 */
	function addPaymentMeta( $payment_id, $payment_data ) {

		$company_id = isset( $_POST['doublethedonation_company_id'] ) ? give_clean( $_POST['doublethedonation_company_id'] ) : '';
		if ( ! $company_id ) {
			return false;
		}

		$company_id           = isset( $_POST['doublethedonation_company_id'] ) ? give_clean( $_POST['doublethedonation_company_id'] ) : '';
		$company_name         = isset( $_POST['doublethedonation_company_name'] ) ? give_clean( $_POST['doublethedonation_company_name'] ) : '';
		$company_entered_text = isset( $_POST['doublethedonation_entered_text'] ) ? give_clean( $_POST['doublethedonation_entered_text'] ) : '';

		give_update_meta( $payment_id, 'doublethedonation_company_id', $company_id );
		give_update_meta( $payment_id, 'doublethedonation_company_name', $company_name );
		give_update_meta( $payment_id, 'doublethedonation_entered_text', $company_entered_text );

	}

	/**
	 * Passes Donation and donor info to Double the Donation's API.
	 *
	 * @param $payment
	 *
	 * @return bool
	 */
	function appendDTD( $payment ) {

		// API Key check
		$dtd_api_key = give_get_option( 'public_dtd_key', false );
		if ( ! $dtd_api_key ) {
			return false;
		}

		// Compatibility with the new form template receipt and hooking in using `give_new_receipt`
		if ( $payment->donationId ) {
			$donation_id = $payment->donationId;
		} else {
			$donation_id = $payment->ID; // This is hooked in via `give_payment_receipt_before_table`
		}

		$payment_meta = give_get_payment_meta( $donation_id );

		$data_360 = [
			'360matchpro_public_key'         => $dtd_api_key,
			'donor_first_name'               => $payment_meta['user_info']['first_name'],
			'donor_last_name'                => $payment_meta['user_info']['last_name'],
			'donor_email'                    => $payment_meta['email'],
			'campaign'                       => $payment_meta['form_id'],
			'donation_amount'                => give_donation_amount( $donation_id ),
			// TODO: API docs says optional for donation_identifier but API response errors and requires it to be passed.
			'donation_identifier'            => Give()->seq_donation_number->get_serial_code( $donation_id ),
			'doublethedonation_company_id'   => $payment_meta['doublethedonation_company_id'],
			'doublethedonation_entered_text' => $payment_meta['doublethedonation_entered_text'],
			'partner_identifier'             => 'GiveWP',
		];

		$response = wp_remote_post( 'https://doublethedonation.com/api/360matchpro/v1/register_donation',
			[
				'method'   => 'POST',
				'blocking' => true,
				'headers'  => [ 'Content-Type' => 'application/json; charset=utf-8' ],
				'body'     => json_encode( $data_360 ),
				'cookies'  => [],
			]
		);

		$response_body = json_decode( wp_remote_retrieve_body( $response ) );

		error_log( print_r( $data_360, true ) . "\n", 3, WP_CONTENT_DIR . '/debug_new.log' );
		error_log( print_r( $response, true ) . "\n", 3, WP_CONTENT_DIR . '/debug_new.log' );
		error_log( print_r( $response_body, true ) . "\n", 3, WP_CONTENT_DIR . '/debug_new.log' );

		// API fail check.
		if ( 201 !== wp_remote_retrieve_response_code( $response ) ) {
			give()->logs->add(
				'Double the Donation',
				'The API failed during the register_donation process. Message from the API: ' . $response_body->error,
				0,
				'api_request'
			);

			return false;
		}

		// Success! Add note for admin.
		$note = esc_html__( 'Donation information added to Double the Donation 360MatchPro', 'give-double-the-donation' );
		give_insert_payment_note( $payment->ID, $note );

		// TODO: Ask DTD about this code below because I didn't notice any responses coming for esign urls that was reliable.

		//		$esign_url = esc_url( $response_body->esign_url );
		//
		//		ob_start(); ?>
		<!---->
		<!--		--><?php //if ( $esign_url ) : ?>
		<!--			<script>doublethedonation.plugin.set_docusign_envelope_url( resp.esign_url, 'Your donation is eligible for a matching gift. Please click here to fill out the form!' );</script>-->
		<!--		--><?php //endif; ?>
		<!---->
		<!--		<input type="hidden" id="dtd-company-id" value="--><?php //echo $payment_meta['_give_dtd_company_id']; ?><!--" />-->
		<!--		<input type="hidden" id="dtd-donation-identifier" value="--><?php //echo $payment_meta['key']; ?><!--" />-->
		<!--		<input type="hidden" id="dtd-email" value="--><?php //echo $payment_meta['_give_payment_donor_email']; ?><!--" />-->
		<!--		<input type="hidden" id="dtd-page-id" value="--><?php //echo $payment_meta['_give_dtd_page_id']; ?><!--" />-->
		<!---->
		<!--		--><?php //return ob_get_clean();
	}


}
