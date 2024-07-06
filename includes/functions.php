<?php
/**
 * List of day name
 *
 * @return string[]
 */
function abs_days_of_week() {
    return array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );
}

/**
 * Generate Time slots
 *
 * @param string $interval
 * @return array
 */
function abs_get_times( $interval = '+5 minutes' ) {
    $output = array();

    $current = strtotime( '00:00' );
    $end = strtotime( '23:59' );
    $time_format = get_option( 'time_format' );

    while ( $current <= $end ) {
        $time = date_i18n( 'H:i', $current );
        $output[ $time ] = date_i18n( $time_format, $current );
        $current = strtotime( $interval, $current );
    }

    return $output;
}

/**
 * Get list calendar
 *
 * @param array $args
 * @return array|object|null
 */
function abs_get_calendar_list( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'number'  => 10,
        'offset'  => 0,
        'orderby' => 'id',
        'order'   => 'DESC',
    );

    $args = wp_parse_args( $args, $defaults );

    $result = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}abs_calendar where status = 0 ORDER BY %s %s
            LIMIT %d, %d",
            $args['orderby'],
            $args['order'],
            $args['offset'],
            apply_filters( 'easy_booked_calendar_list', 1 )
        )
    );

    return $result;
}

/**
 * Calendar count
 *
 * @return int
 */
function abs_get_calendar_list_count() {
    global $wpdb;
    return (int)$wpdb->get_var( "SELECT count(id) FROM {$wpdb->prefix}abs_calendar where status=0" );
}

/**
 * List of appointment
 *
 * @param array $args
 * @param string $search_term
 * @param string $status
 * @return array|object|null
 */
function abs_get_appointment_list( $args = array(), $search_term = '', $status = '' ) {
    global $wpdb;

    $appointment_status = '';
    if ( strtolower( $status ) === strtolower( 'pending' ) ) {
        $appointment_status = 'eb-pending';
    } elseif ( strtolower( $status ) === strtolower( 'approve' ) ) {
        $appointment_status = 'eb-approve';
    }

    $defaults = array(
        'number' => 10,
        'offset' => 0,
    );

    $args = wp_parse_args( $args, $defaults );

    if ( !empty( $search_term ) ) {
        $code_like = '%' . $wpdb->esc_like( $search_term ) . '%';
        $result = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT {$wpdb->prefix}posts.* FROM {$wpdb->prefix}posts  INNER JOIN {$wpdb->prefix}postmeta 
                      ON ( {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id ) WHERE ( 
                      {$wpdb->prefix}postmeta.meta_value LIKE %s
                      ) AND {$wpdb->prefix}posts.post_type = 'easy-appointments' 
                      GROUP BY {$wpdb->prefix}posts.ID ORDER BY {$wpdb->prefix}posts.post_date DESC",
                $code_like
            )
        );

        return $result;
    } else {

        if ( empty( $appointment_status ) ) {
            $query = $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'easy-appointments' ORDER BY id DESC LIMIT %d, %d",
                $args['offset'],
                $args['number']
            );
        } else {
            $query = $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'easy-appointments' AND post_status = %s ORDER BY id DESC LIMIT %d, %d",
                $appointment_status,
                $args['offset'],
                $args['number']
            );
        }

        $result = $wpdb->get_results( $query );
    }

    return $result;
}


/**
 * Appointment count
 *
 * @return int
 */
function abs_get_appointment_list_count() {
    global $wpdb;
    return (int)$wpdb->get_var( "SELECT count(ID) FROM {$wpdb->prefix}posts WHERE post_type = 'easy-appointments' ORDER BY id DESC" );
}

/**
 * Get all easy book WooCommerce product
 *
 * @return array
 */
function abs_easy_booked_product_id() {
    global $wpdb;

    $easy_booked_products = $wpdb->get_col(
        "SELECT DISTINCT posts.ID FROM {$wpdb->posts} AS posts
        INNER JOIN {$wpdb->postmeta} AS meta ON ( posts.ID = meta.post_id AND meta.meta_key = '_easy_booked' )
        WHERE posts.post_type = 'product'
        AND posts.post_status = 'publish'
        AND meta.meta_value = 'yes'"
    );

    return $easy_booked_products;
}

/**
 * Get product title and price
 *
 * @return array
 */
function abs_easy_booked_product() {
    $products = get_posts( array(
        'posts_per_page'   => -1,
        'post_type'        => 'product',
        'meta_query'       => array(
            array(
                'key'     => '_easy_booked',
                'compare' => '=',
                'value'   => 'yes'
            )
        ),
        'suppress_filters' => false
    ) );

    $product = array();
    foreach ( $products as $row ) {
        $product[ $row->ID ] = array(
            'title' => $row->post_title,
            'price' => get_post_meta( $row->ID, '_sale_price', true )
        );
    }

    return $product;
}

/**
 * Cron run time register
 *
 * @param $schedules
 * @return mixed
 */
function abs_cron_time_setup( $schedules ) {
    $schedules['abs_every_five_minute'] = array(
        'interval' => 300,
        'display'  => esc_html__( 'Easy Booked Every Five Minutes', 'appointment-booking' ),
    );
    return $schedules;
}

add_filter( 'cron_schedules', 'abs_cron_time_setup' );

/**
 * Email reminder option time
 *
 * @return array
 */
function abs_get_time_list() {
    $time = array(
        '0'      => esc_html__( 'At appointment time', 'appointment-booking' ),
        '5'      => esc_html__( '5 minutes before', 'appointment-booking' ),
        '10'     => esc_html__( '10 minutes before', 'appointment-booking' ),
        '15'     => esc_html__( '15 minutes before', 'appointment-booking' ),
        '30'     => esc_html__( '30 minutes before', 'appointment-booking' ),
        '45'     => esc_html__( '45 minutes before', 'appointment-booking' ),
        '60'     => esc_html__( '1 hour before', 'appointment-booking' ),
        '120'    => esc_html__( '2 hours before', 'appointment-booking' ),
        '180'    => esc_html__( '3 hours before', 'appointment-booking' ),
        '240'    => esc_html__( '4 hours before', 'appointment-booking' ),
        '300'    => esc_html__( '5 hours before', 'appointment-booking' ),
        '360'    => esc_html__( '6 hours before', 'appointment-booking' ),
        '720'    => esc_html__( '12 hours before', 'appointment-booking' ),
        '1440'   => esc_html__( '24 hours before', 'appointment-booking' ),
        '2880'   => esc_html__( '2 days before', 'appointment-booking' ),
        '4320'   => esc_html__( '3 days before', 'appointment-booking' ),
        '5760'   => esc_html__( '4 days before', 'appointment-booking' ),
        '7200'   => esc_html__( '5 days before', 'appointment-booking' ),
        '8640'   => esc_html__( '6 days before', 'appointment-booking' ),
        '10080'  => esc_html__( '1 week before', 'appointment-booking' ),
        '20160'  => esc_html__( '2 weeks before', 'appointment-booking' ),
        '30240'  => esc_html__( '3 weeks before', 'appointment-booking' ),
        '40320'  => esc_html__( '4 weeks before', 'appointment-booking' ),
        '60480'  => esc_html__( '6 weeks before', 'appointment-booking' ),
        '80640'  => esc_html__( '2 months before', 'appointment-booking' ),
        '120960' => esc_html__( '3 months before', 'appointment-booking' ),
    );
    return $time;
}

/**
 * Dynamic variable
 *
 * @return string[]
 */
function abs_get_appointment_variable_list() {
    return array( 'id', 'name', 'email', 'title', 'date', 'time', 'phone_number', 'zoom_join_link', 'zoom_password' );
}


function abs_user_profile_menu() {
    $menu = array(
        'upcomming' => array( 'far fa-calendar-alt', esc_html( 'Upcoming', 'appointment-booking' ) ),
        'history'   => array( 'far fa-calendar', esc_html( 'History', 'appointment-booking' ) ),
        'profile'   => array( 'far fa-user-circle', esc_html( 'Profile', 'appointment-booking' ) ),
    );

    $menu = apply_filters( 'abs_user_profile_menu', $menu );

    return $menu;
}

/**
 * Get appointment reminder list
 *
 * @return array
 */
function abs_get_appointment_reminder_id( $reminder_time ) {
    $time = strtotime( "+{$reminder_time} minutes", strtotime( date_i18n( "Y-m-d g:i:s A" ) ) );

    $args = array(
        'post_type' => 'easy-appointments',
        'post_status' => array( 'eb-pending' ),
        'meta_query' => array(
            'relation' => 'And',
            array(
                'key' => 'start_date',
                'value' => $time,
                'compare' => '<'
            ),
            array(
                'key' => 'appointment_reminder_email',
                'value' => 1,
                'compare' => '='
            )
        )
    );

    $query = new WP_Query($args);
    $appointments = array();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $appointments[] = get_the_ID() ;
        }
    }

    return $appointments;

}