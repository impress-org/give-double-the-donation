<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Scopes;

use Give\Donations\Models\Donation;


/**
 * Adds payment meta which sets GiveWP employer fields and saves other API responses from DTD
 *
 * @unreleased
 */
class PaymentMeta
{
    public function __construct(Donation $donation)
    {
        if (isset($_POST['doublethedonation_company_id'])) {
            return false;
        }

        $companyID = give_clean($_POST['doublethedonation_company_id']);

        $companyName = isset($_POST['doublethedonation_company_name'])
            ? give_clean($_POST['doublethedonation_company_name'])
            : '';

        $companyEnteredText = isset($_POST['doublethedonation_entered_text'])
            ? give_clean($_POST['doublethedonation_entered_text'])
            : '';

        give_update_meta($donation->id, 'doublethedonation_company_id', $companyID);
        give_update_meta($donation->id, 'doublethedonation_company_name', $companyName);
        give_update_meta($donation->id, 'doublethedonation_entered_text', $companyEnteredText);

        // Update our core "Company Name" field.
        give_update_meta($donation->id, '_give_donation_company', $companyName);

        if (isset($payment_data['user_info']['donor_id'])) {
            Give()->donor_meta->update_meta(absint($payment_data['user_info']['donor_id']), '_give_donor_company', $companyName);
        }
    }
}
