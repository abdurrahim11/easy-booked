<?php


namespace Appointment\Booking;

/**
 * Class WcPayment_Status
 *
 * @package Appointment\Booking
 */
class WcPayment_Status {

    /**
     * WcPayment_Status constructor.
     */
    public function __construct() {
        add_action( 'woocommerce_order_status_changed', array( $this, 'process_status' ), 10, 3 );
    }

    /**
     * @param $order_id
     * @param $old_status
     * @param $status
     */
    public function process_status( $order_id, $old_status, $status ) {
        global $wpdb;

        $status = str_replace( 'wc-', '', $status );

        if ( strtolower( $status ) === strtolower( 'completed' ) ) {
            $appointment_id = get_post_meta( $order_id, 'appointment_id', true );
            $calendar_id = get_post_meta( $order_id, 'calendar_id', true );

            $calendar = $wpdb->get_row(
                $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}abs_calendar WHERE id = %s", $calendar_id )
            );

            if ( strtolower( $calendar->appointment_default ) === strtolower( 'Approve Immediately' ) or strtolower( $calendar->appointment_default ) === strtolower( 'Payment Complete' ) ) {
                $status = 1;
            } else {
                $status = 0;
            }

            $data = array(
                'status' => $status
            );

            $wpdb->update( "{$wpdb->prefix}abs_appointment", $data, array( 'id' => $appointment_id ), '%s' );
        }

        if ( strtolower( $status ) == strtolower( 'cancelled' ) ) {
            $appointment_id = get_post_meta( $order_id, 'appointment_id', true );

            $data = array(
                'status' => 3
            );

            $wpdb->update( "{$wpdb->prefix}abs_appointment", $data, array( 'id' => $appointment_id ), '%s' );
        }
    }
}