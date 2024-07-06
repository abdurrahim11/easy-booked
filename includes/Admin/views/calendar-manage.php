<?php
$abs_get_times = abs_get_times();
?>
<div class="wrap">
    <div class="abs-service-calendars">
        <ul class="abs-nav nav-tabs">
            <li class=""><a href="#abs-calendars-title" class="abs-service-calendars-title"><?php esc_html_e( 'Easy Booked', 'appointment-booking' ); ?></a></li>
            <li class="active"><a href="#abs-general"><span class="dashicons dashicons-admin-generic"></span><?php esc_html_e( 'General', 'appointment-booking' ); ?></a></li>
            <li class=""><a href="#abs-time-slots"><span class="dashicons dashicons-clock"></span><?php esc_html_e( 'Time Slots', 'appointment-booking' ); ?></a></li>
            <li class=""><a href="#custom-time-slots"><span class="dashicons dashicons-clock abs-clock"></span><?php esc_html_e( 'Custom Time Slots', 'appointment-booking' ); ?></a></li>
            <?php
            do_action( 'easy_booked_custom_add_new_menu' );
            ?>
        </ul>
        <div class="abs-tabs-contain abs-appointment-form">
            <div id="abs-general" class="abs-tab-pane active">
                <?php
                require_once __DIR__ . '/general.php';
                ?>
            </div>
            <div id="abs-time-slots" class="abs-tab-pane">
                <?php
                require_once __DIR__ . '/time-slots.php';
                ?>
            </div>
            <div id="custom-time-slots" class="abs-tab-pane">
                <?php
                require_once __DIR__ . '/custom-time-slots.php';
                ?>
            </div>
            <?php
            do_action( 'easy_booked_custom_add_new_contain' );
            ?>
        </div>
    </div>
</div>
