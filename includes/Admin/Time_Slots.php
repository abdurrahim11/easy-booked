<?php


namespace Appointment\Booking\Admin;

/**
 * Class Time_Slots
 * @package Appointment \Booking\Admin
 */
class Time_Slots {

    /**
     * Create time slots
     */
    public function create_time_slot() {
        $nonce = sanitize_text_field( $_POST['nonce'] );
        
        if ( ! wp_verify_nonce( $nonce, 'abs_time_slot' ) ) {
            wp_die();
        }

        $week_days = sanitize_text_field( $_POST['day_name'] );
        $title = sanitize_text_field( $_POST['title'] );
        $start_time = sanitize_text_field( $_POST['start_time'] );
        $end_time = sanitize_text_field( $_POST['end_time'] );
        $space_available = sanitize_text_field( $_POST['space_available'] );
        $abs_calendar_id = sanitize_text_field( $_POST['abs_calendar_id'] );
        $product = sanitize_text_field( $_POST['product'] );

        global $wpdb;
        $table_name = $wpdb->prefix . 'abs_time_slots';

        $fields = array(
            'calendar_id'     => $abs_calendar_id,
            'day_name'        => $week_days,
            'title'           => $title,
            'booking_time'    => $start_time . '-' . $end_time,
            'space_available' => $space_available,
            'product'         => $product
        );

        $format = array( '%d', '%s', '%s', '%s', '%d', '%d' );
        $wpdb->insert( $table_name, $fields, $format );
        wp_die();
    }

    /**
     * Get all time slots
     */
    public function get_timeslots_all() {
        $nonce = sanitize_text_field( $_POST['nonce'] );
        $abs_calendar_id = sanitize_text_field( $_POST['abs_calendar_id'] );

        if ( ! wp_verify_nonce( $nonce, 'abs_time_slot' ) ) {
            wp_die();
        }

        global $wpdb;
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}abs_time_slots WHERE status=%s AND calendar_id = %s ORDER BY id DESC",
                0,
                $abs_calendar_id
            )
        );

        foreach ( $results as $row ) {
            $time_format = get_option( 'time_format' );
            $booking_time = explode( '-', $row->booking_time );
            $start_booking_time = date_i18n( $time_format, strtotime( $booking_time['0'] ) );
            $end_booking_time = isset( $booking_time['1'] ) ? ' – ' . date_i18n( $time_format, strtotime( $booking_time['1'] ) ) : '';

            if ( strtolower( 'allday' ) === strtolower( $booking_time['0'] ) ) {
                $booking_time = 'All Day';
            } else {
                $booking_time = $start_booking_time . $end_booking_time;
            }
            ?>
            <tr>
                <td><?php echo esc_html( $row->title ); ?></td>
                <td><?php echo esc_html( $row->day_name ); ?></td>
                <td><?php echo esc_html( $booking_time ); ?></td>
                <td>
                    <span class="dashicons dashicons-remove abs-time-slots-sub-add" data-operator="-" data-id="<?php echo esc_attr( $row->id ); ?>"></span>
                    <span class="abs-spaces-available-num-<?php echo esc_attr( $row->id ); ?>"><?php echo esc_html( $row->space_available ); ?></span>
                    <span class="dashicons dashicons-insert abs-time-slots-sub-add" data-operator="+" data-id="<?php echo esc_attr( $row->id ); ?>"></span>
                </td>
                <td>
                    <?php do_action("abs_time_slots_edit",$abs_calendar_id,$row->id, 1 ); ?>
                    <div class="abs-time-slots-remove abs-time-slot-icon" data-id="<?php echo esc_attr( $row->id ); ?>">
                        <span class="dashicons dashicons-no-alt"></span>
                    </div>
                </td>
            </tr>
            <?php
        }

        wp_die();
    }

    /**
     * Remove time slots
     */
    public function time_slots_remove() {
        $nonce = sanitize_text_field( $_POST['nonce'] );

        if ( ! wp_verify_nonce( $nonce, 'abs_time_slot' ) ) {
            wp_die();
        }

        $id = sanitize_text_field( $_POST['id'] );
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->prefix}abs_time_slots WHERE id = %d",
                $id
            )
        );

        wp_die();
    }

    /**
     * Remove and add time slots spaces
     */
    public function time_slots_sub_add() {
        $nonce = sanitize_text_field( $_POST['nonce'] );

        if ( ! wp_verify_nonce( $nonce, 'abs_time_slot' ) ) {
            wp_die();
        }

        $id = sanitize_text_field( $_POST['id'] );
        $operator = sanitize_text_field( $_POST['operator'] );
        global $wpdb;

        $info = $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}abs_time_slots WHERE id = %s", $id )
        );

        if( strtolower( $operator ) === '-' ) {
            $update_data = array(
                'space_available' => $info->space_available - 1,
            );
        } elseif( strtolower( $operator ) === '+' ) {
            $update_data = array(
                'space_available' => $info->space_available + 1,
            );
        }

        $booked_format = array( '%d' );
        $wpdb->update( "{$wpdb->prefix}abs_time_slots", $update_data, array( 'id' => $id ), $booked_format );
        wp_die();
    }
}