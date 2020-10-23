<?php


namespace GiveDoubleTheDonation\DoubleTheDonation;

use Give\Receipt\DonationReceipt;
use Give\Receipt\Section;


class UpdateDonationReceipt {

	/**
	 * Add employer matching to LEGACY receipt.
	 *
	 * @since 1.0.0
	 */
	public function renderLegacyRow( $donation ) {

		$instructions = $this->getInstructions( $donation->ID );


		if ( isset($instructions['company_name']) ) { ?>
			<tr>
				<td scope="row"><strong><?php esc_html_e( 'Employer', 'give-funds' ); ?></strong></td>
				<td><?php echo $instructions['company_name']; ?></td>
			</tr>

		<?php }

		if ( isset($instructions['next_steps']) ) { ?>
			<tr>
				<td scope="row"><strong><?php esc_html_e( 'Employer Matching', 'give-funds' ); ?></strong></td>
				<td><?php echo $instructions['next_steps']; ?></td>
			</tr>
		<?php }

	}


	/**
	 * Receipt row sequoia template
	 *
	 * @param \Give\Receipt\DonationReceipt $receipt
	 *
	 * @return void|bool
	 * @since 1.0.0
	 */
	public function renderRowSequoiaTemplate( $receipt ) {

		/* @var Section $receiptDonationSection */
		$receiptDonorSection = $receipt[ DonationReceipt::DONORSECTIONID ];

		$instructions = $this->getInstructions( $receipt->donationId );

		if ( ! $instructions ) {
			return false;
		}

		$receiptDonorSection->addLineItem(
			[
				'id'    => 'employerName',
				'label' => esc_html__( 'Employer', 'give-double-the-donation' ),
				'value' => isset( $instructions['company_name'] ) ? $instructions['company_name'] : esc_html__( 'Unknown', 'give-double-the-donation' ),
			],
			'after',
			'emailAddress'
		);

		// Append linked instructions if there are some.
		if ( ! empty( $instructions['next_steps'] ) ) {
			$receiptDonorSection->addLineItem(
				[
					'id'    => 'employerMatch',
					'label' => esc_html__( 'Employer Matching', 'give-double-the-donation' ),
					'value' => $instructions['next_steps'],
				],
				'after',
				'employerName'
			);
		}

	}

	/**
	 * @param $donationID
	 *
	 * @return array|false
	 */
	public function getMatchLinks( $donationID ) {

		$dtdPrivateKey = give_get_option( 'private_dtd_key', false );

		if ( ! $dtdPrivateKey ) {
			return false;
		}

		$companyID   = give_get_payment_meta( $donationID, 'doublethedonation_company_id' );
		$companyName = give_get_payment_meta( $donationID, 'doublethedonation_company_name' );
		$urls         = [];

		// Is Company ID returned?
		if ( $companyID ) {

			$company_response = wp_remote_get( "https://doublethedonation.com/api/360matchpro/v1/companies/{$companyID}?key={$dtdPrivateKey}" );

			$companyResponseBody = json_decode( wp_remote_retrieve_body( $company_response ) );

			$urls = [
				'company_name'       => $companyName,
				'url_guidelines' => isset( $companyResponseBody->url_guidelines ) ? $companyResponseBody->url_guidelines : '',
				'url_forms'      => isset( $companyResponseBody->url_forms ) ? $companyResponseBody->url_forms : '',
			];

		}

		return ! empty( $urls ) ? $urls : false;

	}

	/**
	 * @param $donationID
	 *
	 * @return false|array
	 */
	public function getInstructions( $donationID ) {

		$receiptValues = $this->getMatchLinks( $donationID );

		if ( ! $receiptValues ) {
			return false;
		}

		$instructions = '';

		// These values are often the same in the API response so don't display duplicates.
		if ( $receiptValues['url_guidelines'] === $receiptValues['url_forms'] ) {
			$instructions = sprintf( '<a href="%s" target="_blank">%s &raquo;</a>', $receiptValues['url_guidelines'],
				esc_html__( 'Finish the Match', 'give-double-the-donation' ) );
		}

		if ( $receiptValues['url_guidelines'] !== $receiptValues['url_forms'] ) {
			$instructions= sprintf(
				'<a href="%s" target="_blank" style="display: inline-block; margin: 0 0 10px;">%s &raquo;</a> <br><a href="%s" target="_blank">%s &raquo;</a>',
				$receiptValues['url_guidelines'],
				esc_html__( 'Match Guidelines', 'give-double-the-donation' ),
				$receiptValues['url_forms'],
				esc_html__( 'Complete the Match', 'give-double-the-donation' )
			);
		}

		return [ 'company_name' => $receiptValues['company_name'], 'next_steps' => $instructions ];

	}

}
