<?php


namespace Appointment\Booking\Frontend\Wc;

/**
 * Class Appointment_List_Page
 *
 * @package Appointment \Booking\Frontend\woocommerce
 */
class Appointment_List_Page {

    /**
     * Appointment_List_Page constructor.
     */
    public function __construct() {
        add_filter ( 'woocommerce_account_menu_items', array( $this, 'create_menu_page' ), 40 );
        add_action( 'init', array( $this, 'add_endpoint' ) );
        add_action( 'init', array( $this, 'new_page_flush_rewrite_rules' ) );
        add_action( 'woocommerce_account_appointment-list_endpoint', array( $this, 'page_contain' ) );
    }

    /**
     * Create WooCommerce account page add new li
     *
     * @param $menu_links
     * @return array|string[]
     */
    public function create_menu_page( $menu_links ){
        $menu_links = array_slice( $menu_links, 0, 1, true )
            + array( 'appointment-list' => 'Appointment  list' )
            + array_slice( $menu_links, 1, NULL, true );

        return $menu_links;
    }

    /**
     * Add endpoint
     */
    public function add_endpoint() {
        add_rewrite_endpoint( 'appointment-list', EP_PAGES );
    }

    /**
     * Flush rewrite rules
     */
    public function new_page_flush_rewrite_rules() {
        add_rewrite_endpoint( 'appointment-list', EP_ROOT | EP_PAGES );
        flush_rewrite_rules();
    }

    /**
     * Page contain
     */
    public function page_contain() {
        global $wpdb;
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'easy-appointments' AND post_author = %s ORDER BY id DESC",
                get_current_user_id()
            )
        );
        ?>
        <table class="abs-appointment-list">
            <thead>
            <tr>
                <th><?php esc_html_e( 'Name', 'appointment-booking' ); ?></th>
                <th><?php esc_html_e( 'Phone', 'appointment-booking' ); ?></th>
                <th><?php esc_html_e( 'Appointment  Time Slot', 'appointment-booking' ); ?></th>
                <th><?php esc_html_e( 'Status', 'appointment-booking' ); ?></th>
                <?php
                if ( strtolower( get_option( 'abs_enable_zoom' ) ) === strtolower( 'yes'  ) ) {
                    ?>
                    <th><?php esc_html_e( 'Zoom join link', 'appointment-booking' ); ?></th>
                    <th><?php esc_html_e( 'Zoom password', 'appointment-booking' ); ?></th>
                    <?php
                }
                ?>
            </tr>
            </thead>
            <tbody>
            <?php
            $time_format = get_option( 'time_format' );

            foreach ( $results as $row ) {
                $date = date_i18n( 'M d, Y', get_post_meta( $row->ID, 'start_date', true ) );
                ?>
                <tr>
                    <td>
                        <?php echo esc_html( $row->post_title );?>
                    </td>
                    <td>
                        <?php echo esc_html( get_post_meta( $row->ID, 'phone', true ) );?>
                    </td>
                    <td>
                        <time>
                            <?php
                            if( ! empty( get_post_meta( $row->ID, 'end_date', true ) ) ) {
                                echo sprintf(
                                    "from %s to %s on %s",
                                    date_i18n( $time_format, get_post_meta( $row->ID, 'start_date', true ) ),
                                    date_i18n( $time_format, get_post_meta( $row->ID, 'end_date', true ) ) ,
                                    $date
                                );
                            } else {
                                echo sprintf(
                                    "%s (All day)",
                                    $date
                                );
                            }
                            ?>
                        </time>
                    </td>
                    <td>
                        <?php
                        $appointment_type = empty( get_post_meta( $row->ID, 'order_id', true ) ) ? 'Free' : 'Paid';
                        if ( strtolower( $row->post_status ) === strtolower( 'eb-pending' ) ) {
                            esc_html_e( 'Pending (Type:' . $appointment_type . ')', 'appointment-booking' );
                        } elseif ( strtolower( $row->post_status ) === strtolower( 'eb-approve' ) ) {
                            esc_html_e( 'Approve (Type:' . $appointment_type . ')', 'appointment-booking' );
                        } elseif ( strtolower( $row->post_status ) === strtolower( 'eb-complete' ) ) {
                            esc_html_e( 'Complete (Type:' . $appointment_type . ')', 'appointment-booking' );
                        }
                        ?>
                    </td>
                    <?php
                    if ( strtolower( get_option( 'abs_enable_zoom' ) ) === strtolower( 'yes'  ) ) {
                        ?>
                        <td>
                            <?php echo esc_html( get_post_meta( $row->ID, 'zoom_join_link', true ) );?>
                        </td>
                        <td>
                            <?php echo esc_html( get_post_meta( $row->ID, 'zoom_password', true ) );?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
        <?php
    }
}