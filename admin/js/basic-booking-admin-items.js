/**
 * Created by chroes on 05-09-2016.
 */
"use strict";

/**
 * Prints a list of available items
 *
 * @param   data - Preparsed JSON data
 */
function print_items( data ) {
    for( var i = 0; i < data.length; i++ ) {
        var row = document.createElement( 'tr' );
        var name = document.createElement( 'td' );
        name.innerHTML = data[ i ][ 'name' ];
        row.appendChild( name );
        var shortcode = document.createElement( 'td' );
        shortcode.innerHTML = "[basic-booking item_id="+data[ i ][ 'item_id' ]+"]";
        row.appendChild( shortcode );
        var color_code = document.createElement( 'td' );
        color_code.style.backgroundColor = data[ i ][ 'color_code' ];
        row.appendChild( color_code );
        var max_booking_count = document.createElement( 'td' );
        max_booking_count.innerText = data[ i ][ 'max_booking_count' ];
        row.appendChild( max_booking_count );
        var time_interval = document.createElement( 'td' );
        time_interval.innerHTML = data[ i ][ 'default_booking_interval' ];
        row.appendChild( time_interval );
        var active = document.createElement( 'td' );
        if("0" === data[ i ][ 'is_active' ]) {
            active.innerHTML = '<button class="btn btn-default" onclick="activate_item('+data[ i ][ 'item_id' ]+', \''+data[ i ][ 'name' ]+'\')">Aktiver</button>';
        }
        else {
            active.innerHTML = '<button class="btn btn-default" onclick="deactivate_item('+data[ i ][ 'item_id' ]+', \''+data[ i ][ 'name' ]+'\')">Deaktiver</button>';
        }
        row.appendChild( active );
        var edit = document.createElement( 'td' );
        edit.innerHTML = '<button class="btn btn-default" onclick="show_edit_item(\''+data[ i ][ 'name' ]+'\', '+data[ i ][ 'item_id' ]+', '+data[ i ][ 'max_booking_count']+', '+data[ i ][ 'default_booking_interval' ]+')">Ret</button>';
        row.appendChild( edit );
        jQuery("#item_body").append( row );
    }
}

/**
 * Adds a bookable item
 */
function add_item() {
    jQuery.post( ajax_object.ajax_url, {
        action: 'basic_booking_admin_insert_item',
        _ajax_nonce: ajax_object.valid_menu,
        name: jQuery( '#item_name' ).val(),
        max_count: jQuery( '#item_booking_count' ).val(),
        time_interval: jQuery( '#default_booking_interval' ).val()
    }, function( data ) {
        cancel_add_item();
        jQuery('#item_body').empty();
        print_items( JSON.parse( data ) );
    })
}

/**
 * Activates a given item
 *
 * @param   item_id - The id of the item
 * @param   item_name - The name of the item
 */
function activate_item ( item_id, item_name ) {
    var confirm_string = 'Dette vil sætte vare '+item_name+' som aktiv.\nEr du sikker på du vil gøre dette?';
    if( confirm( confirm_string ) ) {
        jQuery.post( ajax_object.ajax_url, {
            action: 'basic_booking_admin_activate_item',
            _ajax_nonce: ajax_object.valid_menu,
            item_id: item_id
        }, function( data ) {
            jQuery('#item_body').empty();
            print_items( JSON.parse( data ) );
        });
    }
}

/**
 * Deactivates a given item
 *
 * @param   item_id - The id of the item
 * @param   item_name - The name of the item
 */
function deactivate_item( item_id, item_name ) {
    var confirm_string = 'Dette vil sætte vare '+item_name+' som inaktiv.\nEr du sikker på du vil gøre dette?';
    if( confirm( confirm_string ) ) {
        jQuery.post( ajax_object.ajax_url, {
            action: 'basic_booking_admin_deactivate_item',
            _ajax_nonce: ajax_object.valid_menu,
            item_id: item_id
        }, function( data ) {
            jQuery('#item_body').empty();
            print_items( JSON.parse( data ) );
        });
    }
}

/**
 * Updates the values for a specific item
 */
function edit_item() {
    var id = jQuery( '#edit_id' ).val();
    var new_name = jQuery( '#new_name' ).val();
    var max_count = jQuery( '#new_max_booking_count' );
    var time_interval = jQuery( '#new_default_booking_interval' );
    if( max_count.val() > 0 ) {
        if( time_interval.val() > -1 && time_interval.val() < 256 ) {
            jQuery.post( ajax_object.ajax_url, {
                action: 'basic_booking_admin_update_item',
                _ajax_nonce: ajax_object.valid_menu,
                item_id: id,
                name: new_name,
                max_count: max_count.val(),
                time_interval: time_interval.val()
            }, function( data ) {
                jQuery( '#item_body' ).empty();
                print_items( JSON.parse( data ) );
                cancel_edit_item();
            });
        }
        else {
            alert( 'Min. timer mellem udlejninger skal være mindst 0 og højest 255.');
        }
    }
    else {
        alert( 'Maks antal dage skal være mindst 1.');
    }
}

/**
 * Displays the form for adding a new item
 */
function show_add_item() {
    cleanup_item_forms();
    jQuery( '#add_item' ).show();
}

/**
 * Hides the form for adding a new item and cleans up input fields
 */
function cancel_add_item() {
    jQuery( '#item_name' ).val( '' );
    jQuery( '#item_booking_count' ).val( '' );
    jQuery( '#default_booking_interval' ).val( '' );
    jQuery( '#add_item' ).hide();
}

/**
 * Displays the form for updating a specific item
 *
 * @param   name - The name of the item
 * @param   id - The id of the item
 * @param   max_count - The maximum number of days a booking can last
 */
function show_edit_item( name, id, max_count, interval ) {
    cleanup_item_forms();
    jQuery( '#old_name' ).val( name );
    jQuery( '#new_name' ).val( name );
    jQuery( '#old_max_booking_count' ).val( max_count );
    jQuery( '#new_max_booking_count' ).val( max_count );
    jQuery( '#old_default_booking_interval' ).val( interval );
    jQuery( '#new_default_booking_interval' ).val( interval );
    jQuery( '#edit_id' ).val( id );
    jQuery( '#edit_item' ).show();
}

/**
 * Hides the form for updating a specific item
 */
function cancel_edit_item() {
    jQuery( '#edit_item' ).hide();
    jQuery( '#old_name' ).val( '' );
    jQuery( '#new_name' ).val( '' );
    jQuery( '#old_max_booking_count' ).val( '' );
    jQuery( '#new_max_booking_count' ).val( '' );
    jQuery( '#old_default_booking_interval' ).val( '' );
    jQuery( '#new_default_booking_interval' ).val( '' );
}

/**
 * Hides all forms and clears all input fields
 */
function cleanup_item_forms() {
    cancel_add_item();
    cancel_edit_item();
}

/**
 * Initializes the page
 */
jQuery().ready( function() {
    jQuery.post(ajax_object.ajax_url, {
            action: 'basic_booking_admin_load_items',
            _ajax_nonce: ajax_object.valid_menu
        }, function (data) {
            data = JSON.parse(data);
            print_items(data);
        }
    );
});