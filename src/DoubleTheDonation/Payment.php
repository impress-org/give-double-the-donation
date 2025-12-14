<?php

namespace GiveDoubleTheDonation\DoubleTheDonation;

use Give\Log\Log;

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
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				Log::warning(
					'Double the Donation: Public key not configured. Skipping API call.',
					[
						'category' => 'Payment',
						'source'   => 'Double the Donation add-on',
						'payment_id' => $payment_id,
					]
				);
			}

			return false;
		}

		$paymentMeta = give_get_payment_meta( $payment_id );
		$donationIdentifier = Give()->seq_donation_number->get_serial_code( $payment_id );

		$data_360 = [
			'360matchpro_public_key' => $dtdPublicKey,
			'donor_first_name'       => $paymentMeta['user_info']['first_name'],
			'donor_last_name'        => $paymentMeta['user_info']['last_name'],
			'donor_email'            => $paymentMeta['email'],
			'campaign'               => $paymentMeta['form_id'],
			'donation_amount'        => give_donation_amount( $payment_id ),
			'donation_identifier'    => $donationIdentifier,
			'recurring'              => $payment_data->is_recurring(),
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

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			Log::info(
				'Double the Donation: Sending donation data to 360MatchPro API',
				[
					'category'           => 'Payment',
					'source'             => 'Double the Donation add-on',
					'payment_id'         => $payment_id,
					'donation_identifier' => $donationIdentifier,
					'company_id'         => $companyID,
					'company_name'       => isset( $paymentMeta['doublethedonation_company_name'] ) ? $paymentMeta['doublethedonation_company_name'] : null,
                    'data'               => $data_360,
				]
			);
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

		$responseCode = wp_remote_retrieve_response_code( $response );
		$responseMessage = wp_remote_retrieve_response_message( $response );
		$responseBody = json_decode( wp_remote_retrieve_body( $response ) );
		$responseBodyArray = json_decode( wp_remote_retrieve_body( $response ), true );
		$responseHeaders = wp_remote_retrieve_headers( $response );
		$responseHeadersArray = ( is_wp_error( $response ) || ! $responseHeaders || ! method_exists( $responseHeaders, 'getAll' ) ) ? [] : $responseHeaders->getAll();

		// Log the raw response from the server
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			Log::http(
				'Double the Donation: Received response from 360MatchPro API',
				[
					'category'           => 'Payment',
					'source'             => 'Double the Donation add-on',
					'payment_id'         => $payment_id,
					'donation_identifier' => $donationIdentifier,
					'response_code'      => $responseCode,
					'response_message'  => $responseMessage,
					'response_headers'  => $responseHeadersArray,
					'response_body'     => $responseBodyArray,
					'raw_response'      => is_wp_error( $response ) ? [
						'error_code'    => $response->get_error_code(),
						'error_message' => $response->get_error_message(),
						'error_data'    => $response->get_error_data(),
					] : $response,
				]
			);
		}

		// API fail check.
		if ( 201 !== $responseCode ) {
			$errorMessage = isset( $responseBody->error ) ? $responseBody->error : ( is_object( $responseBody ) ? wp_json_encode( $responseBody ) : 'Unknown error' );

			Log::error(
				'Double the Donation: Failed to register donation with 360MatchPro API',
				[
					'category'           => 'Payment',
					'source'             => 'Double the Donation add-on',
					'payment_id'         => $payment_id,
					'donation_identifier' => $donationIdentifier,
					'response_code'      => $responseCode,
					'response_message'  => $responseMessage,
					'response_headers'  => $responseHeadersArray,
					'response_body'     => $responseBodyArray,
					'raw_response'      => is_wp_error( $response ) ? [
						'error_code'    => $response->get_error_code(),
						'error_message' => $response->get_error_message(),
						'error_data'    => $response->get_error_data(),
					] : $response,
					'company_id'         => $companyID,
					'api_error_message'  => $errorMessage,
				]
			);

			return false;
		}

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$matchedCompanyId = isset( $responseBody->{'matched-company'}->id ) ? $responseBody->{'matched-company'}->id : null;

			Log::success(
				'Double the Donation: Successfully registered donation with 360MatchPro API',
				[
					'category'           => 'Payment',
					'source'             => 'Double the Donation add-on',
					'payment_id'         => $payment_id,
					'donation_identifier' => $donationIdentifier,
					'response_code'      => $responseCode,
					'response_message'  => $responseMessage,
					'response_headers'  => $responseHeadersArray,
					'response_body'     => $responseBodyArray,
					'company_id'         => $companyID,
					'matched_company_id' => $matchedCompanyId,
				]
			);
		}

		// Success! Add note for admin.
		$note = esc_html__( 'Donation information added to Double the Donation 360MatchPro', 'give-double-the-donation' );
		give_insert_payment_note( $payment_id, $note );

		$companyID = isset( $responseBody->{'matched-company'}->id ) ? $responseBody->{'matched-company'}->id : $companyID;

		return $companyID;

	}


}
