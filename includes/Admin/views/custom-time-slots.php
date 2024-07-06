<div class="abs-tab-contain">
    <h3><?php esc_attr_e( 'Custom Time Slots', 'appointment-booking' ); ?></h3>
    <table class="form-table abs-custom-time-slots-form" role="presentation">
        <tbody>
        <tr>
            <th scope="row">
                <label><?php esc_attr_e( 'Start date', 'appointment-booking' ); ?></label>
            </th>
            <td>
                <input class="abs-custom-time-slots-start regular-text abs-field-pd" type="date" min="<?php echo esc_attr( date_i18n( 'Y-m-d' ) ); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php esc_attr_e( 'End date (Optional)', 'appointment-booking' ); ?></label>
            </th>
            <td>
                <input class="abs-custom-time-slots-end regular-text abs-field-pd" type="date" min="<?php echo esc_attr( date_i18n( 'Y-m-d' ) ); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label><?php esc_attr_e( 'Type', 'appointment-booking' ); ?></label>
            </th>
            <td>
                <select name="" class="abs-custom-slots-type regular-text abs-field-pd">
                    <option value="2"><?php esc_attr_e( 'Disable Appointments', 'appointment-booking' ); ?></option>
                    <option value="1"><?php esc_attr_e( 'Enable Appointments', 'appointment-booking' ); ?></option>
                </select>
            </td>
        </tr>
        <tr class="abs-enable-appointments">
            <th scope="row">
                <label><?php esc_attr_e( 'Title', 'appointment-booking' ); ?></label>
            </th>
            <td>
                <input class="abs-custom-timeslots-title regular-text abs-field-pd" type="text" placeholder="Title">
            </td>
        </tr>
        <tr class="abs-enable-appointments">
            <th scope="row">
                <label><?php esc_attr_e( 'Start time', 'appointment-booking' ); ?></label>
            </th>
            <td>
                <select name="start_time" class="abs-custom-timeslots-start-time regular-text abs-field-pd">
                    <option value=""><?php esc_attr_e( 'Start time ...', 'appointment-booking' ); ?></option>
                    <option value="allday"><?php esc_attr_e( 'All Day', 'appointment-booking' ); ?></option>
                    <?php
                    foreach ( $abs_get_times as $key => $value ) {
                        echo sprintf(
                            '<option value="%s">%s</option>',
                            esc_attr( $key ),
                            esc_html( $value )
                        );
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr class="abs-enable-appointments">
            <th scope="row">
                <label><?php esc_attr_e( 'End time', 'appointment-booking' ); ?></label>
            </th>
            <td>
                <select name="end_time" class="abs-custom-time-slots-end-time regular-text abs-field-pd">
                    <option value=""><?php esc_attr_e( 'End time ...', 'appointment-booking' ); ?></option>
                    <?php
                    foreach ( $abs_get_times as $key => $value ) {
                        echo sprintf(
                            '<option value="%s">%s</option>',
                            esc_attr( $key ),
                            esc_html( $value )
                        );
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr class="abs-enable-appointments">
            <th scope="row">
                <label><?php esc_attr_e( 'Spaces available', 'appointment-booking' ); ?></label>
            </th>
            <td>
                <select name="count" class="abs-custom-time-slots-count regular-text abs-field-pd">
                    <option value=""><?php esc_attr_e( 'Spaces available', 'appointment-booking' ); ?></option>
                    <?php
                    for ( $i = 1; $i <= 100; $i++ ) {
                        echo sprintf(
                            '<option value="%s">%s</option>',
                            esc_attr( $i ),
                            esc_html( $i )
                        );
                    }
                    ?>
                </select>
            </td>
        </tr>
        <?php
        do_action( 'easy_booked_custom_time_slots_product', $calendar );
        ?>
        <tr>
            <th scope="row">
                <button class="abs-add-custom-time-sots abs-admin-btn  abs-ladda-button abs-slide-right">
                    <span class="label"><?php esc_html_e( 'Add Timeslots', 'appointment-booking' ); ?></span>
                    <span class="abs-spinner"></span>
                </button>
            </th>
        </tr>
        </tbody>
    </table>
    <div class="abs-custom-time-slots-area">
        <table class="abs-time-slots-list">
            <thead>
            <tr>
                <th><?php esc_attr_e( 'Title', 'appointment-booking' ); ?></th>
                <th><?php esc_attr_e( 'Date', 'appointment-booking' ); ?></th>
                <th><?php esc_attr_e( 'Time Slot', 'appointment-booking' ); ?></th>
                <th><?php esc_attr_e( 'Spaces Available', 'appointment-booking' ); ?></th>
                <th><?php esc_attr_e( 'Action', 'appointment-booking' ); ?></th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>