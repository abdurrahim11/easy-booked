<?php


namespace Appointment\Booking\Admin;

/**
 * Class Custom_Time_Slots
 *
 * @package Appointment \Booking\Admin
 */
class Custom_Time_Slots {

    /**
     * Create custom time slots
     */
    public function create_custom_time_slots() {
        $nonce = sanitize_text_field( $_POST['nonce'] );
        if ( ! wp_verify_nonce( $nonce, 'abs_custom_time_slot' ) ) {
            wp_die();
        }

        global $wpdb;
        $start_date = sanitize_text_field( $_POST['start_date'] );
        $end_date = sanitize_text_field( $_POST['end_date'] );
        $slots_type = sanitize_text_field( $_POST['slots_type'] );
        $title = sanitize_text_field( $_POST['title'] );
        $start_time = sanitize_text_field( $_POST['start_time'] );
        $end_time = sanitize_text_field( $_POST['end_time'] );
        $space_available = sanitize_text_field( $_POST['space_available'] );
        $abs_calendar_id = sanitize_text_field( $_POST['abs_calendar_id'] );
        $product = sanitize_text_field( $_POST['product'] );

        $table_name = $wpdb->prefix . 'abs_custom_time_slots';
        $fields = array(
            'calendar_id'     => $abs_calendar_id,
            'start_date'      => $start_date,
            'end_date'        => $end_date,
            'slots_type'      => $slots_type,
            'title'           => $title,
            'booking_time'    => $start_time . '-' . $end_time,
            'space_available' => $space_available,
            'product'         => $product
        );

        $format = array( '%d','%s', '%s', '%d', '%s', '%s', '%d' );
        $status = $wpdb->insert( $table_name, $fields, $format );

        if ( $status ) {
            $this->get_custom_timeslots_all();
        }

        wp_die();
    }

    /**
     * Get all custom time slots
     */
    public function get_custom_timeslots_all() {
        $nonce = sanitize_text_field( $_POST['nonce'] );
        $abs_calendar_id = sanitize_text_field( $_POST['abs_calendar_id'] );
        if ( ! wp_verify_nonce( $nonce, 'abs_custom_time_slot' ) ) {
            wp_die();
        }

        global $wpdb;
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}abs_custom_time_slots WHERE status=%s AND calendar_id=%s ORDER BY id DESC",
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

            $date = $row->start_date;

            if ( ! empty( $row->end_date ) ) {
                $date = $row->start_date . ' - ' . $row->end_date;
            }

            ?>
            <tr>
                <td><?php echo esc_html( $row->title ); ?></td>
                <td><?php echo esc_html( $date ); ?></td>
                <td><?php echo esc_html( $booking_time ); ?></td>
                <td>
                    <?php
                    if ( (int)$row->slots_type === 2 ) {
                        echo esc_html__( 'Disable Appointments', 'appointment-booking' );
                    } else {
                        ?>
                        <span class="dashicons dashicons-remove abs-custom-time-slots-sub-add" data-operator="-" data-id="<?php echo esc_attr( $row->id ); ?>"></span>
                        <span class="abs-spaces-available-num-<?php echo esc_attr( $row->id ); ?>"><?php echo esc_html( $row->space_available ); ?></span>
                        <span class="dashicons dashicons-insert abs-custom-time-slots-sub-add" data-operator="+" data-id="<?php echo esc_attr( $row->id ); ?>"></span>
                        <?php
                    }
                    ?>
                </td>
                <td>
                    <?php do_action("abs_time_slots_edit",$abs_calendar_id,$row->id, 2 ); ?>

                    <div class="abs-custom-time-slots-remove abs-time-slot-icon" data-id="<?php echo esc_attr( $row->id ); ?>">
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
    public function custom_time_slots_remove() {
        $nonce = sanitize_text_field( $_POST['nonce'] );

        if ( ! wp_verify_nonce( $nonce, 'abs_custom_time_slot' ) ) {
            wp_die();
        }

        $id = sanitize_text_field( $_POST['id'] );
        global $wpdb;

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->prefix}abs_custom_time_slots WHERE id = %d",
                $id
            )
        );

        wp_die();
    }

    /**
     * Remove and add time slots spaces
     */
    public function custom_time_slots_sub_add() {
        $nonce = sanitize_text_field( $_POST['nonce'] );

        if ( ! wp_verify_nonce( $nonce, 'abs_custom_time_slot' ) ) {
            wp_die();
        }

        $id = sanitize_text_field( $_POST['id'] );
        $operator = sanitize_text_field( $_POST['operator'] );
        global $wpdb;

        $info = $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}abs_custom_time_slots WHERE id = %s", $id )
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
        $wpdb->update( "{$wpdb->prefix}abs_custom_time_slots", $update_data, array( 'id' => $id ), $booked_format );
        wp_die();
    }
}