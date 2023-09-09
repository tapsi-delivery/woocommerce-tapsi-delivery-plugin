<?php

/**
 * Location Settings
 *
 * This file is used to set the locations settings fields
 *
 * @link       https://www.inverseparadox.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Doordash
 * @subpackage Woocommerce_Doordash/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<h2 class="wc-doordash-locations-heading">
	<?php _e( 'Pickup Locations', 'local-delivery-by-doordash' ); ?>
	<a href="<?php echo admin_url( 'admin.php?page=wc-settings&tab=woocommerce-doordash&section=locations&location_id=new' ); ?>" class="page-title-action"><?php esc_html_e( 'Add pickup location', 'local-delivery-by-doordash' ); ?></a>
</h2>
<p><?php echo __( 'A pickup location is where an order can be picked up by a Dasher.', 'local-delivery-by-doordash' ) . '<br>' . __( 'Enabled locations can be selected by users at checkout during a location’s open hours.', 'local-delivery-by-doordash' ); ?></p>
<table class="wc-doordash-locations widefat">
	<thead>
		<tr>
			<th class="wc-doordash-location-sort"><?php echo wc_help_tip( __( 'Drag and drop to re-order your custom zones. This is the order in which they will be matched against the customer address.', 'local-delivery-by-doordash' ) ); ?></th>
			<th class="wc-doordash-location-name"><?php esc_html_e( 'Location name', 'local-delivery-by-doordash' ); ?></th>
			<th class="wc-doordash-location-address"><?php esc_html_e( 'Address', 'local-delivery-by-doordash' ); ?></th>
			<th class="wc-doordash-location-hours"><?php esc_html_e( 'Custom Hours', 'local-delivery-by-doordash' ); ?></th>
			<th class="wc-doordash-location-enabled"><?php esc_html_e( 'Location Enabled', 'local-delivery-by-doordash' ); ?></th>
		</tr>
	</thead>
	<tbody class="wc-doordash-location-rows"></tbody>
	<tbody>
	</tbody>
</table>

<script type="text/html" id="tmpl-wc-doordash-location-row-blank">
	<?php //if ( 0 === $method_count ) : ?>
		<tr>
			<td class="wc-doordash-locations-blank-state" colspan="5">
				<p class="main"><?php _e( 'Pickup locations can be configured for each of the physical locations of your business.', 'local-delivery-by-doordash' ); ?></p>
				<p><?php _e( 'Each location can be configured with:', 'local-delivery-by-doordash' ); ?></p>
				<ul>
					<li><?php _e( '<em>Location Name</em>', 'local-delivery-by-doordash' ); ?>
					<li><?php _e( '<em>Location Address</em> where orders will be picked up by Dashers', 'local-delivery-by-doordash' ); ?>
					<li><?php _e( '<em>Open Hours</em> for the location, which determine when users will be able to select this location for DoorDash', 'local-delivery-by-doordash' ); ?>
					<li><?php _e( '<em>Lead Time</em> that is required for orders to be filled at this location.', 'local-delivery-by-doordash' ); ?>
				</ul>
				<p><?php _e( 'Users will be able to select the location their order will be fulfilled from during checkout.', 'local-delivery-by-doordash' ); ?></p>
				<a class="button button-primary wc-doordash-location-add" href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=woocommerce-doordash&section=locations&location_id=new' ) ); ?>"><?php _e( 'Add pickup location', 'local-delivery-by-doordash' ); ?></a>
			</td>
		</tr>
	<?php //endif; ?>
</script>

<script type="text/html" id="tmpl-wc-doordash-location-row">
	<tr data-id="{{ data.location_id }}">
		<td width="1%" class="wc-doordash-location-sort"></td>
		<td class="wc-doordash-location-name">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=woocommerce-doordash&section=locations&location_id=' ) ); ?>{{ data.location_id }}">{{ data.location_name }}</a>
			<div class="row-actions">
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=wc-settings&tab=woocommerce-doordash&section=locations&location_id=' ) ); ?>{{ data.location_id }}"><?php _e( 'Edit', 'local-delivery-by-doordash' ); ?></a> | 
				<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=wc-settings&tab=woocommerce-doordash&section=locations' ), 'delete_location' ) ); ?>&amp;delete_location={{ data.location_id }}" class="wc-doordash-location-delete"><?php _e( 'Delete', 'local-delivery-by-doordash' ); ?></a>
				<!-- <a href="admin.php?page=wc-settings&amp;tab=woocommerce-doordash&amp;section=locations&amp;location_id={{ data.location_id }}"><?php _e( 'Edit', 'local-delivery-by-doordash' ); ?></a> | <a href="admin.php?page=wc-settings&amp;tab=woocommerce-doordash&amp;section=locations&amp;location_delete={{ data.location_id }}" class="wc-doordash-location-delete"><?php _e( 'Delete', 'local-delivery-by-doordash' ); ?></a> -->
			</div>
		</td>
		<td class="wc-doordash-location-address">
			{{ data.formatted_location_address }}
		</td>
		<td class="wc-doordash-location-hours">
			<!-- {{ data.location_hours_yesno }} -->
			<a class="wc-doordash-location-hours-toggle-enabled" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=wc-settings&tab=woocommerce-doordash&section=locations' ), 'location_toggle_hours' ) ); ?>&amp;location_toggle_hours={{data.location_id}}">
				<span class="woocommerce-input-toggle woocommerce-input-toggle--{{data.location_hours}}" aria-label="">{{data.location_hours_yesno}}</a>
			</a>
		</td>
		<td class="wc-doordash-location-enabled">
			<!-- {{ data.location_enabled_yesno }} -->
			<a class="wc-doordash-location-toggle-enabled" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=wc-settings&tab=woocommerce-doordash&section=locations' ), 'location_toggle_enabled' ) ); ?>&amp;location_toggle_enabled={{data.location_id}}">
				<span class="woocommerce-input-toggle woocommerce-input-toggle--{{data.location_enabled}}" aria-label="">{{data.location_enabled_yesno}}</a>
			</a>
		</td>
	</tr>
</script>