<?php

/**
 * Fired during plugin activation
 *
 * @link       solutionteam.dk
 * @since      1.0.0
 *
 * @package    Basic_Booking
 * @subpackage Basic_Booking/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Basic_Booking
 * @subpackage Basic_Booking/includes
 * @author     Christian Østerbye <christian.oesterbye@solutionteam.dk>
 */
class Basic_Booking_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
        if( !defined( 'ABSPATH' ) ) {
            echo "<script>console.log( 'dying' );</script>";
            die;
        }
        global $wpdb;
        $query_string = <<<QS
SHOW TABLES LIKE '{$wpdb->prefix}basic_booking_bookings'
QS;
        $result = $wpdb->get_results( $query_string );
        if( empty( $result ) ) {
            $query_string = <<<QS
SHOW TABLES LIKE '{$wpdb->prefix}basic_booking_bookable_items'
QS;
            $result = $wpdb->get_results( $query_string );
            if( empty( $result ) ) {
                $query_string = <<<QS
CREATE TABLE {$wpdb->prefix}basic_booking_bookable_items (
item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
name VARCHAR(255) NOT NULL UNIQUE,
max_booking_count INT UNSIGNED NOT NULL DEFAULT 13,
default_booking_interval TINYINT(4) UNSIGNED NOT NULL,
color_code CHAR(7) NOT NULL,
is_active tinyint(1) NOT NULL DEFAULT 1
)
QS;
                $wpdb->get_results($query_string);
            }

            $query_string = <<<QS
CREATE TABLE {$wpdb->prefix}basic_booking_bookings (
booking_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
booked_item INT UNSIGNED NOT NULL,
start_date DATETIME NOT NULL,
end_date DATETIME NOT NULL,
customer_name VARCHAR(255) NOT NULL,
customer_email VARCHAR(255) NOT NULL,
customer_phone_nr VARCHAR(15) NOT NULL,
comment VARCHAR(1023),
status TINYINT(4) UNSIGNED NOT NULL DEFAULT 0,
booking_interval TINYINT(4) UNSIGNED NOT NULL,
FOREIGN KEY (booked_item) REFERENCES {$wpdb->prefix}basic_booking_bookable_items (item_id)
)
QS;
            $wpdb->get_results($query_string);
        }
        $booking_email = <<<CMC
Goddag %customer_name%.

Vi har netop modtaget en lejeanmodning af %item%. Vi vil behandle din anmodning snarligt, hvorefter vi vender tilbage med et svar.

Lejer information:
Navn: %customer_name%
E-mail: %customer_email%
Tlf.nr.: %customer_phone_nr%
Startdato: %start_date%
Slutdato: %end_date%
Kommentar: %comment%

Såfremt du mener at have modtaget denne email ved en fejl, bedes du kontakte %admin_email% så vi kan få rettet fejlen.

Mange tak for din bestilling,
%site_name%
CMC;
        add_option( 'basic_booking_booking_response_mail', $booking_email );
        $confirmation_email = <<<CMC
Goddag %customer_name%.

Efter at have behandlet din lejeanmodning af %item%, er vi glade for at informere dig om at den er blevet bekræftet. Du vil inden for et par dage blive kontaktet af den lejeansvarlige angående den videre process. 

Lejer information:
Navn: %customer_name%
E-mail: %customer_email%
Tlf.nr.: %customer_phone_nr%
Startdato: %start_date%
Slutdato: %end_date%
Kommentar: %comment%

Såfremt du mener at have modtaget denne email ved en fejl, bedes du kontakte %admin_email% så vi kan få rettet fejlen.

Mange tak for din bestilling,
%site_name%
CMC;
        add_option( 'basic_booking_confirmation_mail', $confirmation_email );
        $rejection_mail = <<<CMC
Goddag %customer_name%.

Efter at have behandlet din lejeanmodning af %item%, er vi desværre nød til at informere dig om at din anmodning er blevet afvist. 

Lejer information:
Navn: %customer_name%
E-mail: %customer_email%
Tlf.nr.: %customer_phone_nr%
Startdato: %start_date%
Slutdato: %end_date%
Kommentar: %comment%

Såfremt du mener at have modtaget denne email ved en fejl, bedes du kontakte %admin_email% så vi kan få rettet fejlen.

Mange tak for din bestilling,
%site_name%
CMC;
        add_option( 'basic_booking_rejection_mail', $rejection_mail );
        $status_array = ["Reserveret", "Udlejet", "Afvist"];
        add_option( 'basic_booking_available_booking_status', json_encode($status_array) );
    }
}
