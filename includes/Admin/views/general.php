<div class="abs-tab-contain">
    <h3><?php esc_html_e( 'General', 'appointment-booking' ); ?></h3>
    <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="POST">
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Calendar Name', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <input name="calendar_name" class="abs-field-pd" type="text" value="<?php echo esc_attr( $calendar->name ); ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Booking Type', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <select name="booking_type" class="abs-field-pd abs-booking-type">
                        <option <?php  selected( $calendar->booking_type, 'Guest Booking', true ); ?> value="Guest Booking"><?php esc_html_e( 'Guest Booking', 'appointment-booking' ); ?></option>
                        <option <?php  selected( $calendar->booking_type, 'Registered Booking', true ); ?> value="Registered Booking"><?php esc_html_e( 'Registered Booking', 'appointment-booking' ); ?></option>
                    </select>
                </td>
            </tr>
            <?php
            do_action( 'easy_booked_calendar_setting', $calendar );
            ?>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Appointment  Booking Redirect', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <p>
                        <?php
                        if ( empty( $this->get_options( 'redirect' ) ) ) {
                            ?>
                            <input checked type="radio" name="abs[redirect]" class="abs-redirect" value="on page refresh">
                            <?php
                        } else {
                            ?>
                            <input <?php checked( $this->get_options( 'redirect' ), 'on page refresh' ); ?> type="radio" name="abs[redirect]" class="abs-redirect" value="on page refresh">
                            <?php
                        }
                        ?>
                        <label for=""><?php esc_html_e( 'Refresh the calendar list after booking (No Redirect)', 'appointment-booking' ); ?></label>
                    </p>
                    <p>
                        <input  <?php checked( $this->get_options( 'redirect' ), 'refresh' ); ?> type="radio" name="abs[redirect]" class="abs-redirect" value="refresh">
                        <label for=""><?php esc_html_e( 'Choose a redirect page', 'appointment-booking' ); ?></label>
                    </p>
                    <select name="abs[redirect_page]" class="abs-page">
                        <option value=""><?php esc_html_e( 'Choose a page...', 'appointment-booking' ); ?></option>
                        <?php
                        foreach ( get_pages() as $row ) {
                            echo sprintf(
                                '<option %s value="%s">%s</option>',
                                selected( $this->get_options( 'redirect_page' ), $row->ID ),
                                esc_attr(  $row->ID ),
                                esc_html( $row->post_title )
                            );
                        }
                        ?>
                    </select>
                    <p class="description"><?php esc_html_e( 'Note: Refresh the calendar not allow for paid appointment Because must redirect to the checkout page to get the payment. If you select a page, the whole process will end and then it will redirect you to that page', 'appointment-booking' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Appointment  Limit', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <select name="abs[appointment_limit]" class="abs-field-pd">
                        <option value="0" selected=""><?php esc_html_e( 'No limit', 'appointment-booking' ); ?></option>
                        <?php
                        for ( $i = 1; $i <= 100; $i++ ) {
                            echo sprintf(
                                '<option %s value="%s">%s appointments </option>',
                                selected( $this->get_options( 'appointment_limit' ), $i ),
                                esc_attr( $i ),
                                esc_html( $i )
                            );
                        }
                        ?>
                    </select>
                    <p class="description"><?php esc_html_e( 'To prevent users from booking too many appointments, you can set an appointment limit.', 'appointment-booking' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'New Appointment  Default', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <select name="appointment_status" class="abs-field-pd abs-appointment-status" data-select="<?php esc_attr_e( $calendar->appointment_default === 'Payment Complete' ? 'yes' : 'no' ); ?>">
                        <option <?php selected( $calendar->appointment_default, 'Pending' ); ?> value="Pending"><?php esc_html_e( 'Set as Pending', 'appointment-booking' ); ?></option>
                        <option <?php selected( $calendar->appointment_default, 'Approve Immediately' ); ?> value="Approve Immediately"><?php esc_html_e( 'Approve Immediately', 'appointment-booking' ); ?></option>
                    </select>
                    <p class="description">
                        <?php esc_html_e( 'Would you like your appointment requests to go into a pending list or should they be approved immediately?', 'appointment-booking' ); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Start week', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <select name="abs[start_week]" class="abs-field-pd">
                        <option <?php selected( $this->get_options( 'start_week' ), 'Monday' ); ?> value="Monday"><?php esc_html_e( 'Monday', 'appointment-booking' ); ?></option>
                        <option  <?php selected( $this->get_options( 'start_week' ), 'Sunday' ); ?> value="Sunday"><?php esc_html_e( 'Sunday', 'appointment-booking' ); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e( 'Display Options', 'appointment-booking' ); ?></label>
                </th>
                <td>
                    <fieldset>
                        <label for="">
                            <input <?php checked( $this->get_options( 'display_name' ), 'display name' ); ?> name="abs[display_name]" type="checkbox" id="" value="display name">
                            <?php esc_html_e( 'Hide "Name" from booking form', 'appointment-booking' ); ?>
                        </label>
                        <br>
                        <label for="">
                            <input <?php checked( $this->get_options( 'display_email' ), 'display email' ); ?> name="abs[display_email]" type="checkbox" id="" value="display email">
                            <?php esc_html_e( 'Hide "Email" from booking form', 'appointment-booking' ); ?>
                        </label>
                        <br>
                        <label for="">
                            <input <?php checked( $this->get_options( 'display_phone_number' ), 'display phone number' ); ?> name="abs[display_phone_number]" type="checkbox" id="" value="display phone number">
                            <?php esc_html_e( ' Hide "Phone Number" from booking form', 'appointment-booking' ); ?>
                        </label>
                        <br>
                        <label for="">
                            <input <?php checked( $this->get_options( 'display_available_time_slots' ), 'display available time slots' ); ?> name="abs[display_available_time_slots]" type="checkbox" id="" value="display available time slots">
                            <?php esc_html_e( 'Hide the number of available time slots', 'appointment-booking' ); ?>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <?php
                    wp_nonce_field( 'abs-general' );
                    ?>
                    <input type="hidden" name="action" value="abs_general" >
                    <input type="hidden" class="abs-calendar-id" name="calendar_id" value="<?php echo esc_attr( $_GET['id'] ); ?>" >
                    <button class="abs-admin-btn"><?php esc_html_e( 'Save Change', 'appointment-booking' ); ?></button>
                </th>
            </tr>
            </tbody>
        </table>
    </form>
</div>