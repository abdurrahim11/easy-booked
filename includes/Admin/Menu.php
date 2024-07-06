<?php


namespace Appointment\Booking\Admin;

/**
 * Class Menu
 * @package Appointment \Booking\Admin
 */
class Menu {

    /**
     * @var
     */
    private $calendar_manage;
    private $setting;
    private $appointments;

    /**
     * Menu constructor.
     */
    public function __construct( $calendar_manage, $setting, $appointments ) {
        $this->calendar_manage = $calendar_manage;
        $this->setting = $setting;
        $this->appointments = $appointments;
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_filter( 'plugin_action_links_appointment-booking-and-scheduling/appointment-booking-and-scheduling.php', array( $this, 'plugin_setting_link' ) );
    }

    /**
     * Crate admin menu
     */
    public function admin_menu() {
        $capability = 'manage_options';
        $parent_slug = 'calendar-manage';
        add_menu_page( esc_html__( 'Easy Booked', 'appointment-booking' ), esc_html__( 'Easy Booked', 'appointment-booking' ), $capability, 'calendar-manage', array( $this->calendar_manage, 'page' ), 'dashicons-calendar-alt', 57 );
        add_submenu_page( $parent_slug, esc_html__( 'Calendar Manage', 'appointment-booking' ), esc_html__( 'Calendar Manage', 'appointment-booking' ), $capability, $parent_slug, array( $this->calendar_manage, 'page' ) );
        add_submenu_page( $parent_slug, esc_html__( 'Appointments', 'appointment-booking' ), esc_html__( 'Appointments', 'appointment-booking' ), $capability, 'appointments', array( $this->appointments, 'page' ) );
        add_submenu_page( $parent_slug, esc_html__( 'Setting', 'appointment-booking' ), esc_html__( 'Setting', 'appointment-booking' ), $capability, 'setting', array( $this->setting, 'page' ) );
    }

    /**
     * Plugin setting page link
     *
     * @param $link
     * @return mixed
     */
    public function plugin_setting_link( $link ) {
        $new_link = sprintf("<a href='%s'>%s</a>","admin.php?page=calendar-manage",esc_html__("Setting","woo-address-auto-complete"));
        $link[]   = $new_link;
        return $link;
    }
}