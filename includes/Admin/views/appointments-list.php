<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e( 'Appointment', 'appointment-booking' ); ?></h1>
    <?php
    $export_csv = wp_nonce_url( admin_url( 'admin-post.php?action=abs-export-csv' ), 'abs-export-csv' );
    ?>
    <a href="<?php echo esc_url( $export_csv ); ?>" class="page-title-action"><?php esc_html_e( 'Export CSV', 'appointment-booking' ); ?></a>
    <form id="plugins-filter" method="POST" action="<?php echo esc_url( admin_url( 'admin.php?page=appointments' ) ); ?>">
        <input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
        <?php
        $table = new \Appointment\Booking\Admin\Appointment_List();
        $table->prepare_items();
        $table->search_box( esc_html__( 'Search', 'appointment-booking' ), 'appointment_search' );
        $table->display();
        ?>
    </form>


</div>

