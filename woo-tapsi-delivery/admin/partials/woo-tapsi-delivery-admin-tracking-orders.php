<?php

/**
 * Location Settings
 *
 * This file is used to set the locations settings fields
 *
 * @link       https://www.inverseparadox.com
 * @since      0.1.0
 *
 * @package    Woocommerce_Tapsi
 * @subpackage Woocommerce_Tapsi/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<style>
    .submit {
        display: none !important;
    }
</style>
<h2 class="wc-tapsi-locations-heading">
	<?php _e( 'Use the following link to track your shop deliveries.', 'woo-tapsi-delivery' ); ?>
</h2>
<a target="_blank" href="https://pack.tapsi.ir"><?php echo __( 'Tapsi Pack Taker Panel', 'woo-tapsi-delivery' ); ?></a>
