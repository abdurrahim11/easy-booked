<?php


namespace Appointment\Booking\Frontend;

/**
 * Class Booked_Appointment
 * @package Appointment \Booking\Frontend
 */
class Booked_Appointment {

    /**
     * @var
     */
    private $calendar_general_setting;

    /**
     * Appointment  Handler
     */
    public function booked_appointment() {
        global $wpdb;

        $custom_filed = $this->sanitize_array( $_POST['custom_filed'] );

        $slot_type = sanitize_text_field( $_POST['slot_type'] );
        $slots_id = sanitize_text_field( $_POST['slots_id'] );

        if ( strtolower( $slot_type ) === strtolower( 'day_time_slot' ) ) {
            $table = $wpdb->prefix . 'abs_time_slots';
        } else {
            $table = $wpdb->prefix . 'abs_custom_time_slots';
        }

        $info = $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %s", $slots_id )
        );

        $general_setting = get_option( 'abs_calendar_general' . $info->calendar_id );
        $this->calendar_general_setting = $general_setting;

        // Limit check
        if ( isset( $general_setting['appointment_limit'] ) ) {
            $this->appointment_limit_check( $general_setting, $slots_id, $slot_type, $info );
        }

        $calendar = $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}abs_calendar WHERE id = %s", $info->calendar_id )
        );

        if ( ! empty( $info->product ) && strtolower( $calendar->appointment_type ) === strtolower( 'Paid' ) ) {
            $data = $this->wc_checkout_process( $info, $slot_type, $slots_id, $table, $custom_filed, $calendar );
        } else {
            $data = $this->free_appointment_add( $info, $table, $custom_filed, $calendar );
        }

        wp_send_json( $data );
        wp_die();
    }

    /**
     * Add Product WooCommerce Cart
     *
     * @param $info
     * @param $slot_type
     * @param $slots_id
     * @param $table
     * @return array
     */
    public function wc_checkout_process( $info, $slot_type, $slots_id, $table, $custom_filed, $calendar ) {
        global $wpdb;

        $general_setting = $this->calendar_general_setting;

        $user_id =  get_current_user_id();
        $user_data = get_userdata( $user_id );

        if ( ! isset( $general_setting['display_name'] ) ) {
            $book_name = $user_data->display_name ? $user_data->display_name : sanitize_text_field( $_POST['book_name'] );
        } else {
            $book_name = '';
        }

        if ( ! isset( $general_setting['display_phone_number'] ) ) {
            $get_number = get_user_meta( $user_id, 'phone_number', true );
            $phone = $get_number ? $get_number : sanitize_text_field( $_POST['phone'] );
        } else {
            $phone = '';
        }

        if ( ! isset( $general_setting['display_email'] ) ) {
            $book_email = $user_data->user_email ? $user_data->user_email : sanitize_email( $_POST['book_email'] );
        } else {
            $book_email = '';
        }

        $date = sanitize_text_field( $_POST['date'] );

        global $woocommerce;
        $custom_data = array(
            'book_name'   => $book_name,
            'phone'       => $phone,
            'book_email'  => $book_email,
            'slot_type'   => $slot_type,
            'slot_id'     => $slots_id,
            'date'        => $date,
            'time_slot'   => $info->booking_time,
            'table'       => $table,
            'appointment_default'       => $calendar->appointment_default ,
            'calendar_id' => $info->calendar_id,
            'custom_filed' => $custom_filed,
            'slot_title'   => $info->title,
        );

        $woocommerce->cart->add_to_cart( $info->product, '1', '0', array(), $custom_data );
        $setting = get_option( 'abs_setting' );
        $data = array(
            'status' => 'redirect',
            'url'    => isset( $setting['paid_redirect'] ) == 'checkout_page' ? wc_get_checkout_url() : esc_url_raw( wc_get_cart_url() ),
        );

        return $data;
    }

    /**
     * Appointment  Store
     *
     * @param $info
     * @param $table
     * @return array|string[]
     */
    public function free_appointment_add( $info, $table, $custom_filed, $calendar ) {
        global $wpdb;
        $general_setting = $this->calendar_general_setting;

        $user_id =  get_current_user_id();
        $user_data = get_userdata( $user_id );

        if ( ! isset( $general_setting['display_name'] ) ) {
            $book_name = $user_data->display_name ? $user_data->display_name : sanitize_text_field( $_POST['book_name'] );
        } else {
            $book_name = '';
        }

        if ( ! isset( $general_setting['display_phone_number'] ) ) {
            $get_number = get_user_meta( $user_id, 'phone_number', true );
            $phone = $get_number ? $get_number : sanitize_text_field( $_POST['phone'] );
        } else {
            $phone = '';
        }

        if ( ! isset( $general_setting['display_email'] ) ) {
            $book_email = $user_data->user_email ? $user_data->user_email : sanitize_email( $_POST['book_email'] );
        } else {
            $book_email = '';
        }

        $slot_type = sanitize_text_field( $_POST['slot_type'] );
        $slots_id = sanitize_text_field( $_POST['slots_id'] );
        $date = sanitize_text_field( $_POST['date'] );

        $booking_time = explode( '-', $info->booking_time );
        $start_booking_time = strtotime( $date . $booking_time['0'] );
        $end_booking_time = isset( $booking_time['1'] ) ? strtotime( $date . $booking_time['1'] ) : '';

        if ( strtolower( $calendar->appointment_default ) === strtolower( 'Approve Immediately' ) ) {
            $status = 'eb-approve';
        } else {
            $status = 'eb-pending';
        }

        $fields = array(
            'slot_id'    => $slots_id,
            'slot_type'  => $slot_type,
            'phone'      => $phone,
            'email'      => $book_email,
            'start_date' => $start_booking_time,
            'end_date'   => $end_booking_time,
            'slot_title'   => $info->title,
            'appointment_reminder_email' => 1,
        );

        $post = array(
            'post_author'  => get_current_user_id(),
            'post_name'    => $book_name,
            'post_title'   => $book_name,
            'post_status'  => $status,
            'post_type'    => 'easy-appointments',
        );

        $post_id = wp_insert_post( $post );

        foreach ( $fields as $key => $value ) {
            update_post_meta( $post_id, $key,  $value );
        }

        foreach ( $custom_filed as $row ) {
            update_post_meta( $post_id, 'cmf_' . $row['name'],  $row['value'] );
        }

        do_action( 'abs_appointment_confirmation_email', $post_id, $info->title );

        $update_data = array(
            'space_available' => $info->space_available - 1,
            'booked_space'    => $info->booked_space + 1,
        );

        $booked_format = array( '%d', '%d' );
        $wpdb->update( $table, $update_data, array( 'id' => $slots_id ), $booked_format );

        if ( $post_id ) {
            $data = $this->get_redirect_page( $info );
            $time_format = get_option( 'time_format' );
            $start_booking_time = date_i18n( $time_format, $start_booking_time );
            $end_booking_time = date_i18n( $time_format, $end_booking_time );

            $timezone_string = get_option( 'timezone_string' );
            $start_date = date_i18n( 'm/d/Y', strtotime( $date ) ) . $start_booking_time;
            $end_date = date_i18n( 'm/d/Y', strtotime( $date ) ) . $end_booking_time;
            $date_text = $date . ' at ' . $start_booking_time . $end_booking_time;
            ob_start();
            ?>
            <div class="abs-confirmed">
                <div class="abs-confirmed-header">
                    <h5 class="h5"><?php esc_html_e('Confirmed', 'appointment-booking' ); ?></h5>
                    <!-- Button code -->
                    <div title="" class="addeventatc abs-add-to-calendar" id="addeventatc1" aria-haspopup="true" aria-expanded="false" tabindex="0" style="visibility: visible;">
                        <?php esc_html_e(' Add to Calendar', 'appointment-booking' ); ?>
                        <span class="start atc_node"><?php echo esc_html( $start_date ); ?></span>
                        <?php
                        if ( isset( $booking_time['1']  ) ) {
                            ?>
                            <span class="end atc_node"><?php echo esc_html( $end_date ); ?></span>
                            <?php
                        }
                        ?>
                        <span class="timezone atc_node"><?php echo esc_html( $timezone_string ); ?></span>
                        <span class="title atc_node"><?php echo esc_html( $info->title ); ?></span>
                    </div>
                </div>
                <div class="abs-confirmed-list">
                    <div class="abs-items">
                        <i class="fas fa-calendar-week mr"></i>
                        <h6><?php echo esc_html( $date_text ); ?></h6>
                    </div>
                    <div class="abs-items">
                        <i class="fas fa-globe-asia mr"></i>
                        <h6><?php echo esc_html( $timezone_string ); ?></h6>
                    </div>
                </div>
            </div>
            <?php
            $contain = ob_get_contents();
            ob_end_clean();
            $data['html'] = $contain;
        } else {
            $data = array(
                'status'      => 'failed',
                'calendar_id' => $info->calendar_id,
            );
        }

        return $data;
    }

    /**
     * Get redirect page
     *
     * @param $info
     * @return array
     */
    public function get_redirect_page( $info ) {
        $general_setting = $this->calendar_general_setting;

        if ( isset( $general_setting['redirect'] ) ) {
            if ( strtolower( $general_setting['redirect'] ) === strtolower( 'refresh' ) ) {
                $link = get_permalink( $general_setting['redirect_page'] );
                $data = array(
                    'status' => 'redirect',
                    'url'    => esc_url_raw( $link ),
                );
            } else {
                $data = array(
                    'status'      => 'success',
                    'calendar_id' => $info->calendar_id,
                    'massage'     => esc_html__( 'Your appointment booking success', 'appointment-booking' ),
                );
            }
        } else {
            $data = array(
                'status'      => 'success',
                'calendar_id' => $info->calendar_id,
                'massage'     => esc_html__( 'Your appointment booking success', 'appointment-booking' ),
            );
        }

        return $data;
    }

    /**
     * Check appointment limitation
     *
     * @param $general_setting
     * @param $slots_id
     * @param $slot_type
     * @param $info
     */
    public function appointment_limit_check( $general_setting, $slots_id, $slot_type, $info ) {
        global $wpdb;

        if ( $general_setting['appointment_limit'] != 0 ) {
            $appointment_limit = (int)$wpdb->get_var(
                $wpdb->prepare(
                    "SELECT count(id) FROM {$wpdb->prefix}abs_appointment where slot_id=%s AND slot_type=%s AND user_id=%s",
                    $slots_id,
                    $slot_type,
                    get_current_user_id()
                )
            );

            if ( $appointment_limit >= $general_setting['appointment_limit'] ) {

                $data = array(
                    'status'      => 'failed',
                    'massage'     => esc_html__( 'You have already booked and now you can no longer book it.', 'appointment-booking' ),
                    'calendar_id' => $info->calendar_id,
                );

                wp_send_json( $data );
                wp_die();
            }
        }
    }

    /**
     * Sanitize array
     *
     * @param $array
     * @return mixed
     */
    public function sanitize_array( $array ) {
        foreach ( (array) $array as $k => $v ) {
            if ( is_array( $v ) ) {
                $array[ $k ] =  $this->sanitize_array( $v );
            } else {
                $array[ $k ] = sanitize_text_field( $v );
            }
        }

        return $array;
    }

}