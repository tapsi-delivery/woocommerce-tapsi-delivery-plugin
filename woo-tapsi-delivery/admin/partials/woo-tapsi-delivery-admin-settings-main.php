<?php

// TODO: PRUNE these settings are not used anywhere. these fields should be prefilled by default
/**
 * Main Settings
 *
 * This file is used to set the main settings fields
 *
 * @link       https://www.inverseparadox.com
 * @since      0.1.0
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/admin/partials
 */

return array(
    array(
        'id' => $prefix . 'api_config_settings',
        'name' => __('API Configuration', 'woo-tapsi-delivery'),
        'type' => 'title',
        'desc' => __('Visit the <a href="https://developer.tapsi.com/en-US/docs/drive/how_to/manage_credentials" target="_blank">Tapsi Documentation</a> for instructions on how to sign in to the <a href="https://developer.tapsi.com/portal/integration/drive/credentials" target="_blank">Tapsi Developer Portal</a> and create your access keys.', 'woo-tapsi-delivery'),
    ),
    array(
        'id' => $prefix . 'developer_id',
        'name' => __('Developer ID', 'woo-tapsi-delivery'),
        'type' => 'text',
        'desc_tip' => __('Paste your Developer ID obtained from the Tapsi Developer Portal.', 'woo-tapsi-delivery'),
    ),
    array(
        'id' => $prefix . 'api_environment',
        'name' => __('Active Environment', 'woo-tapsi-delivery'),
        'type' => 'select',
        'options' => array(
            'sandbox' => 'Sandbox',
            'production' => 'Production',
        ),
        'desc_tip' => __('Enable <strong>Sandbox</strong> mode to test your integration with Tapsi. Enable <strong>Production</strong> mode to process live deliveries. A separate <em>Key ID</em> and <em>Signing Secret</em> are used for Sandbox and Production environments.'),
        'css' => 'max-width: 120px;',
    ),
    array(
        'id' => $prefix . 'production_key_id',
        'class' => 'wcdd-api-keys__production',
        'name' => __('Key ID', 'woo-tapsi-delivery'),
        'type' => 'text',
        'desc_tip' => __('Paste your <em>Production</em> Key ID obtained from the Tapsi Developer Portal.', 'woo-tapsi-delivery'),
    ),
    array(
        'id' => $prefix . 'production_signing_secret',
        'class' => 'wcdd-api-keys__production',
        'name' => __('Signing Secret', 'woo-tapsi-delivery'),
        'type' => 'text',
        'desc_tip' => __('Paste your <em>Production</em> Signing Secret obtained from the Tapsi Developer Portal.', 'woo-tapsi-delivery'),
    ),
    array(
        'id' => $prefix . 'sandbox_key_id',
        'class' => 'wcdd-api-keys__sandbox',
        'name' => __('Key ID', 'woo-tapsi-delivery'),
        'type' => 'text',
        'desc_tip' => __('Paste your <em>Sandbox</em> Key ID obtained from the Tapsi Developer Portal.', 'woo-tapsi-delivery'),
    ),
    array(
        'id' => $prefix . 'sandbox_signing_secret',
        'class' => 'wcdd-api-keys__sandbox',
        'name' => __('Signing Secret', 'woo-tapsi-delivery'),
        'type' => 'text',
        'desc_tip' => __('Paste your <em>Sandbox</em> Signing Secret obtained from the Tapsi Developer Portal.', 'woo-tapsi-delivery'),
    ),
    array(
        'id' => $prefix . 'api_config_settings',
        'name' => __('API Configuration', 'woo-tapsi-delivery'),
        'type' => 'sectionend',
    ),

    array(
        'id' => $prefix . 'delivery_config_settings',
        'name' => __('Delivery Configuration', 'woo-tapsi-delivery'),
        'type' => 'title',
        'desc' => __('Configure settings for deliveries in your WooCommerce shop.', 'woo-tapsi-delivery'),
    ),
    array(
        'id' => $prefix . 'delivery_scheduling',
        'name' => __('Delivery Scheduling', 'woo-tapsi-delivery'),
        'type' => 'select',
        // 'class'     => 'wc-enhanced-select',
        'options' => array(
            'both' => 'Both ASAP and Scheduled Deliveries',
            'immediate' => 'ASAP Delivery Only',
            'scheduled' => 'Scheduled Delivery Only',
        ),
        'desc_tip' => __('Choose the type of delivery scheduling available to users at checkout.', 'woo-tapsi-delivery'),
    ),
    array(
        'id' => $prefix . 'number_of_days_ahead',
        'name' => __('Scheduled Delivery Days Ahead', 'woo-tapsi-delivery'),
        'type' => 'number',
        'desc_tip' => __('Number of days ahead that deliveries can be scheduled.', 'woo-tapsi-delivery'),
        'css' => 'max-width:120px;',
        'custom_attributes' => array('min' => 1, 'max' => 90),
        'default' => 14,
    ),
    array(
        'id' => $prefix . 'lead_time',
        'name' => __('Order Lead Time', 'woo-tapsi-delivery'),
        'type' => 'number',
        'desc_tip' => __('Number of minutes to allow for preparation of an order.', 'woo-tapsi-delivery'),
        'css' => 'max-width:120px;',
    ),
    array(
        'id' => $prefix . 'default_pickup_instructions',
        'name' => __('Default Pickup Instructions', 'woo-tapsi-delivery'),
        'type' => 'text',
        'desc_tip' => __('These instructions will be provided to Drivers when picking up orders. These instructions can be overridden for each location on the <em>Edit Location</em> screen.', 'woo-tapsi-delivery'),
    ),
    array(
        'id' => $prefix . 'enable_alcohol_tobacco',
        'name' => __('Liquor Store/Smoke Shop Mode', 'woo-tapsi-delivery'),
        'type' => 'select',
        'options' => array(
            'disabled' => 'Disabled',
            'enabled' => 'Enabled',
        ),
        'desc_tip' => __('Contact Tapsi at developer-support@tapsi.com to get approval before enabling this setting in sandbox or production. If your business has not been previously approved by Tapsi for alcohol and tobacco delivery, enabling this setting will cause all delivery requests to be rejected.', 'woo-tapsi-delivery'),
        'default' => 'disabled',
    ),
    array(
        'id' => $prefix . 'delivery_config_settings',
        'name' => __('Delivery Configuration', 'woo-tapsi-delivery'),
        'type' => 'sectionend',
    ),

    array(
        'id' => $prefix . 'fee_settings',
        'name' => __('Fees Configuration', 'woo-tapsi-delivery'),
        'type' => 'title',
        'desc' => __('Set the fees charged to your customers.', 'woo-tapsi-delivery'),
    ),
    array(
        'id' => $prefix . 'fees_mode',
        'name' => __('Delivery Fees Mode', 'woo-tapsi-delivery'),
        'type' => 'select',
        'options' => array(
//			'no_rate' => __( 'No charge for customer (shop pays)', 'woo-tapsi-delivery' ),
            'quoted_rate' => __('Charge customer the quoted Tapsi rate', 'woo-tapsi-delivery'),
//			'fixed_rate' => __( 'Charge customer a fixed rate', 'woo-tapsi-delivery' ),
        ),
        'desc_tip' => __('Choose how the customer will be charged for deliveries.', 'woo-tapsi-delivery'),
    ),
    array(
        'id' => $prefix . 'delivery_fee',
        'name' => __('Delivery Fee', 'woo-tapsi-delivery'),
        'type' => 'number',
        'custom_attributes' => array('step' => 'any', 'min' => '0'),
        'desc_tip' => __('Add a delivery fee in this amount to customer orders. If used with the "Quoted Rate" option above, customer will be charged this fee in addition to the quoted Tapsi rate.', 'woo-tapsi-delivery'),
    ),
    array(
        'id' => $prefix . 'tipping',
        'name' => __('Driver Tipping', 'woo-tapsi-delivery'),
        'type' => 'select',
        'desc_tip' => __('Select Enabled to allow your customers to add a tip for their Tapsier. 100% of tips are passed on to the Driver.', 'woo-tapsi-delivery'),
        'options' => array(
            'enabled' => __('Enabled', 'woo-tapsi-delivery'),
            'disabled' => __('Disabled', 'woo-tapsi-delivery'),
        ),
        'css' => 'max-width:120px;',
    ),
    array(
        'id' => $prefix . 'fee_settings',
        'name' => __('Fees Configuration', 'woo-tapsi-delivery'),
        'type' => 'sectionend',
    ),

    array(
        'id' => $prefix . 'hours_config_settings',
        'name' => __('Default Delivery Hours', 'woo-tapsi-delivery'),
        'type' => 'title',
        'css' => 'max-width: 300px',
        'desc' => sprintf(__('Set the default hours that your shop will fulfill deliveries. These will be the default when new locations are created, if a location&rsquo;s individual hours are disabled, or if no locations have been configured.
		
		Enter hours for each day with a dash separating the opening and closing time, eg <code>10:00am - 8:00pm</code>. 
		Multiple ranges can also be entered separated with a comma, eg, <code>10:00am - 1:00pm, 4:00pm - 7:00pm</code>. 
		If deliveries are not offered, leave the field for that day blank.

		Make sure you have configured your Timezone in your WordPress settings under <a href="%s">Settings &raquo; General</a>. Current local time is <code>%s</code>.', 'woo-tapsi-delivery'), admin_url('options-general.php#timezone_string'), date_i18n(_x('Y-m-d H:i:s', 'timezone date format'))),
    ),
    array(
        'id' => $prefix . 'sunday_hours',
        'name' => __('Sunday', 'woo-tapsi-delivery'),
        'type' => 'text',
    ),
    array(
        'id' => $prefix . 'monday_hours',
        'name' => __('Monday', 'woo-tapsi-delivery'),
        'type' => 'text',
    ),
    array(
        'id' => $prefix . 'tuesday_hours',
        'name' => __('Tuesday', 'woo-tapsi-delivery'),
        'type' => 'text',
    ),
    array(
        'id' => $prefix . 'wednesday_hours',
        'name' => __('Wednesday', 'woo-tapsi-delivery'),
        'type' => 'text',
    ),
    array(
        'id' => $prefix . 'thursday_hours',
        'name' => __('Thursday', 'woo-tapsi-delivery'),
        'type' => 'text',
    ),
    array(
        'id' => $prefix . 'friday_hours',
        'name' => __('Friday', 'woo-tapsi-delivery'),
        'type' => 'text',
    ),
    array(
        'id' => $prefix . 'saturday_hours',
        'name' => __('Saturday', 'woo-tapsi-delivery'),
        'type' => 'text',
    ),
    array(
        'id' => $prefix . 'hours_config_settings',
        'name' => __('Default Delivery Hours', 'woo-tapsi-delivery'),
        'type' => 'sectionend',
    ),

);