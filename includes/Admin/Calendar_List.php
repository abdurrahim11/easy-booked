<?php


namespace Appointment\Booking\Admin;

if ( !class_exists( 'WP_List_Table' ) ) {
    require_once 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class Calendar_List
 *
 * @package Appointment \Booking\Admin
 */
class Calendar_List extends \WP_List_Table {

    /**
     * Calendar_List constructor.
     */
    public function __construct() {
        $args = array(
            'singular' => esc_html( 'Easy Booked', 'appointment-booking' ),
            'plural'   => esc_html( 'Easy Booked', 'appointment-booking' ),
            'ajax'     => false,
        );

        parent::__construct( $args );
    }

    /**
     * @return array|string[]
     */
    public function get_columns() {
        return array(
            'cb'                  => '<input type="checkbox">',
            'name'                => esc_html__( 'Name', 'appointment-booking' ),
            'booking_type'        => esc_html__( 'Booking Type', 'appointment-booking' ),
            'appointment_type'    => esc_html__( 'Appointment  Type','appointment-booking' ),
            'appointment_default' => esc_html__( 'New Appointment  Default','appointment-booking' ),
            'calendar_shortcode'  => esc_html__( 'Shortcode','appointment-booking' )
        );
    }

    /**
     * @param array|object $item
     * @param string $column_name
     * @return string|void
     */
    protected function column_default( $item, $column_name ) {
        return isset( $item->$column_name ) ? $item->$column_name : '';
    }

    /**
     * @param array|object $item
     * @return string|void
     */
    public function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="calendar_id[]" value="%d">',
            esc_attr( $item->id )
        );
    }

    /**
     * @param $item
     * @return string
     */
    public function column_name( $item ) {
        $actions = array();

        $actions['edit'] = sprintf(
            '<a href="%s">%s</a>',
            esc_url( admin_url( 'admin.php?page=calendar-manage&action=edit&id=' . $item->id ) ),
            esc_html__( 'Edit', 'appointment-booking' )
        );

        $actions['delete'] = sprintf(
            '<a href="%s"  class="submitdelete abs-calendar-delete">%s</a>',
            wp_nonce_url( admin_url( 'admin-post.php?action=abs-calendar-delete&id=' . $item->id ), 'abs-calendar-delete' ),
            esc_html__( 'Delete', 'appointment-booking' )
        );

        return sprintf(
            "<a href='%s'><strong>%s</strong></a> %s",
            esc_url( admin_url( 'admin.php?page=calendar-manage&action=edit&id=' . $item->id ) ),
            esc_html( $item->name ),
            $this->row_actions( $actions )
        );
    }

    /**
     * Calendar shortcode
     *
     * @param $item
     * @return string
     */
    public function column_calendar_shortcode( $item ) {
        return esc_html( "[easy-booked calendar=$item->id]" );
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
        $per_page = 10;
        $current_page = $this->get_pagenum();
        $offset = ( $current_page - 1 ) * $per_page;

        $this->items = abs_get_calendar_list( array(
            'number' => $per_page,
            'offset' => $offset,
        ) );

        $this->set_pagination_args( array(
            "total_items" => abs_get_calendar_list_count(),
            "per_page"    => $per_page,
        ) );
    }

    /**
     * Add delete button
     *
     * @return array|string[]
     */
    public function get_bulk_actions() {
        $actions = array(
            'abs_delete_checkbox_calendar' => esc_html( 'Delete', 'appointment-booking' )
        );
        return $actions;
    }

    /**
     * Action process
     */
    public function process_bulk_action() {
        if( 'abs_delete_checkbox_calendar' === $this->current_action() ) {
            // security check!
            if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {

                $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
                $action = 'bulk-' . $this->_args['plural'];

                if ( ! wp_verify_nonce( $nonce, $action ) ) {
                    wp_die( 'Nope! Security check failed!' );
                }
            }

            foreach ( $_GET['calendar_id'] as $row ) {
                $id = sanitize_text_field( $row );
                global $wpdb;

                $wpdb->query(
                    $wpdb->prepare(
                        "DELETE FROM {$wpdb->prefix}abs_calendar WHERE id = %d",
                        $id
                    )
                );

            }

            wp_redirect( 'admin.php?page=calendar-manage' );
            exit();
        }
    }
}