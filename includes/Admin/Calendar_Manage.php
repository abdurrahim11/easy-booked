<?php


namespace Appointment\Booking\Admin;

/**
 * Class Calendar_Manage
 * @package Appointment \Booking
 */
class Calendar_Manage {

    /**
     * Store options data
     * @var
     */
    private $options;

    /**
     * Load calendar page
     */
    public function page() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : '';

        switch ( $action ) {
            case 'edit':
                $id = sanitize_text_field( $_GET['id'] );
                $calendar = $this->get_calendar_value( $id );
                $this->options = get_option( 'abs_calendar_general' . $id );
                $template = __DIR__ . '/views/calendar-manage.php';
                break;
            default:
                $template = __DIR__ . '/views/calendar-list.php';
        }

        if ( file_exists( $template ) ) {
            require_once $template;
        }
    }

    /**
     * Calendar Delete
     */
    public function calendar_delete() {
        $wpnonce = sanitize_text_field( $_REQUEST['_wpnonce'] );

        if ( ! wp_verify_nonce( $wpnonce, 'abs-calendar-delete' ) ) {
            wp_die();
        }

        $id = sanitize_text_field( $_REQUEST['id'] );
        global $wpdb;
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->prefix}abs_calendar WHERE id = %d",
                $id
            )
        );

        wp_redirect( 'admin.php?page=calendar-manage' );
        exit();
    }

    /**
     * Calendar edit
     */
    public function calendar_edit() {
        $id = sanitize_text_field( $_REQUEST['id'] );

        $template = __DIR__ . '/views/calendar-manage.php';

        if ( file_exists( $template ) ) {
            require_once $template;
        }
    }

    /**
     * Add new calendar
     */
    public function create_new_calendar() {
        $nonce = sanitize_text_field( $_POST['_wpnonce'] );

        if ( ! wp_verify_nonce( $nonce, 'create-new-calendar' ) ) {
            wp_die();
        }

        global $wpdb;
        $name = sanitize_text_field( $_POST['name'] );
        $booking_type = sanitize_text_field( $_POST['booking_type'] );
        $appointment_free_premium = sanitize_text_field( $_POST['appointment_free_premium'] );
        $appointment_status = sanitize_text_field( $_POST['appointment_status'] );

        $fields = array(
            'name'                => $name,
            'booking_type'        => $booking_type,
            'appointment_type'    => $appointment_free_premium,
            'appointment_default' => $appointment_status,
        );

        $format = array( '%s', '%s', '%s', '%s' );
        $result = $wpdb->insert( "{$wpdb->prefix}abs_calendar", $fields, $format );
        wp_redirect( 'admin.php?page=calendar-manage&action=edit&id=' . $wpdb->insert_id );
        exit();
    }

    /**
     * Get Calendar value
     */
    public function get_calendar_value( $id ) {
        global $wpdb;
        $results = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}abs_calendar WHERE id=%s",
                $id
            )
        );

        return $results;
    }

    /**
     * General setting
     */
    public function general_setting() {
        $nonce = sanitize_text_field( $_POST['_wpnonce'] );
        $id = sanitize_text_field( $_POST['calendar_id'] );

        if ( ! wp_verify_nonce( $nonce, 'abs-general' ) ) {
            wp_die();
        }

        $calendar_data = array();
        foreach ( $_POST['abs'] as $key => $row ) {
            $calendar_data[ $key ] = sanitize_text_field( $row );
        }

        global $wpdb;

        $data = array(
            'name'                 => sanitize_text_field( $_POST['calendar_name'] ),
            'booking_type'         => sanitize_text_field( $_POST['booking_type'] ),
            'appointment_type'     => sanitize_text_field( $_POST['appointment_free_premium'] ),
            'appointment_default'  => sanitize_text_field( $_POST['appointment_status'] )
        );

        $format = array( '%s', '%s', '%s', '%s' );
        $wpdb->update( "{$wpdb->prefix}abs_calendar", $data, array( 'id' => $id ), $format );
        update_option( 'abs_calendar_general' . $id, $calendar_data, true );
        wp_redirect( esc_url_raw( 'admin.php?page=calendar-manage&action=edit&id=' . $id ) );
    }

    /**
     * Get option
     *
     * @param $index_name
     */
    public function get_options(  $index_name ) {
        $options = $this->options;

        if ( isset( $options[ $index_name ] ) ) {
            return $options[ $index_name ];
        } else {
            return;
        }
    }
}