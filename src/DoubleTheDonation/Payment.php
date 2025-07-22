<?php

namespace GiveDoubleTheDonation\DoubleTheDonation;

use Give\Donations\Models\Donation;

class Payment {

	/**
	 * Adds payment meta which sets GiveWP employer fields and saves other API responses from DTD.
	 *
	 * @param $payment_id
	 * @param $payment_data
	 *
	 * @return bool
	 */
	public function addPaymentMeta( $payment_id, $payment_data ) {

        $companyID = isset( $_POST['doublethedonation_company_id'] )
            ? give_clean( $_POST['doublethedonation_company_id'] )
            : '';
        $companyName = isset( $_POST['doublethedonation_company_name'] )
            ? give_clean( $_POST['doublethedonation_company_name'] )
            : '';
        $companyEnteredText = isset( $_POST['doublethedonation_entered_text'] )
            ? give_clean( $_POST['doublethedonation_entered_text'] )
            : '';

		if ( ! $companyID ) {
			return false;
		}


		give_update_meta( $payment_id, 'doublethedonation_company_id', $companyID );
		give_update_meta( $payment_id, 'doublethedonation_company_name', $companyName );
		give_update_meta( $payment_id, 'doublethedonation_entered_text', $companyEnteredText );

		// Update our core "Company Name" field.
		give_update_meta( $payment_id, '_give_donation_company', $companyName );

		if ( isset( $payment_data['user_info']['donor_id'] ) ) {
			Give()->donor_meta->update_meta( absint( $payment_data['user_info']['donor_id'] ), '_give_donor_company', $companyName );
		}

	}

	/**
	 * Adds the donation to DTD.
	 *
	 * @param $payment_id
	 * @param $payment_data
	 *
	 * @return false|mixed
	 */
	public function addDonationToDTD( $payment_id, $payment_data ) {
		// API Key check
		$dtdPublicKey = give_get_option( 'public_dtd_key', false );
		if ( ! $dtdPublicKey ) {
			return false;
		}

		$paymentMeta = give_get_payment_meta( $payment_id );

		$data_360 = [
			'360matchpro_public_key' => $dtdPublicKey,
			'donor_first_name'       => $paymentMeta['user_info']['first_name'],
			'donor_last_name'        => $paymentMeta['user_info']['last_name'],
			'donor_email'            => $paymentMeta['email'],
			'campaign'               => $paymentMeta['form_id'],
			'donation_amount'        => give_donation_amount( $payment_id ),
			'donation_identifier'    => Give()->seq_donation_number->get_serial_code( $payment_id ),
			'partner_identifier'     => 'GiveWP',
		];

		$companyID = isset( $paymentMeta['doublethedonation_company_id'] ) ? $paymentMeta['doublethedonation_company_id'] : '';
		if ( $companyID ) {
			$data_360['doublethedonation_company_id'] = $companyID;
		}

		$companyEnteredText = isset( $paymentMeta['doublethedonation_entered_text'] ) ? $paymentMeta['doublethedonation_entered_text'] : '';
		if ( $companyEnteredText ) {
			$data_360['doublethedonation_entered_text'] = $companyEnteredText;
		}

		// Pass donation data to DTD regardless of whether the donor put donor information
		// this is requested by DTD because they have in their system matching processes for donation data.
		$response = wp_remote_post( 'https://doublethedonation.com/api/360matchpro/v1/register_donation',
			[
				'method'   => 'POST',
				'blocking' => true,
				'headers'  => [ 'Content-Type' => 'application/json; charset=utf-8' ],
				'body'     => json_encode( $data_360 ),
				'cookies'  => [],
			]
		);

		$responseBody = json_decode( wp_remote_retrieve_body( $response ) );

		// API fail check.
		if ( 201 !== wp_remote_retrieve_response_code( $response ) ) {
			give()->logs->add(
				'Double the Donation',
				'The API failed during the register_donation process. Message from the API: ' . $responseBody->error,
				0,
				'api_request'
			);

			return false;
		}

		// Success! Add note for admin.
		$note = esc_html__( 'Donation information added to Double the Donation 360MatchPro', 'give-double-the-donation' );
		give_insert_payment_note( $payment_id, $note );

		$companyID = isset( $responseBody->{'matched-company'}->id ) ? $responseBody->{'matched-company'}->id : $companyID;

		return $companyID;

	}


}
