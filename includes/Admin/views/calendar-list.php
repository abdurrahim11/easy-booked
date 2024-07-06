<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e( 'Calendar', 'appointment-booking' ); ?></h1>
    <?php
    if ( abs_get_calendar_list_count() > apply_filters( 'easy_booked_calendar_list', 1 ) ) {
        ?>
        <a href="https://codecanyon.net/item/easy-booked-appointment-booking-and-scheduling-management-system-for-wordpress/31669527" class="page-title-action"><?php esc_html_e( 'Update', 'appointment-booking' ); ?></a>
        <?php
    } else {
        ?>
        <a href="" class="page-title-action abs-calendar-create"><?php esc_html_e( 'Add New', 'appointment-booking' ); ?></a>
        <?php
    }
    ?>
    <form id="plugins-filter" method="get">
        <input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
        <?php
        $table = new \Appointment\Booking\Admin\Calendar_List();
        $table->prepare_items();
        $table->display();
        ?>
    </form>
    <!-- The Modal -->
    <div class="abs-calendar-modal">
        <!-- Modal content -->
        <div class="abs-calendar-modal-content">
            <div class="abs-calendar-header-modal">
                <h5><?php esc_html_e( 'Create Appointment Calendar', 'appointment-booking' ); ?></h5>
                <span class="dashicons dashicons-no-alt abs-modal-close"></span>
            </div>
            <div class="abs-calendar-body-modal">
                <form action="<?php echo admin_url( 'admin-post.php' );?>" class="abs-calendar-form-area" method="POST">
                    <?php
                        wp_nonce_field( 'create-new-calendar' )
                    ?>
                    <input type="hidden" name="action" value="abs_create_new_calendar">
                    <div class="abs-calendar-form">
                        <div class="abs-calendar-form-group">
                            <label><?php esc_html_e( 'Calendar Name:', 'appointment-booking' ); ?></label>
                            <input name="name" class="abs_calendar_name" type="text" value="" required="">
                        </div>
                        <div class="abs-calendar-form-group">
                            <label><?php esc_html_e( 'Booking Type:', 'appointment-booking' ); ?></label>
                            <select name="booking_type" class="abs-field-pd">
                                <option value="Guest Booking"><?php esc_html_e( 'Guest Booking', 'appointment-booking' ); ?></option>
                                <option value="Registered Booking"><?php esc_html_e( 'Registered Booking', 'appointment-booking' ); ?></option>
                            </select>
                        </div>
                        <div class="abs-calendar-form-group">
                            <label><?php esc_html_e( 'Appointment type:', 'appointment-booking' ); ?></label>
                            <select name="appointment_free_premium" class="abs-field-pd abs-appointment-paid-free">
                                <option value="Free" selected=""><?php esc_html_e( 'Free', 'appointment-booking' ); ?></option>
                                <?php
                                do_action( 'easy_booked_appointment_type' );
                                ?>
                            </select>
                        </div>
                        <div class="abs-calendar-form-group">
                            <label><?php esc_html_e( 'New Appointment Default:', 'appointment-booking' ); ?></label>
                            <select name="appointment_status" class="abs-field-pd abs-appointment-status">
                                <option value="Pending"><?php esc_html_e( 'Set as Pending', 'appointment-booking' ); ?></option>
                                <option value="Approve Immediately"><?php esc_html_e( 'Approve Immediately', 'appointment-booking' ); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="abs-calendar-form-btn">
                        <div class="abs-calendar-success-massage">
                        </div>
                        <button class="abs-calendar-form-btn-success"><?php esc_html_e( 'Create new calendar', 'appointment-booking' ); ?></button>
                        <button class="abs-calendar-form-cancel-btn"><?php esc_html_e( 'Cancel', 'appointment-booking' ); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

