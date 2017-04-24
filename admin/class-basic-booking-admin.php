<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       solutionteam.dk
 * @since      1.0.0
 *
 * @package    Basic_Booking
 * @subpackage Basic_Booking/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Basic_Booking
 * @subpackage Basic_Booking/admin
 * @author     Christian Østerbye <christian.oesterbye@solutionteam.dk>
 */
class Basic_Booking_Admin {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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
		$id = get_current_screen()->id;
        if( $id === 'toplevel_page_basic-booking' || $id === 'basic-booking_page_basic-booking-sub-items' || $id === 'basic-booking_page_basic-booking-sub-settings' ) {
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/basic-booking-admin.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name . '_jquery_ui', plugin_dir_url(__FILE__) . 'css/jquery-ui.min.css', array(), $this->version, 'all');
            wp_enqueue_style($this->plugin_name . '_bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', array(), $this->version, 'all');
        }
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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
        wp_enqueue_script( 'json2' );
        wp_enqueue_script( 'jquery' );
        $menu_nonce = wp_create_nonce("menu_nonce");
        if( get_current_screen()->id === 'toplevel_page_basic-booking' ) {
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_script( $this->plugin_name . 'booking', plugin_dir_url( __FILE__ ) . 'js/basic-booking-admin-booking.js', array( 'jquery', 'json2', 'jquery-ui-datepicker' ), $this->version, false );
            wp_localize_script( $this->plugin_name . 'booking', 'ajax_object', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'valid_menu' => $menu_nonce,
            ));
        }
        else {
            wp_dequeue_script( $this->plugin_name . 'booking' );
            wp_dequeue_script( 'jquery-ui-datepicker' );
        }
        if( get_current_screen()->id === 'basic-booking_page_basic-booking-sub-items' ) {
            wp_enqueue_script( $this->plugin_name . 'items', plugin_dir_url( __FILE__ ) . 'js/basic-booking-admin-items.js', array( 'jquery', 'json2'), $this->version, false );
            wp_localize_script( $this->plugin_name . 'items', 'ajax_object', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'valid_menu' => $menu_nonce,
            ));
        }
        else {
            wp_dequeue_script( $this->plugin_name . 'items' );
        }
        if( get_current_screen()->id === 'basic-booking_page_basic-booking-sub-settings' ) {
            wp_enqueue_script( $this->plugin_name . 'settings', plugin_dir_url( __FILE__ ) . 'js/basic-booking-admin-settings.js', array( 'jquery', 'json2'), $this->version, false );
            wp_localize_script( $this->plugin_name . 'settings', 'ajax_object', array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'valid_menu' => $menu_nonce,
            ));
        }
        else {
            wp_dequeue_script( $this->plugin_name . 'settings' );
        }
        wp_enqueue_script( $this->plugin_name . '_bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array(), $this->version, 'all' );
	}

	/**
	 * Registers the menu item
	 *
	 * @since   1.0.0
	 */
	public function register_menu_page() {
        add_menu_page( "Basic Booking Options", 'Basic Booking', 'manage_options', 'basic-booking', array( $this,'display_options_page') );
        add_submenu_page( 'basic-booking', 'Varer', 'Varer', 'manage_options', 'basic-booking-sub-items', array( $this, 'display_item_options' ) );
        add_submenu_page( 'basic-booking', 'Indstillinger', 'Indstillinger', 'manage_options', 'basic-booking-sub-settings', array( $this, 'display_settings_options' ) );
	}

    /**
     * Registers the hooks used by admin
     *
     * @since   1.0.0
     */
	public function register_admin_hooks() {
		add_action( 'wp_ajax_basic_booking_admin_load_items', array( $this, 'load_items'  ) );
        add_action( 'wp_ajax_basic_booking_admin_load_bookings', array( $this, 'load_bookings' ) );
        add_action( 'wp_ajax_basic_booking_admin_get_calendar_data', array( $this, 'get_calendar_data' ) );
        add_action( 'wp_ajax_basic_booking_admin_insert_item', array( $this, 'insert_item' ) );
        add_action( 'wp_ajax_basic_booking_admin_insert_booking', array( $this, 'insert_booking' ) );
        add_action( 'wp_ajax_basic_booking_admin_activate_item', array ($this, 'activate_item' ) );
        add_action( 'wp_ajax_basic_booking_admin_deactivate_item', array( $this, 'deactivate_item' ) );
        add_action( 'wp_ajax_basic_booking_admin_delete_booking', array( $this, 'delete_booking' ) );
        add_action( 'wp_ajax_basic_booking_admin_update_item', array( $this, 'update_item' ) );
        add_action( 'wp_ajax_basic_booking_admin_update_booking', array( $this, 'update_booking' ) );
        //add_action( 'wp_ajax_basic_booking_admin_get_max_booking_count', array( $this, 'get_max_booking_count' ) );
        //add_action( 'wp_ajax_basic_booking_admin_update_max_booking_count', array( $this, 'update_max_booking_count' ) );
        //add_action( 'wp_ajax_basic_booking_admin_reset_max_booking_count', array( $this, 'reset_max_booking_count' ) );
        add_action( 'wp_ajax_basic_booking_admin_load_auto_email', array( $this, 'load_auto_email' ) );
        add_action( 'wp_ajax_basic_booking_admin_update_auto_email', array( $this, 'update_auto_email' ) );
        add_action( 'wp_ajax_basic_booking_admin_reset_auto_email', array( $this, 'reset_auto_email' ) );
        add_action( 'wp_ajax_basic_booking_admin_load_confirmation_email', array( $this, 'load_confirmation_email' ) );
        add_action( 'wp_ajax_basic_booking_admin_update_confirmation_email', array( $this, 'update_confirmation_email' ) );
        add_action( 'wp_ajax_basic_booking_admin_reset_confirmation_email', array( $this, 'reset_confirmation_email' ) );
        add_action( 'wp_ajax_basic_booking_admin_load_rejection_email', array( $this, 'load_rejection_email' ) );
        add_action( 'wp_ajax_basic_booking_admin_update_rejection_email', array( $this, 'update_rejection_email' ) );
        add_action( 'wp_ajax_basic_booking_admin_reset_rejection_email', array( $this, 'reset_rejection_email' ) );
	}

    /**
	 * Displays the options content
	 *
	 * @since   1.0.0
	 */
	public function display_options_page() {
        $content = file_get_contents( plugins_url('/partials/basic-booking-admin-display.php', __FILE__ ));
        $left_img_url = plugin_dir_url( __FILE__ ) . "/icons/arrow-left.png";
        $content = str_replace( "id='prev_month'", "src='$left_img_url'", $content );
        $right_img_url = plugin_dir_url( __FILE__ ) . "/icons/arrow-right.png";
        $content = str_replace( "id='next_month'", "src='$right_img_url'", $content );
        echo $content;
	}

    /**
     * Displays the item admin screen
     *
     * @since   1.0.0
     */
	public function display_item_options() {
        include_once 'partials/basic-booking-admin-display-items.php';
    }

    /**
     * Displays the settings admin screen
     *
     * @since   1.0.0
     */
    public function display_settings_options() {
        include_once 'partials/basic-booking-admin-display-settings.php';
    }

    /**
     * AJAX function for getting bookable items
     *
     * @since   1.0.0
     */
	public function load_items() {
		check_ajax_referer( 'menu_nonce' );
		global $wpdb;
		$query_string = <<<QS
SELECT name, item_id, color_code, max_booking_count, default_booking_interval, is_active FROM {$wpdb->prefix}basic_booking_bookable_items
QS;
		$result = $wpdb->get_results( $query_string, ARRAY_A );
		echo json_encode( $result );
		wp_die();
	}

    /**
     * Given an integer between 0 and 6, it converts the starting day of the week from sunday to monday
     * @since   1.0.0
     * @param   int     $index
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
     * Returns the name of the website
     *
     * @since   1.0.0
     * @param   string $name
     *
     * @return  string|void
     */
    private function get_name($name='') {
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
    private function get_address($email='') {
        return 'noreply@'.$_SERVER[ 'SERVER_NAME' ];
    }

    /**
     * Grabs data used to display the calendar
     *
     * @since   1.0.0
     * @return  array
     */
    public function get_calendar_data() {
        check_ajax_referer( 'menu_nonce' );
        $year_month = $_POST[ 'year_month' ];
        $year_month = array( 'year' => htmlspecialchars( $year_month[ 'year' ] ), 'month' => htmlspecialchars( $year_month[ 'month' ] ) );
        if(strlen($year_month["month"]) == 1) {
            $year_month["month"] = "0".$year_month["month"];
        }
        $date_string = "{$year_month['year']}-{$year_month['month']}-01 00:00:00";
        $date = new DateTime($date_string);
        $month_start_week = $date->format( 'W' );
        $date_array = explode(' ', $date->format('m t Y'));
        $prev_month = new DateTime($date_array[2].'-'.$date_array[0].'-01');
        $prev_month->sub(new DateInterval('P1M'));
        $next_month = new DateTime($date_array[2].'-'.$date_array[0].'-01');
        $next_month->add(new DateInterval('P2M'));
        $days_in_month = (int) $date_array[1];
        $days_in_prev_month = (int) $prev_month->format('t');
        $first_of_month = new DateTime($date_array[2].'-'.$date_array[0].'-01');
        $first_weekday = $this->monday_week_start($first_of_month->format('w'));
        $date->sub( new DateInterval( "P{$first_weekday}D" ) );
        $date_string = $date->format( 'Y-m-d H:i:s' );
        global $wpdb;
        $query_string = <<<QS
SELECT item.name, item.color_code, booking.start_date, booking.end_date
FROM {$wpdb->prefix}basic_booking_bookings as booking
INNER JOIN {$wpdb->prefix}basic_booking_bookable_items as item
ON item.item_id = booking.booked_item
WHERE booking.end_date >= STR_TO_DATE(%s, '%%Y-%%m-%%d %%H:%%i:%%s')
AND booking.end_date < DATE_ADD(STR_TO_DATE(%s, '%%Y-%%m-%%d %%H:%%i:%%s'), INTERVAL 2 MONTH)
AND booking.status = 1
QS;
        $query_string = $wpdb->prepare( $query_string, $date_string, $date_string );
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
        $json_arr = array(
            'year' => $year_month[ 'year' ],
            'month' => $year_month[ 'month' ],
            'month_start_week' => $month_start_week,
            'first_weekday' => $first_weekday,
            'days_in_month' => $days_in_month,
            'days_in_prev_month' => $days_in_prev_month,
            'booked_periods' => $booked_periods,
        );
        if( isset( $_POST[ 'direct' ] ) && $_POST[ 'direct' ] === 'true' ) {
            echo json_encode( $json_arr );
            wp_die();
        }
        return $json_arr;
    }

    /**
     * AJAX function for getting bookings
     *
     * @since   1.0.0
     */
	public function load_bookings() {
	    check_ajax_referer( 'menu_nonce' );
        $display_count = htmlspecialchars( $_POST[ 'display_count' ] );
        $off_set = htmlspecialchars( $_POST[ 'current_offset' ] );
        $off_set *= $display_count;
        global $wpdb;
        if( isset( $_POST[ 'custom_view' ] ) && $_POST[ 'custom_view' ] ) {
            $default_date = new DateTime();
            $date_span_start = htmlspecialchars( $_POST[ 'date_span_start' ] );
            if( $date_span_start === '' ) {
                $date_span_start = $default_date->format( 'Y-m-d' );
            }
            $date_span_end = htmlspecialchars( $_POST[ 'date_span_end' ] );
            if( $date_span_end === '' ) {
                $default_date->add( new DateInterval( "P60D" ) );
                $date_span_end = $default_date->format( 'Y-m-d' );
            }
            $query_items = array();
            $query_status = array();
            foreach( $_POST[ 'selections' ] as $field => $column ) {
                switch( htmlspecialchars( $column ) ) {
                    case 'item':
                        $query_items[] = "item.name = '{$wpdb->_real_escape( htmlspecialchars( $field ) )}'";
                        break;
                    case 'status':
                        if( htmlspecialchars( $field ) == "select_confirmed" ) {
                            $query_status[] = "booking.status = 1";
                        }
                        elseif( htmlspecialchars( $field ) == "select_unconfirmed" ) {
                            $query_status[] = "booking.status = 0";
                        }
                        break;
                }
            }
            $query_items = implode( ' OR ', $query_items );
            $query_status = implode( ' OR ', $query_status );
            $query_string = <<<QS
SELECT booking.booking_id, item.name, booking.start_date, booking.end_date, booking.customer_name, booking.customer_email, booking.customer_phone_nr, booking.comment, booking.status, booking.booking_interval
FROM {$wpdb->prefix}basic_booking_bookings AS booking
INNER JOIN {$wpdb->prefix}basic_booking_bookable_items AS item
ON item.item_id = booking.booked_item
WHERE booking.start_date >= STR_TO_DATE(%s, '%%Y-%%m-%%d %%H:%%i:%%s')
AND booking.end_date <= STR_TO_DATE(%s, '%%Y-%%m-%%d %%H:%%i:%%s')
AND ($query_items)
AND ($query_status)
ORDER BY booking.start_date, booking.end_date
LIMIT %d, %d
QS;
            $query_string = $wpdb->prepare( $query_string, $date_span_start, $date_span_end, $off_set, $display_count );
            $query_string_count = <<<QS
SELECT COUNT(booking_id) AS amount
FROM {$wpdb->prefix}basic_booking_bookings AS booking
INNER JOIN {$wpdb->prefix}basic_booking_bookable_items AS item
ON item.item_id = booking.booked_item
WHERE booking.start_date >= STR_TO_DATE(%s, '%%Y-%%m-%%d %%H:%%i:%%s')
AND booking.end_date <= STR_TO_DATE(%s, '%%Y-%%m-%%d %%H:%%i:%%s')
AND ($query_items)
AND ($query_status)
ORDER BY booking.start_date, booking.end_date
QS;
            $query_string_count = $wpdb->prepare( $query_string_count, $date_span_start, $date_span_end );
        }
        else {
            $query_string = <<<QS
SELECT booking.booking_id, item.name, booking.start_date, booking.end_date, booking.customer_name, booking.customer_email, booking.customer_phone_nr, booking.comment, booking.status, booking.booking_interval
FROM {$wpdb->prefix}basic_booking_bookings AS booking
INNER JOIN {$wpdb->prefix}basic_booking_bookable_items AS item
ON item.item_id = booking.booked_item
WHERE booking.end_date > CURDATE()
AND booking.status = 0
ORDER BY booking.start_date, booking.end_date
LIMIT %d, %d
QS;
            $query_string = $wpdb->prepare( $query_string, $off_set, $display_count );
            $query_string_count = <<<QS
SELECT COUNT(booking_id) AS amount
FROM {$wpdb->prefix}basic_booking_bookings
WHERE end_date > CURDATE()
AND status = 0
ORDER BY start_date, end_date
QS;
        }
        $bookings = $wpdb->get_results( $query_string, ARRAY_A );
        $max_amount = $wpdb->get_results( $query_string_count, ARRAY_A );
        $query_string = <<<QS
SELECT item_id, name
FROM {$wpdb->prefix}basic_booking_bookable_items
QS;
        $items = $wpdb->get_results( $query_string, ARRAY_A );
        $calendar_data = $this->get_calendar_data();
        $tmp_option = get_option( "basic_booking_available_booking_status" );
        $status_options = json_decode( $tmp_option );
        $json_arr = array(
            'items' => $items,
            'bookings' => $bookings,
            'calendar_data' => $calendar_data,
            'max_amount' => $max_amount[ 0 ][ 'amount' ],
            'status_options' => $status_options,
        );
        echo json_encode( $json_arr );
        wp_die();
    }

    /**
     * AJAX function for creating new items
     *
     * @since   1.0.0
     */
    public function insert_item() {
        check_ajax_referer( 'menu_nonce' );
        $name = htmlspecialchars( $_POST[ 'name' ] );
        $max_count = htmlspecialchars( $_POST[ 'max_count' ] );
        $time_interval = htmlspecialchars( $_POST[ 'time_interval' ] );
        $color_code = "#";
        for( $i = 0; $i < 6; $i++ ) {
            $color_part = rand(0, 15);
            $color_code .= dechex( $color_part );
        }
        global $wpdb;
        $query_string = <<<QS
INSERT INTO {$wpdb->prefix}basic_booking_bookable_items (name, color_code, max_booking_count, default_booking_interval) VALUES (%s, %s, %d, %d)
QS;
        $query_string = $wpdb->prepare( $query_string, $name, $color_code, $max_count, $time_interval );
        $result = $wpdb->get_results( $query_string );
        $this->load_items();
    }

    /**
     * AJAX function for creating new bookings
     *
     * @since   1.0.0
     */
    public function insert_booking() {
        check_ajax_referer( 'menu_nonce' );
        $data = array();
        try {
            $start_date = new DateTime( htmlspecialchars( $_POST[ 'start_date' ] )." ".htmlspecialchars( $_POST[ 'start_time' ] ) );
        }
        catch (Exception $e) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 1;
            echo json_encode( $data );
            wp_die();
        }
        try {
            $end_date = new DateTime( htmlspecialchars( $_POST[ 'end_date' ] )." ".htmlspecialchars( $_POST[ 'end_time' ] ) );
        }
        catch (Exception $e) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 2;
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
        $diff = $end_date->diff( $start_date, true );
        $item = htmlspecialchars( $_POST[ 'booked_item'] );
        global $wpdb;
        $query_string = <<<QS
SELECT max_booking_count
FROM {$wpdb->prefix}basic_booking_bookable_items
WHERE item_id = %d
QS;
        $query_string = $wpdb->prepare( $query_string, $item );
        $count = $wpdb->get_results( $query_string, ARRAY_A );
        $count = intval( $count[ 0 ][ 'max_booking_count' ] );
        if($diff->days > $count ) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 8;
            $data[ 'max_booking_count' ] = $count;
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
SELECT item_id FROM {$wpdb->prefix}basic_booking_bookable_items
WHERE item_id = %d
QS;
        $query_string = $wpdb->prepare($query_string, $item);
        $result = $wpdb->get_results( $query_string, ARRAY_A );
        if( count( $result ) < 1 ) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 6;
            echo json_encode( $data );
            wp_die();
        }
        $query_string = <<<QS
SELECT COUNT(booking_id) AS overlapping_bookings
FROM {$wpdb->prefix}basic_booking_bookings
WHERE %d = booked_item
AND status = 1
AND STR_TO_DATE(%s, '%%Y-%%m-%%d %%H:%%i:%%s') < end_date
AND STR_TO_DATE(%s, '%%Y-%%m-%%d %%H:%%i:%%s') > start_date
QS;
        $query_string = $wpdb->prepare($query_string, $item, $start_date->format( 'Y-m-d H:i:s' ) , $end_date->format( 'Y-m-d H:i:s' ) );
        $result = $wpdb->get_results( $query_string, ARRAY_A );
        if( 0 < $result[ 0 ][ 'overlapping_bookings' ] ) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 7;
            echo json_encode( $data );
            wp_die();
        }
        else {
            $comment = htmlspecialchars( $_POST[ 'comment' ] );
            $booking_interval = htmlspecialchars( $_POST[ 'booking_interval' ] );
            $query_string = <<<QS
INSERT INTO {$wpdb->prefix}basic_booking_bookings (booked_item, start_date, end_date, customer_name, customer_email, customer_phone_nr, comment, booking_interval)
VALUES (%d, %s, %s, %s, %s, %s, %s, %d)
QS;
            $query_string = $wpdb->prepare($query_string, $item, $start_date->format( 'Y-m-d H:i:s' ), $end_date->format( 'Y-m-d H:i:s' ), $customer_name, $customer_email, $customer_phone_nr, $comment, $booking_interval);
            $result = $wpdb->query( $query_string );

            if( $result === 1) {$admin_email = get_option( 'admin_email' );
                $site_name = get_bloginfo( 'name' );
                $subject = 'Udlejning registereret';
                $mail_start_date = $start_date->format( 'Y-m-d H:i' );
                $mail_end_date = $end_date->format( 'Y-m-d H:i' );
                $headers = <<< MHI
From: {$this->get_name()} <{$this->get_address()}>
Content-Type: text/plain; charset="UTF-8";
MHI;
                $admin_mail_content = <<<AMC
Der er blevet registreret en ny booking af {$item}.

Lejers navn: $customer_name
Lejers email: $customer_email
Lejers tlf.nr.: $customer_phone_nr
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

    /**
     * AJAX function for activating specific items
     */
    public function activate_item() {
        check_ajax_referer( 'menu_nonce' );
        $item_id = htmlspecialchars( $_POST[ 'item_id' ] );
        global $wpdb;
        $query_string = <<<QS
UPDATE {$wpdb->prefix}basic_booking_bookable_items
SET is_active = 1
WHERE item_id = %d
QS;
        $query_string = $wpdb->prepare( $query_string, $item_id );
        $result = $wpdb->get_results( $query_string );
        $this->load_items();
    }

    /**
     * AJAX function for deactivating specific items
     *
     * @since   1.0.0
     */
    public function deactivate_item() {
        check_ajax_referer( 'menu_nonce' );
        $item_id = htmlspecialchars( $_POST[ 'item_id' ] );
        global $wpdb;
        $query_string = <<<QS
UPDATE {$wpdb->prefix}basic_booking_bookable_items
SET is_active = 0
WHERE item_id = %d
QS;
        $query_string = $wpdb->prepare( $query_string, $item_id );
        $result = $wpdb->get_results( $query_string );
        $this->load_items();
    }

    /**
     * AJAX function for deleting specific bookings
     *
     * @since   1.0.0
     */
    public function delete_booking() {
        check_ajax_referer( 'menu_nonce' );
        $booking_id = htmlspecialchars( $_POST[ 'booking_id' ] );
        global $wpdb;
        $query_string = <<<QS
DELETE FROM {$wpdb->prefix}basic_booking_bookings
WHERE booking_id = %d
QS;
        $query_string = $wpdb->prepare( $query_string, $booking_id );
        $result = $wpdb->get_results( $query_string );
        $this->load_bookings();
    }

    /**
     * AJAX function for updating an item field
     *
     * @since   1.0.0
     */
    public function update_item() {
        check_ajax_referer( 'menu_nonce' );
        $item_id = htmlspecialchars( $_POST[ 'item_id' ]  );
        $name = htmlspecialchars( $_POST[ 'name' ] );
        $max_count = htmlspecialchars( $_POST[ 'max_count' ] );
        $time_interval = htmlspecialchars( $_POST[ 'time_interval' ] );
        global $wpdb;
        $query_string = <<<QS
UPDATE {$wpdb->prefix}basic_booking_bookable_items
SET name = %s, max_booking_count = %d, default_booking_interval = %d
WHERE item_id = %d
QS;
        $query_string = $wpdb->prepare( $query_string, $name, $max_count, $time_interval, $item_id );
        $result = $wpdb->get_results( $query_string );
        $this->load_items();
    }

    /**
     * AJAX function for updating a booking
     *
     * @since   1.0.0
     */
    public function update_booking() {
        check_ajax_referer( 'menu_nonce' );
        $data = array();
        $booking_id = htmlspecialchars( $_POST[ 'booking_id' ] );
        $item = htmlspecialchars( $_POST[ 'item' ] );
        try {
            $start_date = new DateTime( htmlspecialchars( $_POST[ 'start_date' ] )." ".htmlspecialchars( $_POST[ 'start_time' ] ) );
        }
        catch (Exception $e) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 1;
            echo json_encode( $data );
            wp_die();
        }
        try {
            $end_date = new DateTime( htmlspecialchars( $_POST[ 'end_date' ] )." ".htmlspecialchars( $_POST[ 'end_time' ] ) );
        }
        catch (Exception $e) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 2;
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
        $comment = htmlspecialchars( $_POST[ 'comment' ] );
        global $wpdb;
        $query_string = <<<QS
SELECT name FROM {$wpdb->prefix}basic_booking_bookable_items
WHERE item_id = %s
QS;
        $query_string = $wpdb->prepare($query_string, $item);
        $result = $wpdb->get_results( $query_string, ARRAY_A );
        if( count( $result ) < 1 ) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 6;
            echo json_encode( $data );
            wp_die();
        }
        $item_name = $result[ 0 ][ 'name' ];
        $query_string = <<<QS
SELECT COUNT(booking_id) AS overlapping_bookings
FROM {$wpdb->prefix}basic_booking_bookings
WHERE %d != booking_id
AND %d = booked_item
AND status = 1
AND STR_TO_DATE(%s, '%%Y-%%m-%%d %%H:%%i:%%s') < end_date
AND STR_TO_DATE(%s, '%%Y-%%m-%%d %%H:%%i:%%s') > start_date
QS;
        $query_string = $wpdb->prepare($query_string, $booking_id, $item, $start_date->format( 'Y-m-d H:i:s' ), $end_date->format( 'Y-m-d H:i:s' ) );
        $result = $wpdb->get_results( $query_string, ARRAY_A );
        if( 0 < $result[ 0 ][ 'overlapping_bookings' ] ) {
            $data[ 'status' ] = 'error';
            $data[ 'error_code' ] = 7;
            echo json_encode( $data );
            wp_die();
        }
        $status_string = "";
        if( $_POST[ 'status_change' ] === "1" ) {
            $status = $wpdb->_escape( htmlspecialchars($_POST[ 'status' ] ) );
            $status_string = ", status = $status";
        }
        $booking_interval = htmlspecialchars( $_POST[ 'booking_interval' ] );
        $query_string = <<<QS
UPDATE {$wpdb->prefix}basic_booking_bookings
SET booked_item = %d, start_date = %s, end_date = %s, customer_name = %s, customer_email = %s, customer_phone_nr = %s, comment = %s{$status_string}, booking_interval=%d
WHERE booking_id = %d
QS;
        $query_string = $wpdb->prepare( $query_string, $item, $start_date->format( 'Y-m-d H:i:s' ), $end_date->format( 'Y-m-d H:i:s' ), $customer_name, $customer_email, $customer_phone_nr, $comment, $booking_interval, $booking_id );
        $result = $wpdb->query( $query_string );
        if( $result === 1) {
            if( $status_string != "" && $_POST[ 'status' ] === '1') {
                $admin_email = get_option( 'admin_email' );
                $site_name = get_bloginfo( 'name' );
                $subject = 'Udlejning bekræftet';
                $mail_start_date = $start_date->format( 'Y-m-d h:i' );
                $mail_end_date = $end_date->format( 'Y-m-d h:i' );
                $headers = <<< MHI
From: {$this->get_name()} <{$this->get_address()}>
Content-Type: text/plain; charset="UTF-8";
MHI;
                $admin_mail_content = <<<AMC
Der er blevet bekræftet en ny booking af {$item_name}.

Udlejer navn: $customer_name
Udlejer email: $customer_email
Udlejer tlf.nr.: $customer_phone_nr
Startdato: $mail_start_date
Slutdato: $mail_end_date
Kommentar: $comment
AMC;
                mail( $admin_email, $subject, $admin_mail_content, $headers );
                $mail_content = get_option( 'basic_booking_confirmation_mail' );
                $mail_content = str_replace( '%customer_name%', $customer_name, $mail_content );
                $mail_content = str_replace( '%customer_email%', $customer_email, $mail_content );
                $mail_content = str_replace( '%customer_phone_nr%', $customer_phone_nr, $mail_content );
                $mail_content = str_replace( '%item%', $item_name, $mail_content );
                $mail_content = str_replace( '%comment%', $comment, $mail_content );
                $mail_content = str_replace( '%start_date%', $mail_start_date, $mail_content );
                $mail_content = str_replace( '%end_date%', $mail_end_date, $mail_content );
                $mail_content = str_replace( '%admin_email%', $admin_email, $mail_content );
                $mail_content = str_replace( '%site_name%', $site_name, $mail_content );
                mail( $customer_email, $subject, $mail_content, $headers );
            }
            elseif ( $status_string != "" && $_POST[ 'status' ] === '2') {
                $admin_email = get_option( 'admin_email' );
                $site_name = get_bloginfo( 'name' );
                $subject = 'Udlejning afvist';
                $mail_start_date = $start_date->format( 'Y-m-d h:i' );
                $mail_end_date = $end_date->format( 'Y-m-d h:i' );
                $headers = <<< MHI
From: {$this->get_name()} <{$this->get_address()}>
MHI;
                $mail_content = get_option( 'basic_booking_rejection_mail' );
                $mail_content = str_replace( '%customer_name%', $customer_name, $mail_content );
                $mail_content = str_replace( '%customer_email%', $customer_email, $mail_content );
                $mail_content = str_replace( '%customer_phone_nr%', $customer_phone_nr, $mail_content );
                $mail_content = str_replace( '%item%', $item_name, $mail_content );
                $mail_content = str_replace( '%comment%', $comment, $mail_content );
                $mail_content = str_replace( '%start_date%', $mail_start_date, $mail_content );
                $mail_content = str_replace( '%end_date%', $mail_end_date, $mail_content );
                $mail_content = str_replace( '%admin_email%', $admin_email, $mail_content );
                $mail_content = str_replace( '%site_name%', $site_name, $mail_content );
                mail( $customer_email, $subject, $mail_content, $headers );
            }
            $data[ 'status' ] = 'success';
            $data[ 'result' ] = $result;
            $json = json_encode($data);
            echo $json;
            wp_die();
        }
    }

    /**
     * AJAX function for loading the content of the email sent to the person booking
     *
     * @since   1.0.0
     */
    public function load_auto_email() {
        check_ajax_referer( 'menu_nonce' );
        echo get_option( 'basic_booking_booking_response_mail' );
        wp_die();
    }

    /**
     * AJAX function for updating booking email
     *
     * @since   1.0.0
     */
    public function update_auto_email() {
        check_ajax_referer( 'menu_nonce' );
        $new_content = htmlspecialchars( $_POST[ 'response_email_new'] );
        update_option( 'basic_booking_booking_response_mail', $new_content );
        $this->load_auto_email();
    }

    /**
     * AJAX function for resetting booking email
     *
     * @since   1.0.0
     */
    public function reset_auto_email() {
        check_ajax_referer( 'menu_nonce' );
        $email_content = <<<CMC
Goddag %customer_name%.

Vi har netop modtaget en leje anmodning af %item%. Vi vil behandle din snarligt, hvorefter vi vender tilbage med et svar.

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
        update_option( 'basic_booking_booking_response_mail', $email_content );
        $this->load_auto_email();
    }

    /**
     * AJAX function for loading the content of the confirmation email
     *
     * @since   1.0.0
     */
    public function load_confirmation_email() {
        check_ajax_referer( 'menu_nonce' );
        echo get_option( 'basic_booking_confirmation_mail' );
        wp_die();
    }

    /**
     * AJAX function for updating confirmation email
     *
     * @since   1.0.0
     */
    public function update_confirmation_email() {
        check_ajax_referer( 'menu_nonce' );
        $new_content = htmlspecialchars( $_POST[ 'confirmation_email_new'] );
        update_option( 'basic_booking_confirmation_mail', $new_content );
        $this->load_confirmation_email();
    }

    /**
     * AJAX function for resetting confirmation email
     *
     * @since   1.0.0
     */
    public function reset_confirmation_email() {
        check_ajax_referer( 'menu_nonce' );
        $email_content = <<<CMC
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
        update_option( 'basic_booking_confirmation_mail', $email_content );
        $this->load_confirmation_email();
    }

    /**
     * AJAX function for loading the content of the confirmation email
     *
     * @since   1.0.0
     */
    public function load_rejection_email() {
        check_ajax_referer( 'menu_nonce' );
        echo get_option( 'basic_booking_rejection_mail' );
        wp_die();
    }

    /**
     * AJAX function for updating confirmation email
     *
     * @since   1.0.0
     */
    public function update_rejection_email() {
        check_ajax_referer( 'menu_nonce' );
        $new_content = htmlspecialchars( $_POST[ 'rejection_email_new' ] );
        update_option( 'basic_booking_rejection_mail', $new_content );
        $this->load_rejection_email();
    }

    /**
     * AJAX function for resetting confirmation email
     *
     * @since   1.0.0
     */
    public function reset_rejection_email() {
        check_ajax_referer( 'menu_nonce' );
        $email_content = <<<CMC
Goddag %customer_name%.

Efter at have behandlet din lejeanmodning af %item%, er vi desværre nød til at informere dig om, at din anmodning er blevet afvist. 

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
        update_option( 'basic_booking_rejection_mail', $email_content );
        $this->load_rejection_email();
    }
}
