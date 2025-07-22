<?php

namespace GiveDoubleTheDonation\DoubleTheDonation\API\REST;

use Give\Donations\Models\Donation;
use GiveDoubleTheDonation\DoubleTheDonation\FormExtension\Actions\FieldScope\HandleData;
use WP_REST_Request;
use WP_REST_Server;

/**
 * @unreleased
 */
class CompanyMatching
{
    public function __invoke()
    {
        register_rest_route(
            'givewp/dtd',
            'donation/(?P<id>[0-9]+)',
            [
                [
                    'methods' => WP_REST_Server::EDITABLE,
                    'callback' => function (WP_REST_Request $request) {
                        $data = [
                            'company_id' => $request->get_param('companyId'),
                            'company_name' => $request->get_param('companyName'),
                            'entered_text' => $request->get_param('enteredText'),
                        ];

                        $donation = Donation::find($request->get_param('id'));

                        $donationHandler = new HandleData();
                        $donationHandler->save($data, $donation);
                        $donationHandler->send($data, $donation);
                    },
                    'permission_callback' => [$this, 'permissionCheck'],
                ],
                'args' => [
                    'id' => [
                        'type' => 'integer',
                        'required' => true,
                    ],
                    'companyId' => [
                        'type' => 'string',
                        'required' => true,
                        'format' => 'text-field',
                    ],
                    'companyName' => [
                        'type' => 'string',
                        'required' => true,
                        'format' => 'text-field',
                    ],
                    'enteredText' => [
                        'type' => 'string',
                        'required' => true,
                        'format' => 'text-field',
                    ],
                    'receiptId' => [
                        'type' => 'string',
                        'required' => true,
                    ],
                ],
            ]
        );

        register_rest_route(
            'givewp/dtd',
            'donation/(?P<id>[0-9]+)',
            [
                [
                    'methods' => WP_REST_Server::DELETABLE,
                    'callback' => function (WP_REST_Request $request) {
                        $donation = Donation::find($request->get_param('id'));
                        $donationHandler = new HandleData();
                        $donationHandler->remove($donation);
                    },
                    'permission_callback' => [$this, 'permissionCheck'],
                ],
                'args' => [
                    'id' => [
                        'type' => 'integer',
                        'required' => true,
                    ],
                    'receiptId' => [
                        'type' => 'string',
                        'required' => true,
                    ],
                ],
            ]
        );
    }

    /**
     * @unreleased
     */
    public function permissionCheck(WP_REST_Request $request): bool
    {
        $receiptId = $request->get_param('receiptId');
        $donationId = $request->get_param('id');
        $donation = give()->donations->getByReceiptId($receiptId);

        if ( ! $donation) {
            return false;
        }

        return $donation->id === $donationId;
    }
}
