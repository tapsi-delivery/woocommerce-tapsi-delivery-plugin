<?php

/**
 * Location Settings
 *
 * This file is used to set the locations settings fields
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<h2 class="wc-tapsi-locations-heading">
	<?php _e( 'Pickup Locations', 'woo-tapsi-delivery' ); ?>
	<a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=woocommerce-tapsi&section=locations&location_id=new' ); ?>" class="page-title-action"><?php esc_html_e( 'Add pickup location', 'woo-tapsi-delivery' ); ?></a>
</h2>
<p><?php echo __( 'A pickup location is where an order can be picked up by a Dasher.', 'woo-tapsi-delivery' ) . '<br>' . __( 'Enabled locations can be selected by users at checkout during a location\'s open hours.', 'woo-tapsi-delivery' ); ?></p>
<table class="wc-tapsi-locations widefat">
	<thead>
		<tr>
			<th class="wc-tapsi-location-sort"><?php echo wc_help_tip( __( 'Drag and drop to re-order your custom zones. This is the order in which they will be matched against the customer address.', 'woo-tapsi-delivery' ) ); ?></th>
			<th class="wc-tapsi-location-name"><?php esc_html_e( 'Location name', 'woo-tapsi-delivery' ); ?></th>
			<th class="wc-tapsi-location-address"><?php esc_html_e( 'Address', 'woo-tapsi-delivery' ); ?></th>
			<th class="wc-tapsi-location-hours"><?php esc_html_e( 'Custom Hours', 'woo-tapsi-delivery' ); ?></th>
			<th class="wc-tapsi-location-enabled"><?php esc_html_e( 'Location Enabled', 'woo-tapsi-delivery' ); ?></th>
		</tr>
	</thead>
	<tbody class="wc-tapsi-location-rows"></tbody>
	<tbody>
	</tbody>
</table>

<script type="text/html" id="tmpl-wc-tapsi-location-row-blank">
	<?php //if ( 0 === $method_count ) : ?>
		<tr>
			<td class="wc-tapsi-locations-blank-state" colspan="5">
				<p class="main"><?php _e( 'Pickup locations can be configured for each of the physical locations of your business.', 'woo-tapsi-delivery' ); ?></p>
				<p><?php _e( 'Each location can be configured with:', 'woo-tapsi-delivery' ); ?></p>
				<ul>
					<li><?php _e( '<em>Location Name</em>', 'woo-tapsi-delivery' ); ?>
					<li><?php _e( '<em>Location Address</em> where orders will be picked up by Dashers', 'woo-tapsi-delivery' ); ?>
					<li><?php _e( '<em>Open Hours</em> for the location, which determine when users will be able to select this location for Tapsi', 'woo-tapsi-delivery' ); ?>
					<li><?php _e( '<em>Lead Time</em> that is required for orders to be filled at this location.', 'woo-tapsi-delivery' ); ?>
				</ul>
				<p><?php _e( 'Users will be able to select the location their order will be fulfilled from during checkout.', 'woo-tapsi-delivery' ); ?></p>
				<a class="button button-primary wc-tapsi-location-add" href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=woocommerce-tapsi&section=locations&location_id=new' ) ); ?>"><?php _e( 'Add pickup location', 'woo-tapsi-delivery' ); ?></a>
			</td>
		</tr>
	<?php //endif; ?>
</script>

<script type="text/html" id="tmpl-wc-tapsi-location-row">
	<tr data-id="{{ data.location_id }}">
		<td width="1%" class="wc-tapsi-location-sort"></td>
		<td class="wc-tapsi-location-name">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=woocommerce-tapsi&section=locations&location_id=' ) ); ?>{{ data.location_id }}">{{ data.location_name }}</a>
			<div class="row-actions">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=woocommerce-tapsi&section=locations&location_id=' ) ); ?>{{ data.location_id }}"><?php _e( 'Edit', 'woo-tapsi-delivery' ); ?></a> |
				<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=wc-settings&tab=woocommerce-tapsi&section=locations' ), 'delete_location' ) ); ?>&amp;delete_location={{ data.location_id }}" class="wc-tapsi-location-delete"><?php _e( 'Delete', 'woo-tapsi-delivery' ); ?></a>
				<!-- <a href="admin.php?page=wc-settings&amp;tab=woocommerce-tapsi&amp;section=locations&amp;location_id={{ data.location_id }}"><?php _e( 'Edit', 'woo-tapsi-delivery' ); ?></a> | <a href="admin.php?page=wc-settings&amp;tab=woocommerce-tapsi&amp;section=locations&amp;location_delete={{ data.location_id }}" class="wc-tapsi-location-delete"><?php _e( 'Delete', 'woo-tapsi-delivery' ); ?></a> -->
			</div>
		</td>
		<td class="wc-tapsi-location-address">
			{{ data.formatted_location_address }}
		</td>
		<td class="wc-tapsi-location-hours">
			<!-- {{ data.location_hours_yesno }} -->
			<a class="wc-tapsi-location-hours-toggle-enabled" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=wc-settings&tab=woocommerce-tapsi&section=locations' ), 'location_toggle_hours' ) ); ?>&amp;location_toggle_hours={{data.location_id}}">
				<span class="woocommerce-input-toggle woocommerce-input-toggle--{{data.location_hours}}" aria-label="">{{data.location_hours_yesno}}</a>
			</a>
		</td>
		<td class="wc-tapsi-location-enabled">
			<!-- {{ data.location_enabled_yesno }} -->
			<a class="wc-tapsi-location-toggle-enabled" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=wc-settings&tab=woocommerce-tapsi&section=locations' ), 'location_toggle_enabled' ) ); ?>&amp;location_toggle_enabled={{data.location_id}}">
				<span class="woocommerce-input-toggle woocommerce-input-toggle--{{data.location_enabled}}" aria-label="">{{data.location_enabled_yesno}}</a>
			</a>
		</td>
	</tr>
</script>