<?php
namespace GiveDoubleTheDonation\DoubleTheDonation;

/**
 * Example code to show how to add setting page to give settings.
 *
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
		$this->label       = esc_html__( 'Double-the-Donation ', 'give-double-the-donation' );
		$this->default_tab = 'text_fields';

		parent::__construct();
	}


	/**
	 * Add setting sections.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_sections() {
		return [
			'text_fields'     => __( 'Text Fields', 'give-double-the-donation' ),
			'radio_fields'    => __( 'Radio Fields', 'give-double-the-donation' ),
			'select_fields'   => __( 'Select Fields', 'give-double-the-donation' ),
			'checkbox_fields' => __( 'Checkbox Fields', 'give-double-the-donation' ),
			'file_fields'     => __( 'File Fields', 'give-double-the-donation' ),
		];
	}


	/**
	 * Get setting.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_settings() {
		$current_section = give_get_current_setting_section();

		switch ( $current_section ) {

			case 'file_fields':
				return [
					/**
					 * File field setting
					 */
					[
						'name' => esc_html__( 'File', 'give-double-the-donation' ),
						'desc' => '',
						'id'   => 'file_field_setting',
						'type' => 'title',
					],
					[
						'name' => esc_html__( 'File Field Settings', 'give-double-the-donation' ),
						'desc' => '',
						'id'   => 'give_file_field_settings',
						'type' => 'file',
					],
					[
						'id'   => 'file_field_setting',
						'type' => 'sectionend',
					],
				];

			case 'checkbox_fields':
				return [
					/**
					 * Checkbox field setting
					 */
					[
						'name' => esc_html__( 'Checkbox', 'give-double-the-donation' ),
						'desc' => '',
						'id'   => 'checkbox_field_setting',
						'type' => 'title',
					],
					[
						'name' => esc_html__( 'Checkbox Field Settings', 'give-double-the-donation' ),
						'desc' => '',
						'id'   => 'give_checkbox_field_settings',
						'type' => 'checkbox',
					],
					[
						'id'   => 'checkbox_field_setting',
						'type' => 'sectionend',
					],

					/**
					 * Multi Checkbox
					 */
					[
						'name' => esc_html__( 'Multi Checkbox', 'give-double-the-donation' ),
						'desc' => '',
						'id'   => 'multi_checkbox_field_setting',
						'type' => 'title',
					],
					[
						'name'    => __( 'Checkbox Field Settings', 'give-double-the-donation' ),
						'desc'    => '',
						'id'      => 'give_multi_checkbox_field_settings',
						'type'    => 'multicheck',
						'default' => [ 'daily', 'monthly' ],
						'options' => [
							'daily'   => 'Daily',
							'weekly'  => 'Weekly',
							'monthly' => 'Monthly',
						],
					],
					[
						'id'   => 'multi_checkbox_field_setting',
						'type' => 'sectionend',
					],
				];

			case 'select_fields':
				return [
					/**
					 * Select field setting
					 */
					[
						'name' => esc_html__( 'Select', 'give-double-the-donation' ),
						'desc' => '',
						'id'   => 'select_field_setting',
						'type' => 'title',
					],
					[
						'name'    => esc_html__( 'Select Field Settings', 'give-double-the-donation' ),
						'desc'    => '',
						'id'      => 'give_select_field_settings',
						'type'    => 'select',
						'default' => 'option_1',
						'options' => [
							'option_1' => __( 'Option 1', 'give-double-the-donation' ),
							'option_2' => __( 'Option 2', 'give-double-the-donation' ),
							'option_3' => __( 'Option 3', 'give-double-the-donation' ),
						],
					],
					[
						'id'   => 'select_field_setting',
						'type' => 'sectionend',
					],

					/**
					 * MultiSelect field setting
					 */
					[
						'name' => esc_html__( 'Multi Select', 'give-double-the-donation' ),
						'desc' => '',
						'id'   => 'multi_field_setting',
						'type' => 'title',
					],
					[
						'name'    => esc_html__( 'Multi Select Field Settings', 'give-double-the-donation' ),
						'desc'    => '',
						'id'      => 'give_multi_field_settings',
						'type'    => 'multiselect',
						'default' => 'option_1',
						'options' => [
							'option_1' => __( 'Option 1', 'give-double-the-donation' ),
							'option_2' => __( 'Option 2', 'give-double-the-donation' ),
							'option_3' => __( 'Option 3', 'give-double-the-donation' ),
						],
					],
					[
						'id'   => 'multi_field_setting',
						'type' => 'sectionend',
					],
				];

			case 'radio_fields':
				return [

					/**
					 * Radio field setting.
					 */
					[
						'name' => esc_html__( 'Radio', 'give-double-the-donation' ),
						'desc' => '',
						'id'   => 'radio_field_setting',
						'type' => 'title',
					],
					[
						'name'    => esc_html__( 'Radio Field Settings', 'give-double-the-donation' ),
						'desc'    => '',
						'id'      => 'give_radio_field_settings',
						'type'    => 'radio',
						'default' => 'option_1',
						'options' => [
							'option_1' => __( 'Option 1', 'give-double-the-donation' ),
							'option_2' => __( 'Option 2', 'give-double-the-donation' ),
						],
					],
					[
						'id'   => 'radio_field_setting',
						'type' => 'sectionend',
					],

					/**
					 * Inline Radio field setting.
					 */
					[
						'name' => esc_html__( 'Radio inline', 'give-double-the-donation' ),
						'desc' => '',
						'id'   => 'radio_inline_field_setting',
						'type' => 'title',
					],
					[
						'name'    => esc_html__( 'Radio Field Settings', 'give-double-the-donation' ),
						'desc'    => '',
						'id'      => 'give_radio_inline_field_settings',
						'type'    => 'radio_inline',
						'default' => 'option_1',
						'options' => [
							'option_1' => __( 'Option 1', 'give-double-the-donation' ),
							'option_2' => __( 'Option 2', 'give-double-the-donation' ),
						],
					],
					[
						'id'   => 'radio_inline_field_setting',
						'type' => 'sectionend',
					],
				];
		}

		/**
		 * Default settings
		 */
		return [

			/**
			 * Text field setting.
			 */
			[
				'name' => esc_html__( 'Text', 'give-double-the-donation' ),
				'desc' => '',
				'id'   => 'text_field_setting',
				'type' => 'title',
			],
			[
				'name' => esc_html__( 'Text Field Settings', 'give-double-the-donation' ),
				'desc' => '',
				'id'   => 'give_text_field_settings',
				'type' => 'text',
			],
			[
				'id'   => 'text_field_setting',
				'type' => 'sectionend',
			],

			/**
			 * Email field setting.
			 */
			[
				'name' => esc_html__( 'Email', 'give-double-the-donation' ),
				'desc' => '',
				'id'   => 'email_field_setting',
				'type' => 'title',
			],
			[
				'name' => esc_html__( 'Email Field Settings', 'give-double-the-donation' ),
				'desc' => '',
				'id'   => 'give_email_field_settings',
				'type' => 'email',
			],
			[
				'id'   => 'give_email_field_settings',
				'type' => 'sectionend',
			],

			/**
			 * Number field setting.
			 */
			[
				'name' => esc_html__( 'Number', 'give-double-the-donation' ),
				'desc' => '',
				'id'   => 'number_field_setting',
				'type' => 'title',
			],
			[
				'name' => esc_html__( 'Number Field Settings', 'give-double-the-donation' ),
				'desc' => '',
				'id'   => 'give_number_field_settings',
				'type' => 'number',
				'css'  => 'width:12em;',
			],
			[
				'id'   => 'give_number_field_settings',
				'type' => 'sectionend',
			],

			/**
			 * Password field setting.
			 */
			[
				'name' => esc_html__( 'Password', 'give-double-the-donation' ),
				'desc' => '',
				'id'   => 'password_field_setting',
				'type' => 'title',
			],
			[
				'name' => esc_html__( 'Password Field Settings', 'give-double-the-donation' ),
				'desc' => '',
				'id'   => 'give_password_field_settings',
				'type' => 'password',
				'css'  => 'width:12em;',
			],
			[
				'id'   => 'give_password_field_settings',
				'type' => 'sectionend',
			],

			/**
			 * Textarea field setting.
			 */
			[
				'name' => esc_html__( 'TextArea', 'give-double-the-donation' ),
				'desc' => '',
				'id'   => 'textarea_field_setting',
				'type' => 'title',
			],
			[
				'name' => esc_html__( 'Textarea Field Settings', 'give-double-the-donation' ),
				'desc' => '',
				'id'   => 'give_textarea_field_settings',
				'type' => 'textarea',
			],
			[
				'id'   => 'give_textarea_field_settings',
				'type' => 'sectionend',
			],
		];

	}
}
