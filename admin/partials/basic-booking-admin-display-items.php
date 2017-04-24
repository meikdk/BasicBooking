<?php
/**
 * Created by PhpStorm.
 * User: chroes
 * Date: 31-08-2016
 * Time: 11:45
 */

?>
<div id="admin_container" class="wrap">
    <h2>Basic Booking Options</h2>
    <div id = "item_container">
        <table class="table table-striped">
            <caption><h3 id="items_heading">Varer</h3></caption>
            <thead>
            <tr>
                <th>Navn</th><th>Shortcode</th><th>Farve kode</th><th>Maks antal dage</th><th>Min. timer til næste</th><th>Aktiv / Inaktiv</th><th>Rediger</th>
            </tr>
            </thead>
            <tbody id="item_body">

            </tbody>
        </table>
        <button class="btn btn-default" id="show_add_item" onclick="show_add_item()">Tilføj Varer</button>
        <div class="admin_menu_form" id="edit_item">
            <form>
                <input id="edit_id" value="" hidden>
                <table class="table">
                    <thead>
                        <tr>
                            <th></th><th>Nuværdende</th><th>Nyt indhold</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><label for="new_name">Navn: </label></td><td><input id="old_name" name="old_name" type="text" disabled /></td><td><input id="new_name" name="new_name" type="text" /></td>
                        </tr>
                        <tr>
                            <td><label for="new_max_booking_count">Maks antal dage: </label></td><td><input id="old_max_booking_count" name="old_max_booking_count" type="text" disabled /></td><td><input id="new_max_booking_count" name="new_max_booking_count" type="number" min="1" /></td>
                        </tr>
                        <tr>
                            <td><label for="new_default_booking_interval">Min. timer til næste: </label></td><td><input id="old_default_booking_interval" name="old_default_booking_interval" type="text" disabled /></td><td><input id="new_default_booking_interval" name="new_default_booking_interval" type="number" min="0" /></td>
                        </tr>
                    </tbody>
                </table>
            </form>
            <button class="btn btn-default" onclick="edit_item()">Bekræft</button> <button class="btn btn-default" onclick="cancel_edit_item()">Fortryd</button>
        </div>
    </div>
    <div class="admin_menu_form" id="add_item" style="margin-top: 5px">
        <form>
            <table>
                <tr>
                    <td><label for="item_name">Navn: </label></td><td><input id="item_name" name="name" type="text" /></td>
                </tr>
                <tr>
                    <td><label for="item_booking_count">Maks antal dage: </label></td><td><input id="item_booking_count" name="item_booking_count" type="number" min="1" /></td>
                </tr>
                <tr>
                    <td><label for="default_booking_interval">Min. timer til næste: </label></td><td><input id="default_booking_interval" name="default_booking_interval" type="number" min="0" max="255" /></td>
                </tr>
            </table>
        </form>
        <button class="btn btn-default" style="margin-top: 5px" onclick="add_item()">Tilføj</button> <button class="btn btn-default" style="margin-top: 5px" onclick="cancel_add_item()">Fortryd</button>
    </div>
</div>