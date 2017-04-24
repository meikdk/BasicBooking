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
 * Gets the previous month on the calendar
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
		action: "basic_booking_load",
		_ajax_nonce: ajax_object.valid_calendar,
		year_month: { year: year, month: month },
		item_id: jQuery( "#item_id" ).val()
	}, function ( data ) {
		populate_calendar( data );
	});
}

/**
 * Gets the next month on the calendar
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
		action: "basic_booking_load",
		_ajax_nonce: ajax_object.valid_calendar,
		year_month: { year: year, month: month },
		item_id: jQuery( "#item_id" ).val()
	}, function ( data ) {
		populate_calendar( data );
	});
}

/**
 * Reloads the calendar
 */
function refresh_calendar() {
    var date = new Date( jQuery( "#displayed_year_month" ).val()+"-01" );
    var month = date.getMonth()+1;
    var year = date.getFullYear();
    var object = {
        action: "basic_booking_load",
        _ajax_nonce: ajax_object.valid_calendar,
        year_month: { year: year, month: month },
        item_id: jQuery( "#item_id" ).val()
    };
    jQuery.post( ajax_object.ajax_url, object, function ( data ) {
        populate_calendar( data );
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
		modifier = Math.abs(Math.floor(month_index / 12));
		month_index = month_index - modifier * 12;
	}
	return months[ month_index ];
}

function date_is_booked( check_date, periods ) {
    var bookings = [];
	for( var i = 0; i < periods.length; i++ ) {
        var start_date_init = periods[ i ][ 'start_date' ][ 0 ];
        var start_time_init = periods[ i ][ 'start_date' ][ 1 ];
        var start_date = new Date( start_date_init[ 0 ], start_date_init[ 1 ] - 1, start_date_init[ 2 ], start_time_init[ 0 ], start_time_init[ 1 ], start_time_init[ 2 ] );
        var end_date_init = periods[ i ][ 'end_date' ][ 0 ];
        var end_time_init = periods[ i ][ 'end_date' ][ 1 ];
        var end_date = new Date( end_date_init[ 0 ], end_date_init[ 1 ] - 1, end_date_init[ 2 ], end_time_init[ 0 ], end_time_init[ 1 ], end_time_init[ 2 ] );
        if( check_date.getFullYear() == start_date.getFullYear() &&
            check_date.getMonth() == start_date.getMonth() &&
            check_date.getDate() == start_date.getDate() ) {
            bookings.push( { start: {
                    h: start_date.getHours(),
                    m: start_date.getMinutes()
                }
            } );
        }
        else if( check_date.getFullYear() == end_date.getFullYear() &&
                 check_date.getMonth() == end_date.getMonth() &&
                 check_date.getDate() == end_date.getDate() ) {
            bookings.push( { end: {
                    h: end_date.getHours(),
                    m: end_date.getMinutes()
                }
            } );
        }
		else if( check_date > start_date && check_date < end_date ) {
			bookings.push( true );
            return bookings;
		}
	}
	return bookings;
}

/**
 * Draws the calendar
 *
 * @param   data - Unparsed JSON string
 */
function populate_calendar( data ) {
	data = JSON.parse( data );
    if( data[ 'month' ].length == 1 ) {
        data[ 'month' ] = '0'+data[ 'month' ];
    }
	jQuery( "#displayed_year_month" ).val( data[ 'year' ]+"-"+data[ 'month' ]);
	var date = new Date();
	var start_date = new Date( data[ 'year' ]+"-"+ data[ 'month' ]+"-01" );
    if( date < start_date ) {
        jQuery( '#prev_month_button' ).css( "visibility", "visible" );
    }
    else {
        jQuery( "#prev_month_button" ).css( "visibility", "hidden") ;
    }
    var i = 0;
    var end_date = jQuery( '#end_date' );
    end_date.empty();
    while( i < data[ 'max_booking_count' ] ) {
        i++;
        var option = document.createElement( 'option' );
        option.value = i;
        option.text = i;
        end_date.append( option );
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
            var cell = jQuery( cell_id );
            cell.removeClass( 'unbookable' );
            cell.removeClass( 'unbookable_start' );
            cell.removeClass( 'unbookable_end' );
            cell.text( start_date.getDate() );
			if(start_date <= date ) {
                cell.addClass( 'unbookable' );
			}
			else {
			    var booking_info = date_is_booked( start_date, data[ 'booked_periods' ] );
				if(booking_info.length > 0 ) {
				    if( booking_info.length > 1 || booking_info[ 0 ] === true ) {
                        cell.addClass( 'unbookable' )
                    }
                    else if( typeof booking_info[ 0 ].start != 'undefined' ) {
                        if(start_date.getDate() - 1 === new Date(Date.now()).getDate()) {
                            cell.addClass( 'unbookable' );
                        }
                        else {
                            cell.addClass( 'unbookable_start' );
                            cell.on( 'hover', null, cell, function( event ) {
                                event.data[ 0 ].style = "cursor: pointer";
                            });
                            cell.on( 'click', null, booking_info, function( event ) {
                                var hours = event.data[ 0 ].start.h - 4;
                                var minutes = String( event.data[ 0 ].start.m );
                                if( minutes.length < 2 ) {
                                    minutes = "0"+minutes;
                                }
                                alert( "Kan lejes til kl. "+hours+":"+minutes );
                            })
                        }
                    }
                    else if( typeof booking_info[ 0 ].end != 'undefined' ) {
                        var hours = booking_info[ 0 ].end.h + 4;
                        if( hours < 24 ) {
                            var minutes = String( booking_info[ 0 ].end.m );
                            if( minutes.length < 2 ) {
                                minutes = "0"+minutes;
                            }
                            cell.addClass( 'unbookable_end' );
                            cell.on( 'hover', null, cell, function( event ) {
                                event.data[ 0 ].style = "cursor: pointer";
                            });
                            cell.on( 'click', null, [ hours, minutes ], function( event ) {
                                alert( "Kan lejes fra kl. "+event.data[ 0 ]+":"+event.data[ 1 ] )
                            } );
                        }
                        else {
                            cell.addClass( 'unbookable' );
                        }
                    }
                }
			}
            start_date.setDate( start_date.getDate() + 1 );
		}
	}
}

/**
 * Creates a booking for the given item
 */
function submit_booking() {
    jQuery.each( jQuery( '.invalid_input' ), function() {
        jQuery(this).removeClass( 'invalid_input' );
    });
    var start_date = jQuery( '#start_date' );
    var end_date = jQuery( '#end_date' );
    var customer_name = jQuery( '#customer_name' );
    var customer_email = jQuery( '#customer_email' );
    var customer_phone_nr = jQuery( '#customer_phone_nr' );
    var comment = jQuery( '#comment' );
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
        var item_id = jQuery('#item_id').val();
        var object = {
            action: 'basic_booking_validate_booking',
            _ajax_nonce: ajax_object.valid_calendar,
            year_month: { year: date.getFullYear(), month: date.getMonth() + 1 },
            item_id: item_id,
            start_date: start_date.val(),
            end_date: end_date.val(),
            customer_name: customer_name.val(),
            customer_email: customer_email.val(),
            customer_phone_nr: customer_phone_nr.val(),
            comment: comment.val()
        };
        jQuery.post( ajax_object.ajax_url, object, function ( data ) {
            data = JSON.parse( data );
            var alert_string;
            if( data[ 'status' ] == 'success' ) {
                refresh_calendar();
                reset_booking_form();
                alert_string = 'Bestillingen er korrekt modtaget. Du burde modtage en e-mail med uddybende information inden for få minutter.';
                alert( alert_string );
            }
            else if( data[ 'status' ] == 'error' ) {
                switch( data[ 'error_code'] ) {
                    case 1:
                        alert_string = 'Den indtastede startdato har et ukorrekt format.\n' +
                                       'Formatet skal være ÅÅÅÅ-MM-DD';
                        jQuery('#start_date').addClass( 'invalid_input' );
                        alert( alert_string );
                        break;
                    case 2:
                        alert_string = 'Det indtastede antal overnatninger har et ukorrekt format.\n' +
                                       'Formatet skal være et tal mellem 1 og ' + data[ 'max_days' ];
                        end_date.addClass( 'invalid_input' );
                        alert( alert_string );
                        break;
                    case 3:
                        alert_string = 'Slutdatoen er den samme som, eller tidligere end, startdato.\n' +
                                       'Slutdato skal være mindst en dag efter startdato';
                        start_date.addClass( 'invalid_input' );
                        end_date.addClass( 'invalid_input' );
                        alert( alert_string );
                        break;
                    case 4:
                        alert_string = 'Startdatoen er i dag eller i fortiden.';
                        start_date.addClass( 'invalid_input' );
                        alert( alert_string );
                        break;
                    case 5:
                        alert_string = 'Den indtastede e-mail er ikke gyldig.\n' +
                                       'Indtast venligst en gyldig email.';
                        customer_email.addClass( 'invalid_input' );
                        alert( alert_string );
                        break;
                    case 6:
                        alert_string = 'Varereferencen er ugyldig.\n' +
                                       'Forny venligst siden og indtast dine bestillingsoplysninger igen.\n' +
                                       'Hvis problemet varer ved, kontakt administatoren på ' + data[ 'admin_email' ];
                        alert( alert_string );
                        break;
                    case 7:
                        alert_string = 'Den valgte periode overlapper med en eller flere andre udlejninger.\n' +
                                       'Vælg venligst en anden periode.';
                        start_date.addClass( 'invalid_input' );
                        end_date.addClass( 'invalid_input' );
                        alert( alert_string );
                        break;
                    case 8:
                        alert_string = 'Den valgte vare er for tiden ikke aktiv for udlejning. Prøv igen senere.';
                        alert( alert_string );
                        break;
                }
            }
        });
    }
}

/**
 * Resets the booking foorm
 */
function reset_booking_form() {
    jQuery( '#start_date' ).val( '' ).removeClass( 'invalid_input' );
    jQuery( '#end_date' ).val( 1 ).removeClass( 'invalid_input' );
    jQuery( '#customer_name' ).val( '' ).removeClass( 'invalid_input' );
    jQuery( '#customer_email' ).val( '' ).removeClass( 'invalid_input' );
    jQuery( '#customer_phone_nr' ).val( '' ).removeClass( 'invalid_input' );
    jQuery( '#comment' ).val( '' ).removeClass( 'invalid_input' );
}

/**
 * Initializes the datepicker functionality in the booking form
 */
jQuery("#start_date").datepicker( {
    dateFormat: "yy-mm-dd",
    dayNamesMin: ["Sø", "Ma", "Ti", "On", "To", "Fr", "Lø"],
	monthNames: ["Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December"],
    firstDay: 1
} );

/**
 * Initializes the page
 */
jQuery( "#calendar_month" ).ready( function(){
	var date = new Date();
	jQuery.post( ajax_object.ajax_url, {
		action: "basic_booking_load",
		_ajax_nonce: ajax_object.valid_calendar,
		year_month: {year: date.getFullYear(), month: date.getMonth()+1},
		item_id: jQuery("#item_id").val()
	}, function (data) {
		populate_calendar(data);
	});
});