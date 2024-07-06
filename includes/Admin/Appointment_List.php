<?php


namespace Appointment\Booking\Admin;

if ( !class_exists( 'WP_List_Table' ) ) {
    require_once 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class Appointment_List
 *
 * @package Appointment\Booking\Admin
 */
class Appointment_List extends \WP_List_Table {

    /**
     * Appointment_List constructor.
     */
    public function __construct() {
        $args = array(
            'singular' => 'Easy Booked',
            'plural'   => 'Easy Booked',
            'ajax'     => false,
        );

        parent::__construct( $args );
    }

    /**
     * @return array|string[]
     */
    public function get_columns() {
        $columns = array(
            'cb'                  => '<input type="checkbox">',
            'id'                => esc_html__( 'ID / Name', 'appointment-booking' ),
            'phone'                => esc_html__( 'Phone', 'appointment-booking' ),
            'email'                => esc_html__( 'Email', 'appointment-booking' ),
            'appointments_time'   => esc_html__( 'Appointments Time', 'appointment-booking' ),
            'payment'   => esc_html__( 'Payment Status', 'appointment-booking' ),
            'status'   => esc_html__( 'Status', 'appointment-booking' ),
        );

        if ( strtolower( get_option( 'abs_enable_zoom' ) ) === strtolower( 'yes'  ) ) {
            $columns['zoom'] =  esc_html__( 'Zoom', 'appointment-booking' );
        }

        return $columns;
    }

    /**
     * @param array|object $item
     * @param string $column_name
     * @return string|void
     */
    protected function column_default( $item, $column_name ) {
        if ( get_post_meta( $item->ID, $column_name, true ) ) {
            return get_post_meta( $item->ID, $column_name, true );
        }

        return isset( $item->$column_name ) ? $item->$column_name : '';
    }

    /**
     *
     * @param array|object $item
     * @return string|void
     */
    public function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="appointment_id[]" value="%d">',
            esc_attr( $item->ID )
        );
    }

    /**
     * Appointments Time
     *
     * @param $item
     * @return string
     */
    public function column_appointments_time( $item ) {
        $time_format = get_option( 'time_format' );

        if ( ! empty( get_post_meta( $item->ID, 'end_date', true ) ) ) {
            $appointment_time = sprintf(
                "from %s to %s on %s",
                date_i18n( $time_format, get_post_meta( $item->ID, 'start_date', true ) ),
                date_i18n( $time_format, get_post_meta( $item->ID, 'end_date', true )  ),
                date_i18n( 'Y-m-d', get_post_meta( $item->ID, 'start_date', true )  )
            );
        } else {
            $appointment_time = sprintf(
                "%s (All day)",
                date_i18n( 'Y-m-d', get_post_meta( $item->ID, 'start_date', true )  )
            );
        }
        return $appointment_time;
    }

    /**
     * Payment status
     *
     * @param $item
     * @return string
     */
    public function column_payment( $item ) {

        if ( empty( get_post_meta( $item->ID, 'order_id', true ) ) ) {
            return esc_html__( 'Free Booking', 'appointment-booking' );
        } else {
            $order = wc_get_order( get_post_meta( $item->ID, 'order_id', true )  );
            return sprintf(
                '<a href="%s" target="_blank">%s ( %s )</a>',
                esc_url( $order->get_edit_order_url() ),
                esc_html__( 'Edit Order', 'appointment-booking' ),
                $order->get_status()
            );
        }
    }

    /**
     * Status
     *
     * @param $item
     * @return string
     */
    public function column_status( $item ) {

        switch ( $item->post_status ) {
            case 'eb-pending':
                return esc_html__( 'Pending', 'appointment-booking' );
                break;
            case 'eb-approve':
                return esc_html__( 'Approve', 'appointment-booking' );
            case 'eb-complete':
                return esc_html__( 'Complete', 'appointment-booking' );
        }
    }

    /**
     * Zoom link
     *
     * @param $item
     * @return string
     */
    public function column_zoom( $item ){
        return sprintf(
        '<a href="%s" target="_blank">%s</a>',
            esc_url( get_post_meta( $item->ID, 'zoom_join_link', true ) ),
            esc_html__( 'Join Zoom Meeting', 'appointment-booking' )
        );
    }

    /**
     * Show calendar column
     */
    public function prepare_items() {
        $column = $this->get_columns();
        $hidden = array();
        $sort_column = $this->get_sortable_columns();

        $this->_column_headers = array( $column, $hidden, $sort_column );
        $this->process_bulk_action();
        $this->views();
        $per_page = 10;
        $current_page = $this->get_pagenum();
        $offset = ( $current_page - 1 ) * $per_page;

        $search_term = isset( $_POST['s'] ) ? sanitize_text_field( $_POST['s'] ) : '';
        $status = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : '';

        $this->items = abs_get_appointment_list( array(
            'number' => $per_page,
            'offset' => $offset,
        ), $search_term, $status );

        $this->set_pagination_args( array(
            "total_items" => abs_get_appointment_list_count(),
            "per_page"    => $per_page,
        ) );
    }

    /**
     * Id
     *
     * @param $item
     * @return string
     */
    public function column_id( $item ) {
        $actions = array();

        if ( strtolower( $item->post_status ) === 'eb-pending' ) {
            $actions['approve_appointment'] = sprintf(
                '<a href="%s">%s</a>',
                wp_nonce_url( admin_url( 'admin-post.php?action=abs-appointments-approve&id=' . $item->ID ), 'abs-appointments-approve' ),
                esc_html__( 'Approve', 'appointment-booking' )
            );
        } else {
            $actions['pending'] = sprintf(
                '<a href="%s">%s</a>',
                wp_nonce_url( admin_url( 'admin-post.php?action=abs-appointments-pending&id=' . $item->ID ), 'abs-appointments-pending' ),
                esc_html__( 'Pending', 'appointment-booking' )
            );
        }

        $actions['delete'] = sprintf(
            '<a href="%s"  class="submitdelete abs-calendar-delete">%s</a>',
            wp_nonce_url( admin_url( 'admin-post.php?action=abs-appointments-delete&id=' . $item->ID ), 'abs-appointments-delete' ),
            esc_html__( 'Delete', 'appointment-booking' )
        );

        return $item->ID . ' / ' . $item->post_title . $this->row_actions( $actions );
    }

    /**
     * Add action button
     *
     * @return array|string[]
     */
    public function get_bulk_actions() {
        $actions = array(
            'abs_approve_checkbox_appointment' => esc_html( 'Approve', 'appointment-booking' ),
            'abs_pending_checkbox_appointment' => esc_html( 'Pending', 'appointment-booking' ),
            'abs_delete_checkbox_appointment' => esc_html( 'Delete', 'appointment-booking' )
        );
        return $actions;
    }

    /**
     * Process action
     */
    public function process_bulk_action() {
        if( 'abs_delete_checkbox_appointment' === $this->current_action() ) {
            // security check!
            if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

                $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
                $action = 'bulk-' . $this->_args['plural'];

                if ( ! wp_verify_nonce( $nonce, $action ) ) {
                    esc_html( 'Nope! Security check failed!', 'appointment-booking' );
                }
            }

            foreach ( $_POST['appointment_id'] as $row ) {
                $id = sanitize_text_field( $row );
                wp_delete_post( $id, true );
            }

            $location = $_SERVER['HTTP_REFERER'];
            wp_redirect( esc_url_raw( $location ) );
            exit();
        }

        if( 'abs_pending_checkbox_appointment' === $this->current_action() ) {
            // security check!
            if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

                $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
                $action = 'bulk-' . $this->_args['plural'];

                if ( ! wp_verify_nonce( $nonce, $action ) ) {
                    esc_html( 'Nope! Security check failed!', 'appointment-booking' );
                }
            }

            foreach ( $_POST['appointment_id'] as $row ) {
                $id = sanitize_text_field( $row );

                wp_update_post( array(
                    "ID" => $id,
                    "post_status" => "eb-pending",
                ) );
            }

            $location = $_SERVER['HTTP_REFERER'];
            wp_redirect( esc_url_raw( $location ) );
            exit();

        }

        if( 'abs_approve_checkbox_appointment' === $this->current_action() ) {
            // security check!
            if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

                $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
                $action = 'bulk-' . $this->_args['plural'];

                if ( ! wp_verify_nonce( $nonce, $action ) ) {
                    esc_html( 'Nope! Security check failed!', 'appointment-booking' );
                }
            }

            foreach ( $_POST['appointment_id'] as $row ) {
                $id = sanitize_text_field( $row );
                wp_update_post( array(
                        "ID" => $id,
                        "post_status" => "eb-approve",
                ) );

                do_action( 'abs_appointment_approval_email', $id );
            }

            $location = $_SERVER['HTTP_REFERER'];
            wp_redirect( esc_url_raw( $location ) );
            exit();

        }
    }

    /**
     * Search box
     *
     * @param string $text
     * @param string $input_id
     */
    public function search_box( $text, $input_id ) {
        if ( empty( $_REQUEST['s'] ) && ! $this->has_items() ) {
            return;
        }

        $input_id = $input_id . '-search-input';

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            echo sprintf(
                '<input type="hidden" name="orderby" value="%s" />',
                esc_attr( $_REQUEST['orderby'] )
            );
        }
        if ( ! empty( $_REQUEST['order'] ) ) {
            echo sprintf(
                '<input type="hidden" name="order" value="%s" />',
                esc_attr( $_REQUEST['order'] )
            );
        }
        if ( ! empty( $_REQUEST['post_mime_type'] ) ) {
            echo sprintf(
                '<input type="hidden" name="post_mime_type" value="%s" />',
                esc_attr( $_REQUEST['post_mime_type'] )
            );
        }
        if ( ! empty( $_REQUEST['detached'] ) ) {
            echo sprintf(
                '<input type="hidden" name="detached" value="%s" />',
                esc_attr( $_REQUEST['detached'] )
            );
        }
        ?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $text ); ?>:</label>
            <input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" />
            <?php submit_button( esc_attr( $text ), '', '', false, array( 'id' => 'search-submit' ) ); ?>
        </p>
        <?php
    }

    /**
     * Add quick action button
     *
     * @return array
     */
    protected function get_views() {
        $views = array();
        $hooks_type = ( ! empty( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : 'all' );

        $types = array(
            'all'          => esc_html__( 'All', 'appointment-booking' ),
            'approve'      => esc_html__( 'Approve', 'appointment-booking' ),
            'pending'      => esc_html__( 'Pending', 'appointment-booking' ),
        );

        foreach ( $types as $key => $value ) {
            $views[ $key ] = sprintf(
                "<a href='%s' class='%s'>%s</a>",
                esc_url( admin_url( 'admin.php?page=appointments&action=' . $key ) ),
                $hooks_type === $key ? esc_attr( 'current' ): '',
                $value
            );
        }

        return $views;
    }
}