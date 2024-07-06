<?php


namespace Appointment\Booking;


/**
 * Class Frontend
 * @package Appointment \Booking
 */
class Frontend {

    /**
     * Frontend constructor.
     */
    public function __construct() {
        new Frontend\Wc\Appointment_List_Page();
    }
}