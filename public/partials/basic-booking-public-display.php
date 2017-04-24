<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       solutionteam.dk
 * @since      1.0.0
 *
 * @package    Basic_Booking
 * @subpackage Basic_Booking/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<form>
    <input id='displayed_year_month' type='hidden' disabled />
    <input id='item_id' type='hidden' disabled />
</form>
<div class="basic-booking">
    <div>
        <table class="calendar">
            <caption><span id='prev_month_button' onclick='get_prev_month()'><img id='prev_month' alt="Previous Month" /></span><span id="calendar_month"></span><span id='next_month_button' onclick='get_next_month()'><img id='next_month' alt="Next Month" /></span></caption>
            <thead>
            <tr>
                <th>Uge</th><th>Man</th><th>Tir</th><th>Ons</th><th>Tor</th><th>Fre</th><th>Lør</th><th>Søn</th>
            </tr>
            </thead>
            <tbody id="calendar_body">
            <tr>
                <td id="week0" class="week_num"></td><td id="date00"></td><td id="date01"></td><td id="date02"></td><td id="date03"></td><td id="date04"></td><td id="date05"></td><td id="date06"></td>
            </tr>
            <tr>
                <td id="week1" class="week_num"></td><td id="date10"></td><td id="date11"></td><td id="date12"></td><td id="date13"></td><td id="date14"></td><td id="date15"></td><td id="date16"></td>
            </tr>
            <tr>
                <td id="week2" class="week_num"></td><td id="date20"></td><td id="date21"></td><td id="date22"></td><td id="date23"></td><td id="date24"></td><td id="date25"></td><td id="date26"></td>
            </tr>
            <tr>
                <td id="week3" class="week_num"></td><td id="date30"></td><td id="date31"></td><td id="date32"></td><td id="date33"></td><td id="date34"></td><td id="date35"></td><td id="date36"></td>
            </tr>
            <tr>
                <td id="week4" class="week_num"></td><td id="date40"></td><td id="date41"></td><td id="date42"></td><td id="date43"></td><td id="date44"></td><td id="date45"></td><td id="date46"></td>
            </tr>
            <tr>
                <td id="week5" class="week_num"></td><td id="date50"></td><td id="date51"></td><td id="date52"></td><td id="date53"></td><td id="date54"></td><td id="date55"></td><td id="date56"></td>
            </tr>
            </tbody>
        </table>
    </div>
    <br />
    <div>
        <form class="contact_form">
            <table id="contact_form_table">
                <tr>
                    <td><label for="start_date">Startdato: </label></td><td><input type="text" id="start_date"/></td>
                </tr>
                <tr>
                    <td><label for="end_date">Antal dage: </label></td><td><select id="end_date"></select></td>
                </tr>
                <tr>
                    <td><label for="customer_name">Fulde navn: </label></td><td><input type="text" id="customer_name" /></td>
                </tr>
                <tr>
                    <td><label for="customer_email">E-mail: </label></td><td><input type="text" id="customer_email"></td>
                </tr>
                <tr>
                    <td><label for="customer_phone_nr">Tlf. nr.: </label></td><td><input type="text" id="customer_phone_nr" /></td>
                </tr>
                <tr>
                    <td><label for="comment">Kommentarer: </label></td><td><textarea id="comment" name="comment"></textarea></td>
                </tr>
            </table>
        </form>
        <button class="form_button" onclick="submit_booking()">Bestil</button> <button class="form_button" onclick="reset_booking_form()">Nulstil formular</button>
    </div>
</div>