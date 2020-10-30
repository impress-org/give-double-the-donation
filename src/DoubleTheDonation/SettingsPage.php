<?php

namespace GiveDoubleTheDonation\DoubleTheDonation;

/**
 * Example code to show how to add setting page to give settings.
 *
 * @package     GiveFBPT\Addon
 * @subpackage  Classes/Give_BP_Admin_Settings
 * @copyright   Copyright (c) 2020, GiveWP
 */
class SettingsPage extends \Give_Settings_Page {

	/**
	 * Settings constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->id          = 'give-double-the-donation';
		$this->label       = esc_html__( 'Double The Donation', 'GIVE_FBPT' );

		parent::__construct();
	}


	/**
	 * Get setting.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_settings() {
		return [
            [
                'name' => '',
                'id'   => 'dtd_title',
                'type' => 'title',
            ],
            [
                'name' => '',
                'id'   => 'dtd_intro',
                'type' => 'dtd_intro',
            ],
            [
                'name' => esc_html__( 'Public API Key', 'give-double-the-donation' ),
                'desc' => esc_html__( 'Please enter the PUBLIC API key from Double the Donation.', 'give-double-the-donation' ),
                'id'   => 'public_dtd_key',
                'type' => 'api_key',
            ],
            [
                'name' => esc_html__( 'Private API Key', 'give-double-the-donation' ),
                'desc' => esc_html__( 'Please enter the PRIVATE API key from Double the Donation.', 'give-double-the-donation' ),
                'id'   => 'private_dtd_key',
                'type' => 'api_key',
            ],
            [
                'name'  => esc_html__( 'Documentation', 'give' ),
                'id'    => 'dtd_docs_link',
                'url'   => esc_url( 'http://docs.givewp.com/double-the-donation' ),
                'title' => esc_html__( 'Documentation', 'give' ),
                'type'  => 'give_docs_link',
            ],
            [
                'id'   => 'dtd_setting',
                'type' => 'sectionend',
            ],
        ];
    }
    
    /**
	 * Render intro
	 *
	 * @since 1.0.0
	 * @return void
	 */
    public function render_intro() { ?>

		<div style="max-width: 600px; margin: 20px 0 25px;">
			<img src="<?php echo GIVE_DTD_URL . '/public/images/dtd-logo.png'; ?>" width="400" />

			<p>Seamlessly integrate the
				<a href="https://doublethedonation.com" target="_blank">Double the Donation</a> database of corporate matching gift and volunteer grant programs with your GiveWP donation forms. Don't have an account with Double the Donation yet?
				<a href="https://zfrmz.com/ciL5Qb5coNEPElvUnYpu" target="_blank">Click here to get started!</a></p>
		</div>

    <?php }
    
    /**
	 * Add Settings Link tab to plugin row.
	 *
	 * @param $actions
	 * @since 1.0.0
	 * @return array
	 */
	public function add_settings_link( $actions ) {
		$new_actions = array(
			'settings' => sprintf(
				'<a href="%1$s">%2$s</a>',
				admin_url( 'edit.php?post_type=give_forms&page=give-settings&tab=general&section=double-the-donation' ),
				__( 'Settings', 'give-double-the-donation' )
			),
		);

		return array_merge( $new_actions, $actions );
	}
}
