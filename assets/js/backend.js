;
(function($) {
    "use strict";

    /**
     * Alert confirm delete
     */
    $( '.abs-calendar-delete' ).on( 'click', function() {
        if ( confirm('Are you sure ?') ) {
            return true;
        } else {
            return false;
        }
    } );

    /**
     * Calendar create modal
     */
    $( '.abs-calendar-create' ).on( 'click', function( event ) {
        event.preventDefault();
        $( '.abs-calendar-modal' ).show();

        $( '.abs-modal-close' ).on( 'click', function () {
            $( '.abs-calendar-modal' ).hide();
        } );

        $( '.abs-book-form-cancel-btn' ).on( 'click', function () {
            $( '.abs-calendar-modal' ).hide();
        } );

        $( window ).click( function( event ) {
            if( $( event.target ).hasClass('abs-calendar-modal') ) {
                $( '.abs-calendar-modal' ).hide();
            }
        } );

    } );

    /**
     * Add Color Picker
     */
    $( function() {
        $('.abs-primary-color').wpColorPicker();
        $('.abs-secondary-color').wpColorPicker();
        $('.abs-tertiary-color').wpColorPicker();
        $('.abs-hover-color').wpColorPicker();
    } );

    /**
     * Booking redirect admin setting
     */
    $( '.abs-redirect' ).on( 'change', function () {
        if ( $( this).val() == 'refresh' ) {
            $( '.abs-page' ).show();
        } else {
            $( '.abs-page' ).hide();
        }
    } );

    if ( $(".abs-redirect:checked").val() == 'refresh' ) {
        $( '.abs-page' ).show();
    }

    /**
     * Booking free or paid
     */
    $( '.abs-appointment-paid-free' ).on( 'change', function () {
        booking_free_paid();
    } );

    booking_free_paid();

    function booking_free_paid() {
        if ( 'Paid' ==  $( '.abs-appointment-paid-free' ).val() ) {
            if ( $( '.abs-appointment-status' ).attr( 'data-select' ) == "yes" ) {
                $( '.abs-appointment-status' ).append( '<option selected="selected" value="Payment Complete">Payment complete will approve</option>' );
            } else {
                $( '.abs-appointment-status' ).append( '<option value="Payment Complete">Payment complete will approve</option>' );
            }
        } else {
            $( ".abs-appointment-status option[value='Payment Complete']" ).remove();
        }
    }

    /**
     * Tabs area js
     */
    $( '.abs-nav li' ).on( 'click',function ( event ) {
        event.preventDefault();
        var anchor = event.target;
        var anchor_id = anchor.getAttribute( 'href' );

        if ( '#abs-calendars-title' == anchor_id ) {
            return false;
        }

        $( '.abs-nav > .active' ).removeClass( 'active' );
        $( '.abs-tabs-contain > .active' ).removeClass( 'active' );
        $( this ).addClass( 'active' );
        $( anchor_id ).addClass( 'active' );
    } );

    if ( window.location.hash != '' ) {
        var id = window.location.hash;
        $( '.abs-nav > .active' ).removeClass( 'active' );
        $( '.abs-tabs-contain > .active' ).removeClass( 'active' );
        $( "[href=" + id + "]" ).parent().addClass( 'active' );
        $( id ).addClass( 'active' );
    }

    // Add time slots
    $( '.abs-add-time-sots' ).on( 'click', function () {
        var day_name = $( '.abs-timeslots-days' ).val();
        var title = $( '.abs-timeslots-title' ).val();
        var start_time = $( '.abs-start-time' ).val();
        var end_time = $( '.abs-end-time' ).val();
        var space_available = $( '.abs-count' ).val();
        var abs_calendar_id = $( '.abs-calendar-id' ).val();

        $('.abs-error-field').remove();
        if ( day_name === '' || start_time === '' || space_available === '' ) {
            if (day_name === '') {
                $( '.abs-timeslots-days' ).after('<div class="abs-error-field">  ' + abs_data.abs_error_filed + '  </div>');
            }

            if (start_time === '') {
                $( '.abs-start-time' ).after('<div class="abs-error-field">  ' + abs_data.abs_error_filed + ' </div>');
            }

            if (space_available === '') {
                $( '.abs-count' ).after('<div class="abs-error-field">  ' + abs_data.abs_error_filed + '  </div>');
            }

            return;
        } else {
            if (  $( '.abs-add-time-sots' ).attr( 'data-loading' ) === 'string'  ) {
                $( '.abs-add-time-sots' ).removeAttr( 'data-loading' );
            } else {
                $( '.abs-add-time-sots' ).attr('data-loading', '' );
            }

            var wc_product = '';
            var wc_product_status = false;
            if ( $( '.abs-wc-product' ).length ) {
                wc_product = $( '.abs-wc-product' ).val();
                var wc_product_status = true;
            }

            $.ajax( {
                type: 'POST',
                url: abs_data.ajax_url,
                data: {
                    action: 'abs_time_slots',
                    day_name: day_name,
                    title: title,
                    start_time: start_time,
                    end_time: end_time,
                    space_available: space_available,
                    abs_calendar_id: abs_calendar_id,
                    product: wc_product,
                    nonce: abs_data.abs_time_slot,
                },
                success: function() {
                    abs_load_time_slots();

                    $( '.abs-timeslots-days' ).val( '' );
                    $( '.abs-timeslots-title' ).val( '' );
                    $( '.abs-start-time' ).val( '' );
                    $( '.abs-end-time' ).val( '' );
                    $( '.abs-count' ).val( '' );

                    if ( wc_product_status === true ) {
                        $( '.abs-wc-product' ).val( '' );
                    }

                    $( '.abs-add-time-sots' ).removeAttr( 'data-loading' );
                }
            } );
        }
    });

    // Load Time Slots
    function abs_load_time_slots() {
        var abs_calendar_id = $( '.abs-calendar-id' ).val();
        $.ajax( {
            type: 'POST',
            url: abs_data.ajax_url,
            data: {
                action: 'abs_load_time_slots',
                nonce: abs_data.abs_time_slot,
                abs_calendar_id: abs_calendar_id,
            },
            success: function( html ) {
                $( '.abs-time-slots-area tbody' ).html( html );
                abs_time_slots_remove();
                abs_add_time_slots_sub();
                $(window).trigger('abs_load_time_slots');
            }
        } );
    }

    $(window).on('abs_time_slots_update', function() {
        abs_load_time_slots();
    });

    abs_load_time_slots();

    // Time slots remove
    function abs_time_slots_remove() {
        $( '.abs-time-slots-remove' ).on( 'click', function () {
            if ( confirm('Are you sure ?') ) {
                var id = $( this ).attr( 'data-id' );

                $.ajax( {
                    type: 'POST',
                    url: abs_data.ajax_url,
                    data: {
                        action: 'abs_time_slots_remove',
                        id: id,
                        nonce: abs_data.abs_time_slot,
                    },
                    success: function() {

                    }
                } );

                $( this ).parent().parent().remove();
            }
        } );
    }

    // Add time slots spaces
    function abs_add_time_slots_sub() {
        $( '.abs-time-slots-sub-add' ).on( 'click', function() {
            var id = $( this ).attr( 'data-id' );
            var operator = $( this ).attr( 'data-operator' );
            var available = $('.abs-spaces-available-num-' + id ).html();

            if ( operator == '-' ) {
                $('.abs-spaces-available-num-' + id ).html( parseInt(available) - 1 );
            } else if ( operator == '+' ) {
                $('.abs-spaces-available-num-' + id ).html( parseInt(available) + 1 );
            }

            $.ajax( {
                type: 'POST',
                url: abs_data.ajax_url,
                data: {
                    action: 'abs_time_slots_sub',
                    id: id,
                    operator: operator,
                    nonce: abs_data.abs_time_slot,
                },
                success: function() {

                }
            } );

        } );
    }

    /**
     * Custom Time Slots Filter
     */
    $( '.abs-custom-slots-type' ).on( 'change', function () {
        abs_custom_slots_type();
    } );

    function abs_custom_slots_type() {
        var custom_slots_type = $( '.abs-custom-slots-type' ).val();
        if ( custom_slots_type == 1 ) {
            $( '.abs-enable-appointments' ).show();
        } else {
            $( '.abs-enable-appointments' ).hide();
        }

        $( '.abs-custom-time-slots-end' ).on( 'change', function () {
            if ( $( '.abs-custom-time-slots-start' ).val() != '' ) {

                if ( $( '.abs-custom-time-slots-start' ).val() >  $( '.abs-custom-time-slots-end' ).val() ) {
                    $( '.abs-custom-time-slots-end' ).val( '' );
                    alert( abs_data.abs_must_larger );
                }

            } else {
                $( '.abs-custom-time-slots-end' ).val( '' );
                alert( abs_data.abs_start_date );
            }
        } );

    }

    abs_custom_slots_type();

    /**
     * Custom Time Slots Add
     */
    $( '.abs-add-custom-time-sots' ).on( 'click', function () {

        var time_slots_start  = $( '.abs-custom-time-slots-start' ).val();
        var time_slots_end    = $( '.abs-custom-time-slots-end' ).val();
        var slots_type =  $( '.abs-custom-slots-type' ).val();
        var timeslots_title = $( '.abs-custom-timeslots-title' ).val();
        var timeslots_start_time = $( '.abs-custom-timeslots-start-time' ).val();
        var timeslots_end_time = $( '.abs-custom-time-slots-end-time' ).val();
        var time_slots_count = $( '.abs-custom-time-slots-count' ).val();
        var abs_calendar_id = $( '.abs-calendar-id' ).val();

        $('.abs-error-field').remove();
        if ( time_slots_start === '' || slots_type === '' ) {

            if (time_slots_start === '') {
                $( '.abs-custom-time-slots-start' ).after('<div class="abs-error-field"> ' + abs_data.abs_error_filed +' </div>');
            }

            if (slots_type === '') {
                $( '.abs-custom-slots-type' ).after('<div class="abs-error-field">' + abs_data.abs_error_filed +' </div>');
            }

            return;
        } else {
            if ( slots_type == 1 ) {
                if ( timeslots_title === '' || timeslots_start_time === '' || time_slots_count === '' ) {
                    if (timeslots_title === '') {
                        $( '.abs-custom-timeslots-title' ).after('<div class="abs-error-field">' + abs_data.abs_error_filed +' </div>');
                    }

                    if (timeslots_start_time === '') {
                        $( '.abs-custom-timeslots-start-time' ).after('<div class="abs-error-field">' + abs_data.abs_error_filed +' </div>');
                    }

                    if (timeslots_start_time === '') {
                        $( '.abs-custom-time-slots-count' ).after('<div class="abs-error-field">' + abs_data.abs_error_filed +' </div>');
                    }

                    return false;
                }
            }

            if (  $( '.abs-add-custom-time-sots' ).attr( 'data-loading' ) === 'string'  ) {
                $( '.abs-add-custom-time-sots' ).removeAttr( 'data-loading' );
            } else {
                $( '.abs-add-custom-time-sots' ).attr('data-loading', '' );
            }


            var wc_product = '';
            var wc_product_status = false;
            if ( $( '.abs-wc-custom-product' ).length ) {
                wc_product = $( '.abs-wc-custom-product' ).val();
                var wc_product_status = true;
            }

            $.ajax( {
                type: 'POST',
                url: abs_data.ajax_url,
                data: {
                    action: 'abs_custom_time_slots',
                    start_date: time_slots_start,
                    end_date: time_slots_end,
                    slots_type: slots_type,
                    title: timeslots_title,
                    start_time: timeslots_start_time,
                    end_time: timeslots_end_time,
                    space_available: time_slots_count,
                    abs_calendar_id: abs_calendar_id,
                    product: wc_product,
                    nonce: abs_data.abs_custom_time_slot,
                },
                success: function( html ) {
                    abs_load_custom_time_slots();
                    $( '.abs-custom-time-slots-start' ).val( '' );
                    $( '.abs-custom-time-slots-end' ).val( '' );
                    $( '.abs-custom-slots-type' ).val( slots_type );
                    $( '.abs-custom-timeslots-title' ).val( '' );
                    $( '.abs-custom-timeslots-start-time' ).val( '' );
                    $( '.abs-custom-time-slots-end-time' ).val( '' );
                    $( '.abs-custom-time-slots-count' ).val( '' );
                    $( '.abs-add-custom-time-sots' ).removeAttr( 'data-loading' );

                    if ( wc_product_status === true ) {
                        $( '.abs-wc-custom-product' ).val( '' );
                    }


                }
            } );
        }
    });

    // Load Custom Time Slots
    function abs_load_custom_time_slots() {
        var abs_calendar_id = $( '.abs-calendar-id' ).val();
        $.ajax( {
            type: 'POST',
            url: abs_data.ajax_url,
            data: {
                action: 'abs_load_custom_time',
                nonce: abs_data.abs_custom_time_slot,
                abs_calendar_id: abs_calendar_id,
            },
            success: function( html ) {
                $( '.abs-custom-time-slots-area tbody' ).html( html );
                abs_custom_time_slots_remove();
                abs_add_custom_time_slots_sub();
                $(window).trigger('abs_load_time_slots');
            }
        } );
    }

    $(window).on('abs_custom_time_slots_update', function() {
        abs_load_custom_time_slots();
    });

    abs_load_custom_time_slots();

    // Custom time slots remove
    function abs_custom_time_slots_remove() {
        $( '.abs-custom-time-slots-remove' ).on( 'click', function () {
            if ( confirm('Are you sure ?') ) {
                var id = $( this ).attr( 'data-id' );

                $.ajax( {
                    type: 'POST',
                    url: abs_data.ajax_url,
                    data: {
                        action: 'abs_custom_time_slots_remove',
                        id: id,
                        nonce: abs_data.abs_custom_time_slot,
                    },
                    success: function() {

                    }
                } );

                $( this ).parent().parent().remove();
            }
        } );
    }

    // Add  Custom time slots spaces
    function abs_add_custom_time_slots_sub() {
        $( '.abs-custom-time-slots-sub-add' ).on( 'click', function() {
            var id = $( this ).attr( 'data-id' );
            var operator = $( this ).attr( 'data-operator' );
            var available = $('.abs-spaces-available-num-' + id ).html();

            if ( operator == '-' ) {
                $('.abs-spaces-available-num-' + id ).html( parseInt(available) - 1 );
            } else if ( operator == '+' ) {
                $('.abs-spaces-available-num-' + id ).html( parseInt(available) + 1 );
            }

            $.ajax( {
                type: 'POST',
                url: abs_data.ajax_url,
                data: {
                    action: 'abs_custom_time_slots_sub',
                    id: id,
                    operator: operator,
                    nonce: abs_data.abs_custom_time_slot,
                },
                success: function() {

                }
            } );

        } );
    }

    // Zoom connection check
    $( '.abs-check-api-connection' ).on( 'click', function ( event ) {
        event.preventDefault();
        $( '.abs-zoom-status' ).html( '<p class="abs-processing"> ' + abs_data.abs_check_api_connection_text +' </p>' );
        $.ajax( {
            type: 'POST',
            url: abs_data.ajax_url,
            data: {
                action: 'abs_check_api_connection',
                nonce: abs_data.abs_check_api_connection,
            },
            success: function( data ) {
                if ( data.status == 'success' ) {
                    $( '.abs-zoom-status' ).html( '<p class="abs-success"> ' + data.massage +' </p>' );
                } else {
                    $( '.abs-zoom-status' ).html( '<p class="abs-error"> ' + data.massage +' </p>' );
                }
            }
        } );
    } );

})(jQuery);