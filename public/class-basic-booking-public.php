<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       solutionteam.dk
 * @since      1.0.0
 *
 * @package    Basic_Booking
 * @subpackage Basic_Booking/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Basic_Booking
 * @subpackage Basic_Booking/public
 * @author     Christian Østerbye <christian.oesterbye@solutionteam.dk>
 */
class Basic_Booking_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

    private $boot_time;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version           The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->boot_time = microtime();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Basic_Booking_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Basic_Booking_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        if( has_shortcode(get_post()->post_content, 'basic-booking') ) {
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/basic-booking-public.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name . "_jquery_ui", plugin_dir_url(__FILE__) . 'css/jquery-ui.min.css', array(), $this->version, 'all');
        }
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Basic_Booking_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Basic_Booking_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if( has_shortcode(get_post()->post_content, 'basic-booking') ) {
            wp_enqueue_script( 'json2' );
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_script( $this->plugin_name . 'public', plugin_dir_url( __FILE__ ) . 'js/basic-booking-public.js', array( 'jquery', 'json2' ), $this->version, true );
            $calendar_nonce = wp_create_nonce('calendar' );
            wp_localize_script( $this->plugin_name . 'public', 'ajax_object', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'valid_calendar' => $calendar_nonce,
            ));
        }
	}

    /**
     * Registers all public hooks
     *
     * @since   1.0.0
     */
    public function register_public_hooks() {
        add_shortcode( 'basic-booking', array( $this, 'basic_booking_shortcode_content' ) );
        add_action( 'wp_ajax_basic_booking_load', array( $this, 'basic_booking_get_data' ) );
        add_action( 'wp_ajax_nopriv_basic_booking_load', array( $this, 'basic_booking_get_data' ) );
        add_action( 'wp_ajax_basic_booking_validate_booking', array( $this, 'basic_booking_validate_booking' ) );
        add_action( 'wp_ajax_nopriv_basic_booking_validate_booking', array( $this, 'basic_booking_validate_booking' ) );
    }

    /**
     * Given an integer between 0 and 6, it converts the starting day of the week from sunday to monday
     *
     * @since   1.0.0
     * @param   int     $index
     *
     * @return  int
     */
	private function monday_week_start($index) {
        if($index == 0) {
            return 6;
        }
        else {
            return --$index;
        }
    }

	/**
	 * Outputs the shortcode info
	 *
	 * @since   1.0.0
	 * @param   $atts
	 *
	 * @return  string
	 */
    public function basic_booking_shortcode_content( $atts ) {
        $calendar_content = file_get_contents( plugins_url( '/partials/basic-booking-public-display.php', __FILE__ ) );
	    $atts = shortcode_atts( array("item_id" => ""), $atts );
	    $item_id = $atts[ 'item_id' ];
	    $calendar_content = str_replace( "id='item_id'", "id='item_id' value='$item_id'", $calendar_content );
        $left_img_url = plugin_dir_url( __FILE__ ) . "/icons/arrow-left.png";
        $calendar_content = str_replace( "id='prev_month'", "src='$left_img_url'", $calendar_content );
        $right_img_url = plugin_dir_url( __FILE__ ) . "/icons/arrow-right.png";
        $calendar_content = str_replace( "id='next_month'", "src='$right_img_url'", $calendar_content );
        return $calendar_content;
    }

    /**
     * Returns the name of the website
     *
     * @since   1.0.0
     * @param   string $name
     *
     * @return  string|void
     */
    public function get_name($name='') {
        return get_bloginfo( 'name' );
    }

    /**
     * Returns the email address associated to the website
     *
     * @since   1.0.0
     * @param   string $email
     *
     * @return  string
     */
    public function get_address($email='') {
        return 'noreply@'.$_SERVER[ 'SERVER_NAME' ];
    }

    /**
     * Ajax function. Returns information about the month to be shown, along with booking data.
     *
     * @since   1.0.0
     */
    public function basic_booking_get_data() {
        check_ajax_referer( 'calendar' );
        $year_month = $_POST[ 'year_month' ];
        $year_month = array( 'year' => htmlspecialchars( $year_month[ 'year' ] ), 'month' => htmlspecialchars( $year_month[ 'month' ] ) );
        $item_id = htmlspecialchars( $_POST[ 'item_id' ] );
        if( strlen( $year_month[ "month" ] ) == 1 ) {
            $year_month[ "month" ] = "0" . $year_month[ "month" ];
        }
        $date_string = "{$year_month[ 'year' ]}-{$year_month[ 'month' ]}-01";
        $date = new DateTime( $date_string );
        $month_start_week = $date->format( 'W' );
        $date_array = explode( ' ', $date->format( 'm t Y' ) );
        $prev_month = new DateTime( $date_array[2] . '-' . $date_array[ 0 ] . '-01' );
        $prev_month->sub( new DateInterval( 'P1M' ) );
        $next_month = new DateTime( $date_array[2] . '-' . $date_array[0] . '-01' );
        $next_month->add( new DateInterval( 'P2M' ) );
        $days_in_month = (int) $date_array[ 1 ];
        $days_in_prev_month = (int) $prev_month->format( 't' );
        $first_of_month = new DateTime( $date_array[2] . '-' . $date_array[0] . '-01' );
        $first_weekday = $this->monday_week_start( $first_of_month->format( 'w' ) );
        $date->sub( new DateInterval( "P{$first_weekday}D" ) );
        $date_string = $date->format( 'Y-m-d' );
        global $wpdb;
        $query_string = <<<QS
SELECT start_date, end_date
FROM {$wpdb->prefix}basic_booking_bookings
WHERE booked_item = %d
AND end_date >= STR_TO_DATE(%s, '%%Y-%%m-%%d %%H:%%i:%%s')
AND end_date < DATE_ADD(STR_TO_DATE(%s, '%%Y-%%m-%%d %%H:%%i:%%s'), INTERVAL 2 MONTH)
AND status = 1
QS;
        $query_string = $wpdb->prepare( $query_string, $item_id, $date_string, $date_string );
        $booked_periods = $wpdb->get_results( $query_string, ARRAY_A );
        foreach( $booked_periods as &$booked_period ) {
            $tmp_date = explode( " ", $booked_period[ 'start_date' ] );
            $tmp_date[ 0 ] = explode( "-", $tmp_date[ 0 ] );
            $tmp_date[ 1 ] = explode( ":", $tmp_date[ 1 ] );
            $booked_period[ 'start_date' ] = $tmp_date;
            $tmp_date = explode( " ", $booked_period[ 'end_date' ] );
            $tmp_date[ 0 ] = explode( "-", $tmp_date[ 0 ] );
            $tmp_date[ 1 ] = explode( ":", $tmp_date[ 1 ] );
            $booked_period[ 'end_date' ] = $tmp_date;
        }
        $query_string = <<<QS
SELECT max_booking_count
FROM {$wpdb->prefix}basic_booking_bookable_items
WHERE item_id = %d
QS;
        $query_string = $wpdb->prepare( $query_string, $item_id );
        $max_count = $wpdb->get_results( $query_string, ARRAY_A );
        $json_array = array(
            'year' => $year_month[ 'year' ],
            'month' => $year_month[ 'month' ],
            'month_start_week' => $month_start_week,
            'first_weekday' => $first_weekday,
            'days_in_month' => $days_in_month,
            'days_in_prev_month' => $days_in_prev_month,
            'booked_periods' => $booked_periods,
            'max_booking_count' => $max_count[ 0 ][ 'max_booking_count' ],
        );
        echo json_encode( $json_array );
        wp_die();
    }

    /**
     * AJAX function for validating and creating bookings
     *
     * @since   1.0.0
     */
    public function basic_booking_validate_booking() {
        check_ajax_referer( 'calendar' );
        $item_id = htmlspecialchars( $_POST[ 'item_id'] );
        if( !is_numeric( $item_id ) ) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 6;
            $data[ 'admin_email' ] = get_option( 'admin_email' );
            echo json_encode( $data );
            wp_die();
        }
        global $wpdb;
        $query_string = <<<QS
SELECT name, is_active
FROM {$wpdb->prefix}basic_booking_bookable_items
WHERE item_id = %d
QS;
        $query_string = $wpdb->prepare( $query_string, $item_id );
        $result = $wpdb->get_results( $query_string, ARRAY_A );
        if( 0 == $result[ 0 ][ 'is_active' ] ) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 8;
            echo json_encode( $data );
            wp_die();
        }
        $item = $result[ 0 ][ 'name' ];
        $data = array();
        try {
            $start_date = new DateTime( htmlspecialchars( $_POST[ 'start_date' ] ) );
        }
        catch (Exception $e) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 1;
            echo json_encode( $data );
            wp_die();
        }
        $query_string = <<<QS
SELECT max_booking_count
FROM {$wpdb->prefix}basic_booking_bookable_items
WHERE item_id = %d
QS;
        $query_string = $wpdb->prepare( $query_string, $item_id );
        $max_count = $wpdb->get_results( $query_string, ARRAY_A );
        try {
            if( is_numeric( $_POST[ 'end_date' ] ) && $_POST[ 'end_date' ] <= intval( $max_count[ 0 ][ 'max_booking_count' ] ) && $_POST[ 'end_date' ] > 0 ) {
                $end_date = new DateTime( $start_date->format( 'Y-m-d' ) );
                $end_date->add( new DateInterval( "P{$_POST[ 'end_date' ]}D" ) );
            }
            else {
                throw new Exception();
            }
        }
        catch (Exception $e) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 2;
            $data[ 'max_days' ] = intval( $max_count[ 0 ][ 'max_booking_count' ] );
            echo json_encode( $data );
            wp_die();
        }
        $current_date = new DateTime( date( 'Y-m-d' ) );
        if( $end_date <= $start_date ) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 3;
            echo json_encode( $data );
            wp_die();
        }
        elseif( $start_date <= $current_date ) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 4;
            echo json_encode( $data );
            wp_die();
        }
        $customer_name = htmlspecialchars( $_POST[ 'customer_name' ] );
        $customer_email = is_email( htmlspecialchars( $_POST[ 'customer_email' ] ) );
        if( !$customer_email ) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 5;
            echo json_encode( $data );
            wp_die();
        }
        $customer_phone_nr = htmlspecialchars( $_POST[ 'customer_phone_nr' ] );
        $query_string = <<<QS
SELECT COUNT(booking_id) AS overlapping_bookings
FROM {$wpdb->prefix}basic_booking_bookings
WHERE %d = booked_item
AND STR_TO_DATE(%s, '%%Y-%%m-%%d') < DATE(end_date)
AND STR_TO_DATE(%s, '%%Y-%%m-%%d') > DATE(start_date)
AND status = 1
QS;
        $query_string = $wpdb->prepare($query_string, $item_id, $start_date->format( 'Y-m-d' ) , $end_date->format( 'Y-m-d' ) );
        $result = $wpdb->get_results( $query_string, ARRAY_A );
        if( 0 < $result[ 0 ][ 'overlapping_bookings' ] ) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 7;
            echo json_encode( $data );
            wp_die();
        }
        else {
            $comment = htmlspecialchars( $_POST[ 'comment' ] );
            $query_string = <<<QS
SELECT item.default_booking_interval, booking.start_date, booking.end_date, booking.booking_interval
FROM {$wpdb->prefix}basic_booking_bookings AS booking
INNER JOIN {$wpdb->prefix}basic_booking_bookable_items AS item
ON item.item_id = booking.booked_item
WHERE %d = booking.booked_item
AND (STR_TO_DATE(%s, '%%Y-%%m-%%d') = DATE(booking.end_date)
OR STR_TO_DATE(%s, '%%Y-%%m-%%d') = DATE(booking.start_date))
AND booking.status = 1
QS;
            $query_string = $wpdb->prepare( $query_string, $item_id, $start_date->format( 'Y-m-d' ), $end_date->format( 'Y-m-d' ) );
            $result = $wpdb->get_results( $query_string, ARRAY_A );
            $start_hour = "14";
            $end_hour = "10";
            if( count( $result ) > 0 ) {
                $existing_booking_start = explode( " ", $result[ 0 ][ 'start_date' ] );
                $existing_booking_end = explode( " ", $result[ 0 ][ 'end_date' ] );
                if( strtotime( $existing_booking_start[ 0 ] ) == strtotime( $end_date->format( 'Y-m-d' ) ) ) {
                    $tmp_start_time = explode( ":", $existing_booking_start[ 1 ] );
                    $tmp_start_time[ 0 ] -= $result[ 0 ][ 'default_booking_interval' ];
                    $end_hour = (string) $tmp_start_time[ 0 ];
                    $existing_booking_start[ 1 ] = implode( ":", $tmp_start_time );
                    $existing_booking_start = implode( " ", $existing_booking_start );
                    $query_string = <<<QS
INSERT INTO {$wpdb->prefix}basic_booking_bookings (booked_item, start_date, end_date, customer_name, customer_email, customer_phone_nr, comment, booking_interval)
VALUES (%d, %s, %s, %s, %s, %s, %s, %d)
QS;
                    $query_string = $wpdb->prepare($query_string, $item_id, $start_date->format('Y-m-d') . " $start_hour:00:00", $existing_booking_start, $customer_name, $customer_email, $customer_phone_nr, $comment, $result[0]['default_booking_interval']);
                }
                elseif( strtotime( $existing_booking_end[ 0 ] ) ==  strtotime( $start_date->format( 'Y-m-d' ) ) ) {
                    $tmp_end_time = explode( ":", $existing_booking_end[ 1 ] );
                    $tmp_end_time[ 0 ] += $result[ 0 ][ 'booking_interval' ];
                    $start_hour = $tmp_end_time[ 0 ];
                    $existing_booking_end[ 1 ] = implode( ":", $tmp_end_time );
                    $existing_booking_end = implode( " ", $existing_booking_end );
                    $query_string = <<<QS
INSERT INTO {$wpdb->prefix}basic_booking_bookings (booked_item, start_date, end_date, customer_name, customer_email, customer_phone_nr, comment, booking_interval)
VALUES (%d, %s, %s, %s, %s, %s, %s, %d)
QS;
                    $query_string = $wpdb->prepare($query_string, $item_id, $existing_booking_end, $end_date->format('Y-m-d') . " $end_hour:00:00", $customer_name, $customer_email, $customer_phone_nr, $comment, $result[0]['default_booking_interval']);
                }
                else {
                    $data[ 'status' ] = 'error';
                    $data[ 'error_code' ] = 7;
                    echo json_encode( $data );
                    wp_die();
                }
            }
            else {
                $query_string = <<<QS
SELECT default_booking_interval
FROM {$wpdb->prefix}basic_booking_bookable_items
WHERE %d = item_id
QS;
                $query_string = $wpdb->prepare( $query_string, $item_id );
                $result = $wpdb->get_results( $query_string, ARRAY_A );
                $query_string = <<<QS
INSERT INTO {$wpdb->prefix}basic_booking_bookings (booked_item, start_date, end_date, customer_name, customer_email, customer_phone_nr, comment, booking_interval)
VALUES (%d, %s, %s, %s, %s, %s, %s, %d)
QS;
                $query_string = $wpdb->prepare($query_string, $item_id, $start_date->format('Y-m-d') . " $start_hour:00:00", $end_date->format('Y-m-d') . " $end_hour:00:00", $customer_name, $customer_email, $customer_phone_nr, $comment, $result[0]['default_booking_interval']);
            }
            $result = $wpdb->query( $query_string );
            if( $result === 1) {
                $admin_email = get_option( 'admin_email' );
                $site_name = get_bloginfo( 'name' );
                $subject = 'Udlejning registereret';
                $mail_start_date = $start_date->format( 'Y-m-d' )." $start_hour:00";
                $mail_end_date = $end_date->format( 'Y-m-d' )." $end_hour:00";
                $headers = <<< MHI
From: {$this->get_name()} <{$this->get_address()}>
MHI;
                $admin_mail_content = <<<AMC
Der er blevet registreret en ny booking af {$item}.

Udlejer navn: $customer_name
Udlejer email: $customer_email
Udlejer tlf.nr.: $customer_phone_nr
Startdato: $mail_start_date
Slutdato: $mail_end_date
Kommentar: $comment
AMC;
                mail( $admin_email, $subject, $admin_mail_content, $headers );
                $customer_mail_content = get_option( 'basic_booking_booking_response_mail' );
                $customer_mail_content = str_replace( '%customer_name%', $customer_name, $customer_mail_content );
                $customer_mail_content = str_replace( '%customer_email%', $customer_email, $customer_mail_content );
                $customer_mail_content = str_replace( '%customer_phone_nr%', $customer_phone_nr, $customer_mail_content );
                $customer_mail_content = str_replace( '%item%', $item, $customer_mail_content );
                $customer_mail_content = str_replace( '%comment%', $comment, $customer_mail_content );
                $customer_mail_content = str_replace( '%start_date%', $mail_start_date, $customer_mail_content );
                $customer_mail_content = str_replace( '%end_date%', $mail_end_date, $customer_mail_content );
                $customer_mail_content = str_replace( '%admin_email%', $admin_email, $customer_mail_content );
                $customer_mail_content = str_replace( '%site_name%', $site_name, $customer_mail_content );
                mail( $customer_email, $subject, $customer_mail_content, $headers );
                $data[ 'status' ] = 'success';
                $data[ 'result' ] = $result;
                $json = json_encode($data);
                echo $json;
                wp_die();
            }
        }
    }
}
