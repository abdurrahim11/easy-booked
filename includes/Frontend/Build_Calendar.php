<?php


namespace Appointment\Booking\Frontend;

/**
 * Class Build_Calendar
 * @package Appointment \Booking
 */
class Build_Calendar {

    /**
     * @var
     */
    private $time_slots;
    private $custom_time_slots;
    private $setting;

    /**
     * Build_Calendar constructor.
     */
    public function __construct() {
        add_shortcode( 'easy-booked', array( $this, 'create_shortcode' ) );
    }

    /**
     * Shortcode calendar
     *
     * @return false|string
     */
    public function create_shortcode( $atts = array(), $content = '' ) {
        $calendar_id = sanitize_text_field( isset( $atts['calendar'] ) ? $atts['calendar'] : '' );
        if ( empty( $calendar_id ) ) {
            return;
        }

        $general_setting = get_option( 'abs_calendar_general' . $calendar_id );
        $this->setting = get_option( 'abs_setting' );
        $start_week = isset( $general_setting['start_week'] ) ? $general_setting['start_week'] : 'Monday';
        $html = '<div class="abs-booked-calendar-area">';
        $html .= $this->create_calendar( date_i18n( 'm' ), date_i18n( 'Y' ), $start_week, $calendar_id );
        $html .= '</div>';
        return $html;
    }

    /**
     * Ajax calendar load
     */
    public function book_calendar_load() {
        $calendar_id = sanitize_text_field( $_POST['calendar_id'] );
        $general_setting = get_option( 'abs_calendar_general' . $calendar_id );
        $start_week = isset( $general_setting['start_week'] ) ? $general_setting['start_week'] : 'Monday';
        $date = sanitize_text_field( $_POST['date'] );
        $html = $this->create_calendar( date_i18n( 'm', $date ), date_i18n( 'Y', $date ), $start_week, $calendar_id );
        echo wp_kses_post( $html );
        wp_die();
    }

    /**
     * Create calendar
     */
    public function create_calendar( $month, $year, $start_week = null, $calendar_id ) {
        $first_day_of_month = mktime( 0, 0, 0, $month, 1, $year );
        $number_of_days = date_i18n( 't', $first_day_of_month );
        $this->time_slots = $this->get_time_slots_all( $calendar_id );
        $days_of_week = array( 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun' );
        $date_components = getdate( $first_day_of_month );

        $month_name = $date_components['month'];
        $day_of_week = $date_components['wday'];

        if ( strtolower( $start_week ) === strtolower( 'Sunday' ) ) {
            $days_of_week = array( 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', );
        } else {
            $day_of_week_date = sprintf( "%s-%s-01", $year, $month );
            $day_of_week = date_i18n( 'N', strtotime( $day_of_week_date ) ) - 1;
        }

        $today_date = date_i18n( "Y-m-d" );
        $number_of_days_before_month = date_i18n( "t", mktime( 0, 0, 0, $month - 1, 1, $year ) );
        $count = 1 + $number_of_days_before_month - $day_of_week;
        $previous_year_month = date_i18n( 'Y-m', mktime( 0, 0, 0, $month - 1, 1, $year ) );
        $previous_date = "$previous_year_month-$count";

        $this->custom_time_slots = $this->get_custom_time_slots_all( $previous_date, $first_day_of_month, $calendar_id );

        ob_start();
        ?>
        <table class="abs-booked-calendar <?php echo esc_attr( 'abs-booked-calendar-' . $calendar_id ); ?>"
               data-date="<?php echo esc_attr( $first_day_of_month ); ?>">
            <thead class="abs-calendar-header">
            <tr>
                <th colspan="7">
                    <?php
                    if ( $first_day_of_month >= strtotime( $today_date ) ) {
                        ?>
                        <a href="#" data-calendar-id="<?php echo esc_attr( $calendar_id ); ?>"
                           data-goto="<?php echo esc_attr( mktime( 0, 0, 0, $month - 1, 1, $year ) ); ?>"
                           class="abs-page-calendar-button abs-page-left">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <?php
                    }

                    echo sprintf( '<span class="abs-month-name">%s %s</span>',
                        esc_html( $month_name ),
                        esc_html( $year )
                    );
                    ?>
                    <i class="far fa-clock abs-load-time "></i>
                    <a href="#" data-calendar-id="<?php echo esc_attr( $calendar_id ); ?>"
                       data-goto="<?php echo esc_attr( mktime( 0, 0, 0, $month + 1, 1, $year ) ); ?>"
                       class="abs-page-calendar-button abs-page abs-page-right">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </th>
            </tr>
            <tr class="abs-days">
                <?php
                foreach ( $days_of_week as $day_name ) {
                    $day_name = sprintf( '<th> %s </th>', $day_name );
                    echo wp_kses_post( $day_name );
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <tr class="abs-week">
                <?php
                if ( $day_of_week > 0 ) {
                    for ( $i = 0; $i < $day_of_week; $i++ ) {
                        $time_slot = $this->bookend_status( $previous_date, $calendar_id );

                        if ( strtotime( $previous_date ) < strtotime( $today_date ) ) {
                            $date = sprintf( "<td><span class='abs-date'><span class='abs-number'>%s</span></span></td>", esc_html( $count ) );
                            echo wp_kses_post( $date );
                        } elseif ( strtotime( $previous_date ) == strtotime( $today_date ) ) {
                            $date = sprintf( "<td class='abs-data' data-calendar-id='%s' data-date='%s'><span class='abs-date %s'><span class='abs-number %s' title='%s Available' data-appointments-count='%s'>%s</span></span></td>",
                                esc_attr( $calendar_id ),
                                esc_attr( $previous_date ),
                                esc_attr( $time_slot['class'] ),
                                esc_attr( $time_slot['count'] != 0 ? 'abs-tooltip' : '' ),
                                esc_attr( $time_slot['count'] ),
                                esc_attr( $time_slot['count'] ),
                                esc_html( $count )
                            );
                            echo wp_kses_post( $date );
                        } else {
                            $date = sprintf( "<td class='abs-data' data-calendar-id='%s' data-date='%s'><span class='abs-date %s'><span class='abs-number %s' title='%s Available' data-appointments-count='%s'>%s</span></span></td>",
                                esc_attr( $calendar_id ),
                                esc_attr( $previous_date ),
                                esc_attr( $time_slot['class'] ),
                                esc_attr( $time_slot['count'] != 0 ? 'abs-tooltip' : '' ),
                                esc_attr( $time_slot['count'] ),
                                esc_attr( $time_slot['count'] ),
                                esc_html( $count )
                            );
                            echo wp_kses_post( $date );
                        }

                        $count++;

                        $time_slot_in = strtotime( "1 day", strtotime( $previous_date ) );
                        $previous_date = date_i18n( "Y-m-d", $time_slot_in );
                    }
                }

                $current_day = 1;

                while ( $current_day <= $number_of_days ) {
                    if ( $day_of_week === 7 ) {
                        $day_of_week = 0;
                        echo sprintf(  "</tr><tr class='%s'>", esc_attr( 'abs-week' ) );
                    }

                    $current_day_rel = str_pad( $current_day, 2, "0", STR_PAD_LEFT );
                    $month = str_pad( $month, 2, "0", STR_PAD_LEFT );
                    $current_date = "$year-$month-$current_day_rel";

                    $time_slot = $this->bookend_status( $current_date, $calendar_id );

                    if ( strtotime( $current_date ) == strtotime( $today_date ) ) {
                        $date = sprintf( "<td class='abs-data' data-calendar-id='%s' data-date='%s'><span class='abs-today-active abs-date %s'><span class='abs-number %s' title='%s Available' data-appointments-count='%s'>%s</span></span></td>",
                            esc_attr( $calendar_id ),
                            esc_attr( $current_date ),
                            esc_attr( $time_slot['class'] ),
                            esc_attr( $time_slot['count'] != 0 ? 'abs-tooltip' : '' ),
                            esc_attr( $time_slot['count'] ),
                            esc_attr( $time_slot['count'] ),
                            esc_html( $current_day )
                        );
                        echo wp_kses_post( $date );
                    } elseif ( strtotime( $current_date ) < strtotime( $today_date ) ) {
                        $date = sprintf( "<td><span class='abs-date'><span class='abs-number'>%s</span></span></td>", esc_html( $current_day ) );
                        echo wp_kses_post( $date );
                    } else {
                        $date = sprintf( "<td class='abs-data' data-calendar-id='%s' data-date='%s'><span class='abs-date %s'><span class='abs-number %s' title='%s Available' data-appointments-count='%s'>%s</span></span></td>",
                            esc_attr( $calendar_id ),
                            esc_attr( $current_date ),
                            esc_attr( $time_slot['class'] ),
                            esc_attr( $time_slot['count'] != 0 ? 'abs-tooltip' : '' ),
                            esc_attr( $time_slot['count'] ),
                            esc_attr( $time_slot['count'] ),
                            esc_html( $current_day )
                        );
                        echo wp_kses_post( $date );
                    }

                    $current_day++;
                    $day_of_week++;
                }

                if ( $day_of_week != 7 ) {
                    $remaining_days = 7 - $day_of_week;
                    $count = 1;

                    for ( $i = 0; $i < $remaining_days; $i++ ) {
                        $next_year_month = date_i18n( 'Y-m', mktime( 0, 0, 0, $month + 1, 1, $year ) );
                        $previous_date = "$next_year_month-$count";
                        $time_slot = $this->bookend_status( $previous_date, $calendar_id );

                        if ( strtotime( $previous_date ) == strtotime( $today_date ) ) {
                            $date = sprintf( "<td class='abs-data' data-calendar-id='%s'  data-date='%s'><span class='abs-date %s'><span class='abs-number %s' title='%s Available' data-appointments-count='%s'>%s</span></span></td>",
                                esc_attr( $calendar_id ),
                                esc_attr( $previous_date ),
                                esc_attr( $time_slot['class'] ),
                                esc_attr( $time_slot['count'] != 0 ? 'abs-tooltip' : '' ),
                                esc_attr( $time_slot['count'] ),
                                esc_attr( $time_slot['count'] ),
                                esc_html( $count )
                            );
                            echo wp_kses_post( $date );
                        } else {
                            $date = sprintf( "<td class='abs-data' data-calendar-id='%s'  data-date='%s'><span class='abs-date %s'><span class='abs-number %s' title='%s Available' data-appointments-count='%s'>%s</span></span></td>",
                                esc_attr( $calendar_id ),
                                esc_attr( $previous_date ),
                                esc_attr( $time_slot['class'] ),
                                esc_attr( $time_slot['count'] != 0 ? 'abs-tooltip' : '' ),
                                esc_attr( $time_slot['count'] ),
                                esc_attr( $time_slot['count'] ),
                                esc_html( $count )
                            );
                            echo wp_kses_post( $date );
                        }

                        $count++;
                    }
                }
                ?>
            </tr>
            </tbody>
        </table>
        <!-- The Modal -->
        <div class="abs-booked-modal abs-loading-book-form <?php echo esc_attr( 'abs-book-form-' . $calendar_id ); ?>">
            <img src="<?php echo esc_url( ABS_PLUGIN_URL . 'assets/images/loading.gif' ); ?>"
                 alt="<?php echo esc_attr__( 'Loading Booking Form', 'appointment-booking' ); ?>">
            <!-- Modal content -->
            <div class="abs-booked-modal-content">

            </div>
        </div>

        <?php
        $calender = ob_get_contents();
        ob_end_clean();
        return $calender;
    }

    /**
     * Get all time slots
     *
     * @return int[]
     */
    public function get_time_slots_all( $calendar_id ) {
        global $wpdb;

        $items = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}abs_time_slots WHERE space_available != 0 AND status = 0 AND calendar_id = %s ORDER by %s %s",
                $calendar_id,
                'id',
                'DESC'
            )
        );

        $default = array(
            'count'  => 0,
            'time'   => '',
        );

        $data = array( 'monday' => $default, 'tuesday' => $default, 'wednesday' => $default, 'thursday' => $default, 'friday' => $default, 'saturday' => $default, 'sunday' => $default );

        foreach ( $items as $item ) {
            $day_name = $item->day_name;
            $day_count = $item->space_available;
            $total_day_count = ( $data[ $day_name ]['count'] + $day_count );
            $data[ $day_name ] = array(
                'count'  => $total_day_count,
                'time'   => $item->booking_time,
            );

        }

        return $data;
    }

    /**
     * Get custom time slots
     *
     * @param null $start_date
     * @param null $first_day_of_month
     * @return array
     */
    public function get_custom_time_slots_all( $start_date = null, $first_day_of_month = null, $calendar_id ) {
        global $wpdb;

        $fm_start_date = date_i18n( 'Y-m-d', strtotime( $start_date ) );
        $items = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}abs_custom_time_slots WHERE ( start_date >= %s OR end_date >= %s OR end_date = '' ) AND calendar_id = %s",
                $fm_start_date,
                $fm_start_date,
                $calendar_id
            )
        );

        $appointments = array();

        foreach ( $items as $row ) {

            $start_time = strtotime( date_i18n( 'Y-m-d', strtotime( $start_date ) ) );
            $end_time = strtotime( "+7 day", strtotime( date_i18n( 'Y-m-t', $first_day_of_month ) ) );

            if ( !empty( $row->end_date ) ) {
                if ( $end_time >= strtotime( $row->end_date ) ) {
                    $end_time = strtotime( $row->end_date );
                }
            }

            for ( $i = $start_time; $i <= $end_time; $i = $i + 86400 ) {
                $date = date_i18n( 'Y-m-d', $i );
                if ( $date >= $row->start_date ) {
                    if ( (int)$row->slots_type === 2 ) {
                        if ( isset( $appointments['close'][ $date ] ) ) {
                            $appointments['close'][ strtotime( $date ) ] = 0;
                        } else {
                            $appointments['close'][ strtotime( $date ) ] = 0;
                        }
                    } else {
                        if ( isset( $appointments['open'][ $date ] ) ) {
                            $appointments['open'][ strtotime( $date ) ] = array(
                                'count' => $appointments['open'][ $date ]['count'] + $row->space_available,
                                'time'  => $row->booking_time
                            );
                        } else {
                            $appointments['open'][ strtotime( $date ) ] = array(
                                'count' => $row->space_available,
                                'time'  => $row->booking_time
                            );
                        }
                    }
                }
            }

        }

        return $appointments;
    }

    /**
     * Available appointments
     *
     * @param $date
     * @return array
     */
    public function bookend_status( $date, $calendar_id ) {
        $time_slot = array(
            'class' => '',
            'count' => 0,
        );

        $time_format = get_option( 'time_format' );
        $current_day_name = strtolower( date( "l", strtotime( $date ) ) );
        $time_slots = $this->time_slots;
        $custom_time_slots = $this->custom_time_slots;

        if ( isset( $custom_time_slots['close'][ strtotime( $date ) ] ) ) {
            return $time_slot;
        }

        if ( $time_slots[ $current_day_name ]['count'] !== 0 ) {
            $booking_time = explode( '-', $time_slots[ $current_day_name ]['time'] );
            $now_time = date_i18n( 'Y-m-d ' . $time_format );
            $appointment_time = date_i18n( 'Y-m-d ' . $time_format, strtotime( $date . $booking_time['0'] ) );

            if ( strtotime( $appointment_time  ) >= strtotime( $now_time  ) ) {
                $remove_status = apply_filters( "abs_time_slot_remove", false, $appointment_time, $calendar_id );
                if ( $remove_status == false ) {
                    $time_slot = array(
                        'class' => 'abs-time-slots-active',
                        'count' => $time_slots[ $current_day_name ]['count']
                    );
                }
            }
        }

        if ( isset( $custom_time_slots['open'][ strtotime( $date ) ]['count'] ) ) {

            $custom_booking_time = explode( '-', $custom_time_slots['open'][ strtotime( $date ) ]['time'] );
            $custom_now_time = date_i18n( 'Y-m-d ' . $time_format );
            $custom_appointment_time = date_i18n( 'Y-m-d ' . $time_format, strtotime( $date . $custom_booking_time['0'] ) );

            if ( strtotime( $custom_appointment_time  ) >= strtotime( $custom_now_time  ) ) {
                $remove_status = apply_filters( "abs_time_slot_remove", false, $custom_appointment_time, $calendar_id );
                if ( $remove_status == false ) {
                    $time_slot = array(
                        'class' => 'abs-time-slots-active',
                        'count' => $time_slot['count'] + $custom_time_slots['open'][ strtotime( $date ) ]['count'],
                    );
                }
            }
        }

        return $time_slot;
    }

    /**
     * Get single date appointments
     */
    public function get_appointments() {
        $date = sanitize_text_field( $_POST['date'] );
        $calendar_id = sanitize_text_field( $_POST['calendar_id'] );

        $this->get_single_date_appointments( $calendar_id, $date );
        wp_die();
    }

    /**
     * Get single date appointments list
     */
    public function get_single_date_appointments( $calendar_id, $date ) {
        global $wpdb;

        $fm_start_date = date_i18n( 'Y-m-d', strtotime( $date ) );
        $current_day_name = strtolower( date( "l", strtotime( $date ) ) );
        $time_format = get_option( 'time_format' );

        $items = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}abs_custom_time_slots WHERE ( start_date >= %s OR end_date >= %s OR end_date = '' ) AND ( space_available != 0 AND status = 0 ) AND calendar_id = %s",
                $fm_start_date,
                $fm_start_date,
                $calendar_id
            )
        );

        $day_name_items = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}abs_time_slots WHERE  day_name = %s AND space_available != 0 AND status = 0 AND calendar_id = %s",
                $current_day_name,
                $calendar_id
            )
        );
        ?>
        <div class="abs-time-solat-list">
            <h2><?php echo esc_html__( 'Available Appointments on', 'appointment-booking' ); ?>
                <strong><?php echo esc_html( date_i18n( 'F d, Y', strtotime( $date ) ) ); ?></strong></h2>
            <?php
            foreach ( $items as $row ) {
                $abs_calendar_general = get_option( 'abs_calendar_general' . $row->calendar_id );

                $booking_time = explode( '-', $row->booking_time );
                $now_time = date_i18n( 'Y-m-d ' . $time_format );
                $appointment_time = date_i18n( 'Y-m-d ' . $time_format, strtotime( $fm_start_date . $booking_time['0'] ) );

                if ( strtotime( $appointment_time  ) >= strtotime( $now_time  ) ) {
                    $remove_status = apply_filters( "abs_time_slot_remove", false, $appointment_time, $calendar_id );
                    if ( $remove_status == false ) {
                        ?>
                        <div class="abs-bookme-timeslot">
                            <div class="abs-bookme-timeslot-left">
                                <h5><?php echo esc_html( $row->title ); ?></h5>
                                <?php
                                $booking_time = explode( '-', $row->booking_time );
                                $start_booking_time = date_i18n( $time_format, strtotime( $booking_time['0'] ) );
                                $end_booking_time = isset( $booking_time['1'] ) ? ' – ' . date_i18n( $time_format, strtotime( $booking_time['1'] ) ) : '';

                                if ( strtolower( 'allday' ) === strtolower( $booking_time['0'] ) ) {
                                    $booking_time = 'All Day';
                                } else {
                                    $booking_time = $start_booking_time . $end_booking_time;
                                }
                                ?>
                                <p class="abs-bookme-time"><span class="dashicons dashicons-clock"></span>
                                    <span class="abs-bookme-time-area"><?php esc_html_e( $booking_time ); ?></span>
                                </p>
                                <?php
                                if ( !isset( $abs_calendar_general['display_available_time_slots'] ) ) {
                                    ?>
                                    <p class="abs-bookme-spaces"><?php echo sprintf( '%s %s', esc_html( $row->space_available ), esc_html__( 'SPACES AVAILABLE', 'appointment-booking' ) ); ?></p>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="abs-bookme-timeslot-right">
                                <a href="" class="abs-bookme-timeslot-button"
                                   data-calendar-id="<?php echo esc_attr( $row->calendar_id ); ?>"
                                   data-slot-date="<?php echo esc_attr( $date ); ?>"
                                   data-slot-type="custom_time_slot"
                                   data-slot-id="<?php echo esc_attr( $row->id ); ?>"><?php esc_html_e( 'Book Now', 'appointment-booking' ); ?>
                                </a>
                            </div>
                        </div>
                        <?php
                    }

                }
            }

            foreach ( $day_name_items as $row ) {
                $abs_calendar_general = get_option( 'abs_calendar_general' . $row->calendar_id );

                $booking_time = explode( '-', $row->booking_time );
                $now_time = date_i18n( 'Y-m-d ' . $time_format );
                $appointment_time = date_i18n( 'Y-m-d ' . $time_format, strtotime( $fm_start_date . $booking_time['0'] ) );

                if ( strtotime( $appointment_time  ) >= strtotime( $now_time  ) ) {
                    $remove_status = apply_filters( "abs_time_slot_remove", false, $appointment_time, $calendar_id );
                    if ( $remove_status == false ) {
                        ?>
                        <div class="abs-bookme-timeslot">
                            <div class="abs-bookme-timeslot-left">
                                <h5><?php echo esc_html( $row->title ); ?></h5>
                                <?php
                                $booking_time = explode( '-', $row->booking_time );
                                $start_booking_time = date_i18n( $time_format, strtotime( $booking_time['0'] ) );
                                $end_booking_time = isset( $booking_time['1'] ) ? ' – ' . date_i18n( $time_format, strtotime( $booking_time['1'] ) ) : '';

                                if ( strtolower( 'allday' ) === strtolower( $booking_time['0'] ) ) {
                                    $booking_time = 'All Day';
                                } else {
                                    $booking_time = $start_booking_time . $end_booking_time;
                                }
                                ?>
                                <p class="abs-bookme-time">
                                    <span class="dashicons dashicons-clock"></span>
                                    <span class="abs-bookme-time-area"><?php echo esc_html( $booking_time ); ?></span>
                                </p>
                                <?php
                                if ( ! isset( $abs_calendar_general['display_available_time_slots'] ) ) {
                                    ?>
                                    <p class="abs-bookme-spaces"><?php echo sprintf( '%s %s', esc_html( $row->space_available ), esc_html__( 'SPACES AVAILABLE', 'appointment-booking' ) ); ?></p>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="abs-bookme-timeslot-right">
                                <a href="" class="abs-bookme-timeslot-button" data-slot-date="<?php echo esc_html( $date ); ?>"
                                   data-slot-type="day_time_slot"
                                   data-calendar-id="<?php echo esc_attr( $row->calendar_id ); ?>"
                                   data-slot-id="<?php echo esc_attr( $row->id ); ?>"><?php esc_html_e( 'Book Now', 'appointment-booking' ); ?></a>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
        <?php
    }

    /**
     * Book from html contain
     */
    public function booked_form_contain() {
        global $wpdb;

        $slot_id = sanitize_text_field( $_POST['slot_id'] );
        $slot_type = sanitize_text_field( $_POST['slot_type'] );
        $date = sanitize_text_field( $_POST['date'] );

        if ( strtolower( $slot_type ) === strtolower( 'day_time_slot' ) ) {
            $table = $wpdb->prefix . 'abs_time_slots';
        } else {
            $table = $wpdb->prefix . 'abs_custom_time_slots';
        }

        $result = $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %s", $slot_id )
        );

        ?>
        <div class="abs-booked-header-modal">
            <h5><?php echo esc_html__( 'Book an Appointment ', 'appointment-booking' ); ?></h5>
            <span class="dashicons dashicons-no-alt abs-modal-close"></span>
        </div>
        <div class="abs-booked-body-modal">
            <div class="abs-booked-form-time-solat">
                <h5><?php echo esc_html( $result->title ); ?></h5>
                <?php
                $time_format = get_option( 'time_format' );
                $date = date_i18n( 'M d, Y', strtotime( $date ) );
                if ( strtolower( 'allday' ) === strtolower( str_replace( '-', '', $result->booking_time ) ) ) {
                    $date_text = "All day on " . $date;
                } else {
                    $booking_time = explode( '-', $result->booking_time );
                    $start_booking_time = date_i18n( $time_format, strtotime( $booking_time['0'] ) );
                    $end_booking_time = isset( $booking_time['1'] ) ? ' – ' . date_i18n( $time_format, strtotime( $booking_time['1'] ) ) : '';
                    $date_text = $date . ' at ' . $start_booking_time . $end_booking_time;
                }
                ?>
                <p>
                    <span class="dashicons dashicons-calendar-alt"></span><span> <?php echo esc_html( $date_text ); ?> </span>
                </p>
                <?php
                if ( $result->product != 0 ) {
                    $product_price = get_post_meta( $result->product, '_sale_price', true );
                    echo sprintf(
                        '<h6 class="abs-booking-price">%s %s %s</h6>',
                        esc_html__( 'Booking Price:', 'appointment-booking' ),
                        esc_html( $product_price ),
                        esc_html( get_woocommerce_currency() )
                    );
                }
                ?>
            </div>
            <?php
            $calendar = $wpdb->get_row(
                $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}abs_calendar WHERE id = %s", $result->calendar_id )
            );

            if ( is_user_logged_in() ) {
                $this->login_user_booking_form( $result, $date, $slot_type );
            } else {
                if ( strtolower( 'Registered Booking' ) === strtolower( $calendar->booking_type ) ) {
                    $this->login_booking_form( $result, $date, $slot_type );
                } else {
                    $this->guest_booking_form( $result, $date, $slot_type );
                }
            }
            ?>
        </div>
        <?php
        wp_die();
    }

    /**
     * Login form for Appointments
     *
     * @param $result
     * @param $date
     * @param $slot_type
     */
    public function login_booking_form( $result, $date, $slot_type )  {
        ?>
        <form action="" class="abs-book-login-area" method="GET">
            <div class="abs-book-form">
                <h4><?php esc_html_e( 'Please sign in:', 'appointment-booking' ); ?>:</h4>
                <div class="abs-book-form-group">
                    <label><?php esc_html_e( 'Email', 'appointment-booking' ); ?></label>
                    <input class="abs-book-email" type="text" value="" required>
                </div>
                <div class="abs-book-form-group">
                    <label><?php esc_html_e( 'Password', 'appointment-booking' ); ?></label>
                    <input class="abs-book-password" type="password" value="" required>
                </div>
            </div>
            <div class="abs-book-form-btn">
                <div class="abs-book-success-massage">
                </div>
                <button class="ab-book-button abs-book-login-button abs-ladda-button abs-slide-right"
                        data-calendar-id="<?php echo esc_attr(  $result->calendar_id ); ?>"
                        data-slot-date="<?php echo esc_attr( $date ); ?>"
                        data-slot-type="<?php echo esc_attr( $slot_type ); ?>"
                        data-slot-id="<?php echo esc_attr( $result->id ); ?>">
                    <span class="label"><?php esc_html_e( 'Login', 'appointment-booking' ); ?></span>
                    <span class="spinner"></span>
                </button>
                <?php
                $register_page = get_option( 'abs_user_register_page' );
                $link = get_permalink( $register_page );
                ?>
                <a href="<?php echo esc_url( $link ); ?>" class="ab-book-button-two abs-book-register-link"><?php esc_html_e( 'Register', 'appointment-booking' ); ?></a>
            </div>
        </form>
        <?php
    }

    /**
     * Guest booking form
     *
     * @param $result
     * @param $date
     * @param $slot_type
     */
    public function guest_booking_form( $result, $date, $slot_type ) {
        $abs_calendar_general = get_option( 'abs_calendar_general' . $result->calendar_id );
        ?>
        <form action="" class="abs-book-form-area" method="GET">
            <div class="abs-book-form">
                <h4><?php esc_html_e( 'Your Information', 'appointment-booking' ); ?>:</h4>
                <?php
                if ( ! isset( $abs_calendar_general['display_name'] ) ) {
                    ?>
                    <div class="abs-book-form-group">
                        <label><?php esc_html_e( 'Name', 'appointment-booking' ); ?></label>
                        <input class="abs_book_name" type="text" value="" required>
                    </div>
                    <?php
                }
                ?>
                <?php
                if ( ! isset( $abs_calendar_general['display_phone_number'] ) ) {
                    ?>
                    <div class="abs-book-form-group">
                        <label><?php esc_html_e( 'Phone', 'appointment-booking' ); ?></label>
                        <input class="abs-book-phone" type="text" value="" required>
                    </div>
                    <?php
                }
                ?>
                <?php
                if ( ! isset( $abs_calendar_general['display_email'] ) ) {
                    ?>
                    <div class="abs-book-form-group">
                        <label><?php esc_html_e( 'Email', 'appointment-booking' ); ?></label>
                        <input class="abs-book-email" type="email" value="" required>
                    </div>
                    <?php
                }

                $this->create_custom_fields( $result->calendar_id );
                ?>
            </div>
            <div class="abs-book-form-btn">
                <div class="abs-book-success-massage">
                </div>
                <button class="ab-book-button abs-book-form-btn-success abs-ladda-button abs-slide-right"
                        data-slot-date="<?php echo esc_attr( $date ); ?>"
                        data-slot-type="<?php echo esc_attr( $slot_type ); ?>"
                        data-slot-id="<?php echo esc_attr( $result->id ); ?>"><span
                            class="label"><?php esc_html_e( 'Book Appointment ', 'appointment-booking' ); ?></span>
                    <span class="spinner"></span>
                </button>
                <button class="ab-book-button-two abs-book-form-cancel-btn"><?php esc_html_e( 'Cancel', 'appointment-booking' ); ?></button>
            </div>
        </form>
        <?php
    }

    /**
     * Login user booking form
     *
     * @param $result
     * @param $date
     * @param $slot_type
     */
    public function login_user_booking_form( $result, $date, $slot_type ) {
        ?>
        <form action="" class="abs-book-form-area" method="POST">
            <div class="abs-book-form">
                <?php
                $this->create_custom_fields( $result->calendar_id );
                ?>
            </div>
            <div class="abs-book-form-btn">
                <div class="abs-book-success-massage">
                    <div class="abs-book-form">
                    </div>
                </div>
                <button class="ab-book-button abs-book-form-btn-success abs-ladda-button abs-slide-right"
                        data-slot-date="<?php echo esc_attr( $date ); ?>"
                        data-slot-type="<?php echo esc_attr( $slot_type ); ?>"
                        data-slot-id="<?php echo esc_attr( $result->id ); ?>">
                    <span class="label"><?php esc_html_e( 'Book Appointment ', 'appointment-booking' ); ?></span>
                    <span class="spinner"></span>
                </button>
                <button class="ab-book-button-two abs-book-form-cancel-btn"><?php esc_html_e( 'Cancel', 'appointment-booking' ); ?></button>
            </div>
        </form>
        <?php
    }

    public function create_custom_fields( $id ) {
        $custom_fields = get_option( "abs_custom_fields" );
        foreach ( $custom_fields as $row ) {
            $name = str_replace( ' ', '_', strtolower( $row['label'] ) );
            if ( strtolower( $row['type'] ) === strtolower( 'text' ) ) {
                ?>
                <div class="abs-book-form-group">
                    <label><?php echo esc_html( $row['label'] ); ?></label>
                    <input class="abs-custom-field" type="text" name="<?php echo esc_attr( $name ); ?>" required>
                </div>
                <?php
            }

            if ( strtolower( $row['type'] ) === strtolower( 'drop_down' ) ) {
                ?>
                <div class="abs-book-form-group">
                    <label><?php echo esc_html( $row['label'] ); ?></label>
                    <select class="abs-custom-field" name="<?php echo esc_attr( $name ); ?>" required >
                        <?php
                        foreach ( $row['options'] as $singe ) {
                            echo sprintf(
                                '<option value="%s">%s</option>',
                                esc_attr( $singe['value']  ),
                                esc_html( $singe['value'] )
                            );
                        }
                        ?>
                    </select>
                </div>
                <?php
            }

            if ( strtolower( $row['type'] ) === strtolower( 'radio' ) ) {
                ?>
                <div class="abs-book-form-group">
                    <label><?php echo esc_html( $row['label'] ); ?></label>
                    <?php
                    foreach ( $row['options'] as $singe ) {
                        $value_name = str_replace( ' ', '_', strtolower( $singe['value']  ) );
                        ?>
                        <div class="abs-radio">
                            <input class="abs-custom-field" type="radio" id="<?php echo esc_attr( $value_name ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $singe['value'] ); ?>">
                            <label for="<?php echo esc_attr( $value_name ); ?>"><?php echo esc_html( $singe['value'] ); ?></label>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }

            if ( strtolower( $row['type'] ) === strtolower( 'text_content' ) ) {
                ?>
                <div class="abs-book-form-group">
                    <label><?php echo esc_html( $row['label'] ); ?></label>
                    <textarea class="abs-custom-field" name="<?php echo esc_attr( $name ); ?>" id="" cols="30" rows="4"></textarea>
                </div>
                <?php
            }
        }
    }
}