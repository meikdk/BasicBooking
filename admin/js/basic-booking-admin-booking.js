/**
 * All of the code for your public-facing JavaScript source
 * should reside in this file.

 * Ideally, it is not considered best practise to attach more than a
 * single DOM-ready or window-load handler for a particular page.
 * Although scripts in the WordPress core, Plugins and Themes may be
 * practising this, we should strive to set a better example in our own work.
 */
"use strict";

/**
 * Displays the bookings
 *
 * @param   data - Preparsed JSON object
 */
function print_bookings( data ) {
    populate_view_settings_form( data[ 'items' ] );
    var status_new = jQuery( '#status_new' );
    status_new.empty();
    var i;
    for( i = 0; i < data[ 'status_options' ].length; i++ ) {
        var tmp = data[ 'status_options' ][ i ].split( ',' );
        var status_option = document.createElement( 'option' );
        status_option.value = i;
        status_option.id = "SO_" + data[ 'status_options' ][ i ];
        status_option.innerHTML = data[ 'status_options' ][ i ];
        status_new.append( status_option );
    }
    var current_date = new Date();
    var item_list = jQuery( '#bookable_item_list' );
    item_list.empty();
    for( i = 0; i < data[ 'items' ].length; i++ ) {
        var name_option = document.createElement( 'option' );
        name_option.value = data[ 'items' ][ i ].item_id;
        name_option.innerHTML = data[ 'items' ][ i ][ 'name' ];
        item_list.append( name_option );
    }
    for( i = 0; i < data[ 'bookings' ].length; i++ ) {
        var row = document.createElement( 'tr' );
        row.id = 'booking_'+data[ 'bookings' ][ i ][ 'booking_id' ];
        var item_id = document.createElement( 'td' );
        item_id.innerHTML = data[ 'bookings' ][ i ]['booking_id'];
        row.appendChild( item_id );
        var item = document.createElement( 'td' );
        item.innerHTML = data[ 'bookings' ][ i ][ 'name' ];
        row.appendChild( item );
        var start_date = document.createElement( 'td' );
        start_date.innerHTML = data[ 'bookings' ][ i ][ 'start_date' ];
        var temp_date = new Date( data[ 'bookings' ][ i ][ 'start_date' ] );
        if( temp_date < current_date ) {
            start_date.style.backgroundColor = '#E04040';
        }
        row.appendChild( start_date );
        var end_date = document.createElement( 'td' );
        end_date.innerHTML = data[ 'bookings' ][ i ][ 'end_date' ];
        temp_date = new Date( data[ 'bookings' ][ i ][ 'end_date' ] );
        if( temp_date < current_date ) {
            end_date.style.backgroundColor = '#E04040';
        }
        row.appendChild( end_date );
        var customer_name = document.createElement( 'td' );
        customer_name.innerHTML = data[ 'bookings' ][ i ][ 'customer_name' ];
        row.appendChild( customer_name );
        var customer_email = document.createElement( 'td' );
        customer_email.innerHTML = data[ 'bookings' ][ i ][ 'customer_email' ];
        row.appendChild( customer_email );
        var customer_phone_nr = document.createElement( 'td' );
        customer_phone_nr.innerHTML = data[ 'bookings' ][ i ][ 'customer_phone_nr' ];
        row.appendChild( customer_phone_nr );
        var comment = document.createElement( 'td' );
        comment.innerHTML = data[ 'bookings' ][ i ][ 'comment' ];
        row.appendChild( comment );
        var status = document.createElement( 'td' );
        status.innerText = data[ 'status_options' ][data[ 'bookings' ][ i ][ 'status' ] ];
        row.appendChild( status );
        var booking_interval = document.createElement( 'td' );
        booking_interval.style.display = 'none';
        booking_interval.innerText = data[ 'bookings' ][ i ][ 'booking_interval' ];
        row.appendChild( booking_interval );
        var edit = document.createElement( 'td' );
        edit.innerHTML = '<button class="btn btn-default" onclick="show_edit_booking('+data[ 'bookings' ][ i ][ 'booking_id' ]+')">Ret</button>';
        row.appendChild( edit );
        var remove = document.createElement( 'td' );
        remove.innerHTML = '<button class="btn btn-default" onclick="delete_booking('+data[ 'bookings' ][ i ][ 'booking_id' ]+')">Slet</button>';
        row.appendChild( remove );
        jQuery( "#booking_body" ).append( row );
    }
    page_buttons( data[ 'max_amount' ] );
}

/**
 * Draws the content of the booking display settings form
 *
 * @param   items - The items of each item
 */
function populate_view_settings_form( items ) {
    var item_selection_table = jQuery( '#item_selection' );
    item_selection_table.empty();
    for( var i = 0; i < Math.floor(items.length / 3) + 1; i++ ) {
        var item_row = document.createElement( 'tr' );
        for( var j = 0; j < 3; j++ ) {
            var index = i * 3 + j;
            if( index >= items.length ) {
                break;
            }
            var check_cell = document.createElement( 'td' );
            var check_input = document.createElement( 'input' );
            check_input.id = "check_" + items[ index ].name;
            check_input.type = 'checkbox';
            check_input.name = 'item';
            check_input.value = items[ index ].name;
            check_input.checked = "checked";
            check_cell.appendChild( check_input );
            var label_cell = document.createElement( 'td' );
            var check_label = document.createElement( 'label' );
            check_label.for = check_input.id;
            check_label.innerHTML = check_input.value + ':';
            label_cell.appendChild( check_label );
            item_row.appendChild( label_cell );
            item_row.appendChild( check_cell );
        }
        item_selection_table.append( item_row );
    }
}

/**
 * Draws the calendar
 *
 * @param   data - Preparsed JSON object
 */
function populate_calendar( data ) {
    if( data[ 'month' ].length == 1 ) {
        data[ 'month' ] = '0'+data[ 'month' ];
    }
    var this_month = parseInt( data[ 'month' ] ) - 1;
    jQuery( "#displayed_year_month" ).val( data[ 'year' ]+"-"+data[ 'month' ]);
    var date = new Date();
    var start_date = new Date( data[ 'year' ], data[ 'month' ]-1, 1, 23, 59, 59 );
    if( date < start_date ) {
        jQuery( '#prev_month_button' ).css( "visibility", "visible" );
    }
    else {
        jQuery( "#prev_month_button" ).css( "visibility", "hidden") ;
    }
    start_date.setDate( start_date.getDate() - data[ 'first_weekday' ] );
    var week_num = parseInt( data[ 'month_start_week' ] );
    jQuery( "#calendar_month" ).text( get_month_name(data['month'])+" "+data['year'] );
    //Draw the calendar
    for( var i = 0; i < 6; i++ ) {
        var week_id = "#week".concat( String( i ) );
        jQuery( week_id ).text( week_num );
        if(week_num == 52) {
            week_num = 1;
        }
        else {
            week_num++;
        }
        for( var j = 0; j < 7; j++ ) {
            var cell_id = "#date".concat( String( i ), String( j ) );
            jQuery( cell_id ).removeClass( 'unbookable ');
            var date_div = document.createElement( 'div' );
            var p_date = document.createElement( 'p' );
            p_date.innerHTML = start_date.getDate();
            date_div.appendChild( p_date );
            var booked_items = date_is_booked( start_date, data[ 'booked_periods' ] );
            if( booked_items != false ) {
                for( var k = 0; k < booked_items.length; k++ ) {
                    var p_item = document.createElement( 'p' );
                    p_item.innerHTML = booked_items[ k ][ 0 ];
                    p_item.style.backgroundColor = booked_items[ k ][ 1 ];
                    date_div.appendChild( p_item );
                }
            }
            jQuery( cell_id ).html( date_div );
            start_date.setDate( start_date.getDate() + 1 );
        }
    }
}

/**
 * Reloads the booking list after it has been changed
 */
function refresh_bookings() {
    var date = new Date();
    jQuery.post( ajax_object.ajax_url, {
            action: 'basic_booking_admin_load_bookings',
            _ajax_nonce: ajax_object.valid_menu,
            year_month: {year: date.getFullYear(), month: date.getMonth() + 1},
            display_count: jQuery( '#hide_count' ).val(),
            current_offset: jQuery( '#current_offset' ).val()
        }, function( data ) {
            jQuery('#booking_body').empty();
            data = JSON.parse( data );
            populate_calendar( data[ 'calendar_data' ] );
            print_bookings( data );
        }
    );
}

/**
 * Adds a booking to the database
 */
function add_booking() {

    jQuery.each( jQuery( '.invalid_input' ), function() {
        jQuery(this).removeClass( 'invalid_input' );
    });
    var start_date = jQuery( '#start_date' );
    var start_time = jQuery( '#start_time' );
    var end_date = jQuery( '#end_date' );
    var end_time = jQuery( '#end_time' );
    var item = jQuery( '#booked_item' );
    var customer_name = jQuery( '#customer_name' );
    var customer_email = jQuery( '#customer_email' );
    var customer_phone_nr = jQuery( '#customer_phone_nr' );
    var comment = jQuery( '#comment' );
    var booking_interval = jQuery( '#booking_interval' );
    if( booking_interval.val() < 0 || booking_interval.val() > 255 ) {
        alert( "Min. timer til skal være mindst 0 og højest 255." );
        booking_interval.addClass( 'invalid_input' );
        return;
    }
    var missing = false;
    if( !start_date.val().trim() ) {
        missing = true;
        start_date.addClass( 'invalid_input' );
    }
    if( !end_date.val().trim() ) {
        missing = true;
        end_date.addClass( 'invalid_input' );
    }
    if( !customer_name.val().trim() ) {
        missing = true;
        customer_name.addClass( 'invalid_input' );
    }
    if( !customer_email.val().trim() ) {
        missing = true;
        customer_email.addClass( 'invalid_input' );
    }
    if( !customer_phone_nr.val().trim() ) {
        missing = true;
        customer_phone_nr.addClass( 'invalid_input' );
    }
    if( missing ) {
        alert('Der mangler at blive udfyldt nogle felter.\nFelter markeret med rødt skal være udfyldt.')
    }
    else {
        var date = new Date();
        var object = {
            action: 'basic_booking_admin_insert_booking',
            _ajax_nonce: ajax_object.valid_menu,
            year_month: { year: date.getFullYear(), month: date.getMonth() + 1 },
            booked_item: item.val(),
            start_date: start_date.val(),
            start_time: start_time.val(),
            end_date: end_date.val(),
            end_time: end_time.val(),
            customer_name: customer_name.val(),
            customer_email: customer_email.val(),
            customer_phone_nr: customer_phone_nr.val(),
            booking_interval: booking_interval.val(),
            comment: comment.val()
        };
        jQuery.post( ajax_object.ajax_url, object, function( data ) {
            data = JSON.parse( data );
            if( data[ 'status' ] == 'success' ) {
                cancel_add_booking();
                jQuery('#booking_body').empty();
                refresh_bookings();
            }
            else if( data[ 'status' ] == 'error' ) {
                var alert_string;
                switch( data[ 'error_code'] ) {
                    case 1:
                        jQuery('#start_date').addClass( 'invalid_input' );
                        alert_string = 'Ukorrekt datoformat på startdatoen.\n' +
                            'Datoformatet skal være ÅÅÅÅ-MM-DD';
                        alert( alert_string );
                        break;
                    case 2:
                        end_date.addClass( 'invalid_input' );
                        alert_string = 'Ukorrekt datoformat på slutdatoen.\n' +
                            'Datoformatet skal være ÅÅÅÅ-MM-DD';
                        alert( alert_string );
                        break;
                    case 3:
                        start_date.addClass( 'invalid_input' );
                        end_date.addClass( 'invalid_input' );
                        alert_string = 'Slutdato er før eller magen til startdato.\n' +
                            'Slutdato skal være mindst en dag efter startdato.';
                        alert( alert_string );
                        break;
                    case 4:
                        start_date.addClass( 'invalid_input' );
                        alert_string = 'Stardato er i fortiden eller i dag.\n' +
                            'Startdatoen skal som minimum være en dag i fremtiden.';
                        alert( alert_string );
                        break;
                    case 5:
                        customer_email.addClass( 'invalid_input' );
                        alert_string = 'Den indtastede e-mail er ukorrekt.\n' +
                            'Indsæt venligst en korrekt e-mail adresse.';
                        alert( alert_string );
                        break;
                    case 6:
                        item.addClass( 'invalid_input' );
                        alert_string = 'Den indtastede vare findes ikke.\n' +
                            'Indtast venligst en eksisterende vare.';
                        alert( alert_string );
                        break;
                    case 7:
                        start_date.addClass( 'invalid_input' );
                        end_date.addClass( 'invalid_input' );
                        alert_string = 'Der eksisterer allerede en bestilling for den valgte periode.\n' +
                            'Vælg venligst en fri periode.';
                        alert( alert_string );
                        break;
                }
            }
        });
    }
}

/**
 * Deletes the booking with the given ID
 *
 * @param   booking_id - Valid id for a booking
 */
function delete_booking( booking_id ) {
    var date = new Date();
    var confirm_string = 'Dette vil slette booking '+booking_id+'\nEr du sikker på du vil gøre dette?';
    if( confirm( confirm_string ) ) {
        var data_object = {};
        data_object[ 'year_month' ] = {year: date.getFullYear(), month: date.getMonth()+1};
        data_object[ 'action' ] = 'basic_booking_admin_delete_booking';
        data_object[ '_ajax_nonce' ] = ajax_object.valid_menu;
        data_object[ 'booking_id' ] = booking_id;
        data_object[ 'display_count' ] = jQuery( '#display_count' ).val();
        data_object[ 'current_offset' ] = jQuery( '#current_offset' ).val();
        var view_settings = jQuery( '#display_settings' ).val();
        if( view_settings != "" ) {
            view_settings = JSON.parse( view_settings );
            data_object[ 'custom_view' ] = view_settings.custom_view;
            data_object[ 'date_span_start' ] = view_settings.date_span_start;
            data_object[ 'date_span_end' ] = view_settings.date_span_end;
            data_object[ 'selections' ] = view_settings.selections;
        }
        jQuery.post( ajax_object.ajax_url, data_object, function( data ) {
            data = JSON.parse( data );
            jQuery('#booking_body').empty();
            print_bookings( data );
        });
    }
}

/**
 * Updates the display settings for the booking list
 */
function select_view_settings() {
    var date = new Date();
    var data_object = {
        action: 'basic_booking_admin_load_bookings',
        _ajax_nonce: ajax_object.valid_menu,
        year_month: { year: date.getFullYear(), month: date.getMonth() + 1 },
        date_span_start: jQuery( '#date_span_start' ).val(),
        date_span_end: jQuery( '#date_span_end' ).val(),
        display_count: jQuery( '#display_count' ).val(),
        current_offset: 0,
        custom_view: true
    };
    var selections = {};
    jQuery('#view_settings_form :input[type=checkbox]').each( function() {
        if( jQuery(this).prop( 'checked' ) ) {
            selections[ this.value ] = this.name;
        }
    });
    data_object[ 'selections' ] = selections;
    jQuery( '#display_settings' ).val( JSON.stringify( data_object ) );
    jQuery.post( ajax_object.ajax_url, data_object, function( data ) {
            data = JSON.parse( data );
            jQuery('#booking_body').empty();
            cancel_view_settings();
            cleanup_booking_forms();
            print_bookings( data );
        }
    );
}

/**
 * Updates an existing booking
 */
function edit_booking() {
    var booking_id = jQuery( '#booking_id_old' );
    var item = jQuery( '#item_new' );
    var start_date = jQuery( '#start_date_new' );
    var start_time = jQuery( '#start_time_new' );
    var end_date = jQuery( '#end_date_new' );
    var end_time = jQuery( '#end_time_new' );
    var customer_name = jQuery( '#customer_name_new' );
    var customer_email = jQuery( '#customer_email_new' );
    var customer_phone_nr = jQuery( '#customer_phone_nr_new' );
    var comment = jQuery( '#comment_new' );
    var booking_interval = jQuery( '#booking_interval_new' );
    if( booking_interval.val() < 0 || booking_interval.val() > 255 ) {
        alert( "Min. timer til skal være mindst 0 og højest 255." );
        return;
    }
    var status = jQuery( '#status_new' );
    var status_change = 0;
    if( jQuery( '#status_old' ).val() != 'Udlejet' && status.val() != "" ) {
        status_change = 1;
    }
    var object = {
        action: 'basic_booking_admin_update_booking',
        _ajax_nonce: ajax_object.valid_menu,
        booking_id: booking_id.val(),
        display_count: jQuery( '#hide_count' ).val(),
        current_offset: jQuery( '#current_offset' ).val(),
        item: item.val(),
        start_date: start_date.val(),
        start_time: start_time.val(),
        end_date: end_date.val(),
        end_time: end_time.val(),
        customer_name: customer_name.val(),
        customer_email: customer_email.val(),
        customer_phone_nr: customer_phone_nr.val(),
        comment: comment.val(),
        booking_interval: booking_interval.val(),
        status: status.val(),
        status_change: status_change
    };
    jQuery.post( ajax_object.ajax_url, object, function( data ) {
        data = JSON.parse( data );
        if( data[ 'status' ] == 'success' ) {
            cancel_edit_booking();
            jQuery('#booking_body').empty();
            refresh_bookings();
        }
        else if( data[ 'status' ] == 'error' ) {
            var alert_string;
            switch( data[ 'error_code'] ) {
                case 1:
                    jQuery('#start_date').addClass( 'invalid_input' );
                    alert_string = 'Ukorrekt datoformat på startdatoen.\n' +
                                   'Datoformatet skal være ÅÅÅÅ-MM-DD';
                    alert( alert_string );
                    break;
                case 2:
                    end_date.addClass( 'invalid_input' );
                    alert_string = 'Ukorrekt datoformat på slutdatoen.\n' +
                                   'Datoformatet skal være ÅÅÅÅ-MM-DD';
                    alert( alert_string );
                    break;
                case 3:
                    start_date.addClass( 'invalid_input' );
                    end_date.addClass( 'invalid_input' );
                    alert_string = 'Slutdato er før eller magen til startdato.\n' +
                                   'Slutdato skal være mindst en dag efter startdato.';
                    alert( alert_string );
                    break;
                case 4:
                    start_date.addClass( 'invalid_input' );
                    alert_string = 'Stardato er i fortiden eller i dag.\n' +
                                   'Startdatoen skal som minimum være en dag i fremtiden.';
                    alert( alert_string );
                    break;
                case 5:
                    customer_email.addClass( 'invalid_input' );
                    alert_string = 'Den indtastede e-mail er ukorrekt.\n' +
                                   'Indsæt venligst en korrekt e-mail adresse.';
                    alert( alert_string );
                    break;
                case 6:
                    item.addClass( 'invalid_input' );
                    alert_string = 'Den indtastede vare findes ikke.\n' +
                                   'Indtast venligst en eksisterende vare.';
                    alert( alert_string );
                    break;
                case 7:
                    start_date.addClass( 'invalid_input' );
                    end_date.addClass( 'invalid_input' );
                    alert_string = 'Der eksisterer allerede en bestilling for den valgte periode.\n' +
                                   'Vælg venligst en fri periode.';
                    alert( alert_string );
                    break;
                case 8:
                    start_date.addClass( 'invalid_input' );
                    end_date.addClass( 'invalid_input' );
                    alert_string = 'Den valgte periode er for lang. En booking kan kun vare '+data[ 'max_booking_count' ]+' dage.\n' +
                                   'Dette kan ændres under Basic Booking --> Indstillinger';
                    alert( alert_string );
                    break;
            }
        }
    });
}

/**
 * Gets the previous month in the calendar
 */
function get_prev_month() {
    var date = new Date( jQuery( "#displayed_year_month" ).val()+"-1" );
    var month = date.getMonth(); //Get month is zero indexed
    var year = date.getFullYear();
    if( month < 1 ) {
        month = 12;
        year--;
    }
    jQuery.post( ajax_object.ajax_url, {
        action: "basic_booking_admin_get_calendar_data",
        _ajax_nonce: ajax_object.valid_menu,
        year_month: { year: year, month: month },
        direct: true
    }, function ( data ) {
        data = JSON.parse( data );
        populate_calendar( data );
    });
}

/**
 * Gets the next month in the calendar
 */
function get_next_month() {
    var date = new Date(jQuery("#displayed_year_month").val()+"-1");
    var month = date.getMonth() + 2;
    var year = date.getFullYear();
    if( month > 12) {
        month = 1;
        year++;
    }
    jQuery.post( ajax_object.ajax_url, {
        action: "basic_booking_admin_get_calendar_data",
        _ajax_nonce: ajax_object.valid_menu,
        year_month: { year: year, month: month },
        direct: true
    }, function ( data ) {
        data = JSON.parse( data );
        populate_calendar( data );
    });
}

/**
 * Enables or disables the next and previous page buttons as appropriate
 *
 * @param   booking_count - The total amound of bookings fitting the display criteria
 */
function page_buttons( booking_count ) {
    var prev_button = jQuery( '#prev_page' );
    var next_button = jQuery( '#next_page' );
    prev_button.prop( 'disabled', false );
    next_button.prop( 'disabled', false );
    var curret_offset = jQuery( '#current_offset' ).val();
    if( curret_offset == 0 ) {
        prev_button.prop( 'disabled', true );
    }
    if( booking_count <= ( parseInt( jQuery( '#hide_count' ).val() ) * ( parseInt( curret_offset ) + 1 ) ) ) {
        next_button.prop( 'disabled', true );
    }
}

/**
 * Gets the next page on the display list
 */
function next_page() {
    var display_settings = jQuery('#display_settings');
    var current_offset = jQuery( '#current_offset' );
    if( display_settings.val() ) {
        display_settings = JSON.parse( display_settings.val() );
        display_settings[ 'current_offset' ] = parseInt( current_offset.val() ) + 1;
    }
    else {
        var date = new Date();
        display_settings = {
            action: 'basic_booking_admin_load_bookings',
            _ajax_nonce: ajax_object.valid_menu,
            year_month: {year: date.getFullYear(), month: date.getMonth() + 1},
            display_count: jQuery( '#hide_count' ).val(),
            current_offset: parseInt( current_offset.val() ) + 1
        }
    }
    current_offset.val( display_settings[ 'current_offset' ] );
    jQuery.post( ajax_object.ajax_url, display_settings, function( data ) {
        data = JSON.parse( data );
        jQuery('#booking_body').empty();
        cancel_view_settings();
        cleanup_booking_forms();
        print_bookings( data );
    });
}

/**
 * Gets the previous page on the display list
 */
function prev_page() {
    var display_settings = jQuery('#display_settings');
    var current_offset = jQuery( '#current_offset' );
    if( display_settings.val() ) {
        display_settings = JSON.parse( display_settings.val() );
        display_settings[ 'current_offset' ] = parseInt( current_offset.val() ) - 1;
    }
    else {
        var date = new Date();
        display_settings = {
            action: 'basic_booking_admin_load_bookings',
            _ajax_nonce: ajax_object.valid_menu,
            year_month: {year: date.getFullYear(), month: date.getMonth() + 1},
            display_count: jQuery( '#hide_count' ).val(),
            current_offset: parseInt( current_offset.val() ) - 1
        }
    }
    current_offset.val( display_settings[ 'current_offset' ] );
    jQuery.post( ajax_object.ajax_url, display_settings, function( data ) {
        data = JSON.parse( data );
        jQuery('#booking_body').empty();
        cancel_view_settings();
        cleanup_booking_forms();
        print_bookings( data );
    });
}

/**
 * Takes a zero-indexed month number and returns the name of that month
 *
 * @param   month_index - Zero-indexed month integer
 * @returns {string} - The name of the month
 */
function get_month_name( month_index ) {
    var months = [ "Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December" ];
    var modifier;
    month_index--;
    if( month_index < 0 ) {
        modifier = Math.abs( Math.floor( month_index / 12 ) );
        month_index = month_index + modifier * 12;
    }
    else if( month_index > 11 ) {
        modifier = Math.abs( Math.floor( month_index / 12 ) );
        month_index = month_index - modifier * 12;
    }
    return months[ month_index ];
}

/**
 * Takes a date object and compares it to an array of booked periods
 *
 * @param   check_date - The date which is checked
 * @param   periods - An array containing period objects
 * @returns {*} - False if no overlap, otherwise a list of booked items of the checked date
 */
function date_is_booked( check_date, periods ) {
    var items = [];
    for( var i = 0; i < periods.length; i++ ) {
        var start_date_init = periods[ i ][ 'start_date' ][ 0 ];
        var start_time_init = periods[ i ][ 'start_date' ][ 1 ];
        var start_date = new Date( start_date_init[ 0 ], start_date_init[ 1 ] - 1, start_date_init[ 2 ], start_time_init[ 0 ], start_time_init[ 1 ], start_time_init[ 2 ] );
        var end_date_init = periods[ i ][ 'end_date' ][ 0 ];
        var end_time_init = periods[ i ][ 'end_date' ][ 1 ];
        var end_date = new Date( end_date_init[ 0 ], end_date_init[ 1 ] - 1, end_date_init[ 2 ], end_time_init[ 0 ], end_time_init[ 1 ], end_time_init[ 2 ] );
        if( check_date >= start_date && check_date < end_date ) {
            items.push( [ periods[ i ][ 'name' ], periods[ i ][ 'color_code' ] ] );
        }
    }
    if( items.length > 0) {
        return items;
    }
    else {
        return false;
    }
}

/**
 * Displays the form for adding additional bookings
 */
function show_add_booking() {
    cleanup_booking_forms();
    jQuery( '#add_booking' ).show();
    jQuery( '#booked_item' ).html( jQuery( '#bookable_item_list' ).html() ).focus();

    jQuery("#start_date").datepicker({
        dateFormat: "yy-mm-dd",
        dayNamesMin: ["Sø", "Ma", "Ti", "On", "To", "Fr", "Lø"],
        monthNames: ["Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December"],
        firstDay: 1
    });

    jQuery("#end_date").datepicker({
        dateFormat: "yy-mm-dd",
        dayNamesMin: ["Sø", "Ma", "Ti", "On", "To", "Fr", "Lø"],
        monthNames: ["Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December"],
        firstDay: 1
    });
}

/**
 * Hides the form for adding additional bookings and removes all info put into the form
 */
function cancel_add_booking() {
    jQuery( '#booked_item' ).val( '' ).removeClass( 'invalid_input' );
    jQuery( '#start_date' ).val( '' ).removeClass( 'invalid_input' );
    jQuery( '#end_date' ).val( '' ).removeClass( 'invalid_input' );
    jQuery( '#customer_name' ).val( '' ).removeClass( 'invalid_input' );
    jQuery( '#customer_email' ).val( '' ).removeClass( 'invalid_input' );
    jQuery( '#customer_phone_nr' ).val( '' ).removeClass( 'invalid_input' );
    jQuery( '#comment' ).val( '' ).removeClass( 'invalid_input' );
    jQuery( '#booking_interval' ).val( '' ).removeClass( 'invalid_input' );
    jQuery( '#add_booking' ).hide();
}

/**
 * Displays the form for adding updating a given booking
 *
 * @param   booking_id - A valid id for a booking
 */
function show_edit_booking( booking_id ) {
    cleanup_booking_forms();
    jQuery( '#edit_booking' ).show();
    var row = [];
    jQuery( '#booking_'+booking_id ).find( 'td' ).each( function() {
        row.push( jQuery( this ).text() );
    });
    jQuery( '#booking_id_old' ).val( row[ 0 ] );
    jQuery( '#item_old' ).val( row[ 1 ] );
    var new_item = jQuery( '#item_new' );
    var item_list = jQuery( '#bookable_item_list' );
    new_item.html( item_list.html() ).focus();
    new_item.val( "" );
    for( var i = 0; i < item_list.children().length; i++ ) {
        var child_item = item_list.children()[ i ];
        if( child_item.text === row[ 1 ] ) {
            new_item.val( child_item.value );
            break;
        }
    }
    var datetime = row[ 2 ].split( ' ' );
    jQuery( '#start_date_old' ).val( datetime[ 0 ] );
    jQuery( '#start_date_new' ).val( datetime[ 0 ] ).datepicker({
        dateFormat: "yy-mm-dd",
        dayNamesMin: ["Sø", "Ma", "Ti", "On", "To", "Fr", "Lø"],
        monthNames: ["Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December"],
        firstDay: 1
    });
    jQuery( '#start_time_old' ).val( datetime[ 1 ] );
    jQuery( '#start_time_new' ).val( datetime[ 1 ] );
    datetime = row[ 3  ].split( ' ' );
    jQuery( '#end_date_old' ).val( datetime[ 0 ] );
    jQuery( '#end_date_new' ).val( datetime[ 0 ] ).datepicker({
        dateFormat: "yy-mm-dd",
        dayNamesMin: ["Sø", "Ma", "Ti", "On", "To", "Fr", "Lø"],
        monthNames: ["Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December"],
        firstDay: 1
    });
    jQuery( '#end_time_old' ).val( datetime[ 1 ] );
    jQuery( '#end_time_new' ).val( datetime[ 1 ] );
    jQuery( '#customer_name_old' ).val( row[ 4 ] );
    jQuery( '#customer_name_new' ).val( row[ 4 ] );
    jQuery( '#customer_email_old' ).val( row[ 5 ] );
    jQuery( '#customer_email_new' ).val( row[ 5 ] );
    jQuery( '#customer_phone_nr_old' ).val( row[ 6 ] );
    jQuery( '#customer_phone_nr_new' ).val( row[ 6 ] );
    jQuery( '#comment_old' ).val( row[ 7 ] );
    jQuery( '#comment_new' ).val( row[ 7 ] );
    jQuery( '#status_old' ).val( row[ 8 ] );
    jQuery( '#booking_interval_old' ).val( row[ 9 ] );
    jQuery( '#booking_interval_new' ).val( row[ 9 ] );

}

/**
 * Hides the form for updating bookings and removes all info put into the form
 */
function cancel_edit_booking() {
    jQuery( '#booking_id_old' ).val('').removeClass( 'invalid_input' );
    jQuery( '#item_old' ).val('').removeClass( 'invalid_input' );
    jQuery( '#item_new' ).val('').removeClass( 'invalid_input' );
    jQuery( '#start_date_old' ).val('').removeClass( 'invalid_input' );
    jQuery( '#start_date_new' ).val('').removeClass( 'invalid_input' );
    jQuery( '#end_date_old' ).val('').removeClass( 'invalid_input' );
    jQuery( '#end_date_new' ).val('').removeClass( 'invalid_input' );
    jQuery( '#customer_name_old' ).val('').removeClass( 'invalid_input' );
    jQuery( '#customer_name_new' ).val('').removeClass( 'invalid_input' );
    jQuery( '#customer_email_old' ).val('').removeClass( 'invalid_input' );
    jQuery( '#customer_email_new' ).val('').removeClass( 'invalid_input' );
    jQuery( '#customer_phone_nr_old' ).val('').removeClass( 'invalid_input' );
    jQuery( '#customer_phone_nr_new' ).val('').removeClass( 'invalid_input' );
    jQuery( '#comment_old' ).val('').removeClass( 'invalid_input' );
    jQuery( '#comment_new' ).val('').removeClass( 'invalid_input' );
    jQuery( '#edit_booking' ).hide();
}

/**
 * Displays the form for updating the display settings of the booking list
 */
function show_view_settings() {
    var view_settings = jQuery( '#view_settings' );
    if( view_settings.css( 'display' ) != 'none' ) {
        reset_view_settings();
    }
    else {
        cleanup_booking_forms();
        view_settings.show();
        jQuery("#date_span_start").datepicker({
            dateFormat: "yy-mm-dd",
            dayNamesMin: ["Sø", "Ma", "Ti", "On", "To", "Fr", "Lø"],
            monthNames: ["Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December"],
            firstDay: 1
        });

        jQuery("#date_span_end").datepicker({
            dateFormat: "yy-mm-dd",
            dayNamesMin: ["Sø", "Ma", "Ti", "On", "To", "Fr", "Lø"],
            monthNames: ["Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December"],
            firstDay: 1
        });
    }
}

/**
 * Hides the form for updating the display settings of the booking list and removes all info put into the form
 */
function cancel_view_settings() {
    jQuery( '#date_span_start' ).val('');
    jQuery( '#date_span_end' ).val('');
    jQuery( '#view_settings' ).hide();
}

/**
 * Sets the display settings for the booking list to its default value
 */
function reset_view_settings() {
    cleanup_booking_forms();
    cancel_view_settings();
    jQuery( '#display_settings').val( '' );
    jQuery( '#hide_count' ).val( 25 );
    jQuery( '#current_offset' ).val( 0 );
    refresh_bookings();
}

/**
 * Hides and cleans up all forms
 */
function cleanup_booking_forms() {
    cancel_add_booking();
    cancel_view_settings();
    cancel_edit_booking();
}

/**
 * Initializes the page
 */
jQuery().ready( function() {
    var date = new Date();
    jQuery.post( ajax_object.ajax_url, {
            action: 'basic_booking_admin_load_bookings',
            _ajax_nonce: ajax_object.valid_menu,
            year_month: {year: date.getFullYear(), month: date.getMonth() + 1},
            display_count: jQuery( '#hide_count' ).val(),
            current_offset: jQuery( '#current_offset' ).val()
        }, function( data ) {
            data = JSON.parse( data );
            populate_calendar( data[ 'calendar_data' ] );
            print_bookings( data );
        }
    );
} );