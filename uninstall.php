<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       solutionteam.dk
 * @since      1.0.0
 *
 * @package    Basic_Booking
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
global $wpdb;
$query_string = <<<QS
SET foreign_key_checks = 0
QS;
$wpdb->query( $query_string );
$query_string = <<<QS
DROP TABLE IF EXISTS {$wpdb->prefix}basic_booking_bookings, {$wpdb->prefix}basic_booking_bookable_items
QS;
$wpdb->query( $query_string );
delete_option( 'basic_booking_booking_response_mail' );
delete_option( 'basic_booking_confirmation_mail' );
delete_option( 'basic_booking_rejection_mail' );
delete_option( 'basic_booking_available_booking_status' );
