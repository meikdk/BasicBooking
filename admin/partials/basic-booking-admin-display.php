<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       solutionteam.dk
 * @since      1.0.0
 *
 * @package    Basic_Booking
 * @subpackage Basic_Booking/admin/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="admin_container" class="wrap">
    <form>
        <input id='displayed_year_month' type='hidden' disabled />
        <input id='item_id' type='hidden' disabled />
        <input id='display_settings' type="hidden" disabled />
        <input id='hide_count' type="hidden" value="25" disabled />
        <input id='current_offset' type="hidden" value="0" disabled />
    </form>
	<h2 id="booking_heading">Booking Administration</h2>
    <div>
        <table class="calendar">
            <caption><span id='prev_month_button' onclick='get_prev_month()'><img id='prev_month' alt="Previous Month" /></span><span id="calendar_month"></span><span id='next_month_button' onclick='get_next_month()'><img id='next_month' alt="Next Month" /></span></caption>
            <thead>
            <tr>
                <th id="week" class="week_num">Uge</th><th>Man</th><th>Tir</th><th>Ons</th><th>Tor</th><th>Fre</th><th>Lør</th><th>Søn</th>
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
	<div id = "booking_container">
        <h3><span>Udlejninger</span><button id="show_booking_date_span" class="btn btn-default btn-xs pull-right" onclick="show_view_settings()">Visningsindstillinger</button></h3>
        <div class="admin_menu_form settings" id="view_settings">
            <hr class="ruler" />
            <form id="view_settings_form" class="table">
                <div>
                    <h4>Viste Varer</h4>
                    <table id="item_selection">

                    </table>
                </div>
                <div>
                    <h4>Tidsperiode</h4>
                    <table>
                        <tr>
                            <td><label for="date_span_start" >Startdato: </label></td><td><input id="date_span_start" name="date_span_start" type="text" /></td>
                        </tr>
                        <tr>
                            <td><label for="date_span_end" >Slutdato: </label></td><td><input id="date_span_end" name="date_span_end" type="text" /></td>
                        </tr>
                    </table>
                </div>
                <div>
                    <h4>Antal</h4>
                    <table>
                        <tr>
                            <td><label for="display_count">Pr. side: </label></td><td>  <select id="display_count" name="display_count">
                                                                                            <option>25</option>
                                                                                            <option>50</option>
                                                                                            <option>100</option>
                                                                                            <option>250</option>
                                                                                        </select>
                                                                                    </td>
                        </tr>
                    </table>
                </div>
                <div>
                    <h4>Bestillingsstatus</h4>
                    <table>
                        <tr>
                            <td><label for="select_confirmed">Bekræftet:</label></td><td><input id="select_confirmed" name="status" value="select_confirmed" type="checkbox" style="margin-right: 10px" /></td>
                        </tr>
                        <tr>
                            <td><label for="select_unconfirmed">Ubekræftet:</label></td><td><input id="select_unconfirmed" name="status" value="select_unconfirmed" type="checkbox" checked="checked" style="margin-right: 10px" /></td>
                        </tr>
                    </table>
                </div>
            </form>
            <button class="btn btn-default btn-sm" onclick="select_view_settings()">Hent udlejninger</button> <button class="btn btn-default btn-sm" onclick="cancel_view_settings()">Fortryd</button>
        </div>
        <hr class="ruler" />
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Booking id</th><th>Vare</th><th>Startdato</th><th>Slutdato</th><th>Kunde navn</th><th>Kunde e-mail</th><th>Kunde tlf. nr.</th><th>Kommentar</th><th>Status</th><th>Ret</th><th>Fjern</th>
				</tr>
			</thead>
			<tbody id="booking_body">

			</tbody>
		</table>
        <button id="show_add_booking" class="btn btn-default" onclick="show_add_booking()">Tilføj Udlejning</button> <button id="reset_date_span" class="btn btn-default" onclick="reset_view_settings()">Nulstil visning</button>
        <button id="next_page" class="btn btn-default pull-right" onclick="next_page()">Næste</button> <button id="prev_page" class="btn btn-default pull-right" onclick="prev_page()">Forrige</button>
	</div>
    <hr class="ruler" />
    <div class="admin_menu_form" id="add_booking">
        <form>
            <datalist id="bookable_item_list"></datalist>
            <table>
                <tr>
                    <td><label for="booked_item">Vare:</label></td><td><select id="booked_item" style="margin-left: 5px" name="item" ></select></td>
                </tr>
                <tr>
                    <td><label for="start_date">Startdato:</label></td><td><input id="start_date" style="margin-left: 5px" name="start_date" type="text" /></td>
                </tr>
                <tr>
                    <td><label for="start_time">Starttid:</label></td><td><select id="start_time" style="margin-left: 5px" name="start_time" type="text" >
                            <option value="09:00:00">9:00</option>
                            <option value="10:00:00">10:00</option>
                            <option value="11:00:00">11:00</option>
                            <option value="12:00:00">12:00</option>
                            <option value="13:00:00">13:00</option>
                            <option value="14:00:00">14:00</option>
                            <option value="15:00:00">15:00</option>
                            <option value="16:00:00">16:00</option>
                            <option value="17:00:00">17:00</option>
                            <option value="18:00:00">18:00</option>
                        </select></td>
                </tr>
                <tr>
                    <td><label for="end_date">Slutdato:</label></td><td><input id="end_date" style="margin-left: 5px" name="end_date" type="text" /></td>
                </tr>
                <tr>
                    <td><label for="end_time">Sluttid:</label></td><td><select id="end_time" style="margin-left: 5px" name="end_time" type="text" >
                            <option value="09:00:00">9:00</option>
                            <option value="10:00:00">10:00</option>
                            <option value="11:00:00">11:00</option>
                            <option value="12:00:00">12:00</option>
                            <option value="13:00:00">13:00</option>
                            <option value="14:00:00">14:00</option>
                            <option value="15:00:00">15:00</option>
                            <option value="16:00:00">16:00</option>
                            <option value="17:00:00">17:00</option>
                            <option value="18:00:00">18:00</option>
                        </select></td>
                </tr>
                <tr>
                    <td><label for="booking_interval">Min. timer til:</label></td><td><input id="booking_interval" style="margin-left: 5px" name="booking_interval" type="number" min="0" /></td>
                </tr>
                <tr>
                <tr>
                    <td><label for="customer_name">Kundenavn:</label></td><td><input id="customer_name" style="margin-left: 5px" name="customer_name" type="text" /></td>
                </tr>
                <tr>
                    <td><label for="customer_email">Kunde e-mail:</label></td><td><input id="customer_email" style="margin-left: 5px" name="customer_email" type="text" /></td>
                </tr>
                <tr>
                    <td><label for="customer_phone_nr">Kunde tlf. nr.:</label></td><td><input id="customer_phone_nr" style="margin-left: 5px" name="customer_phone_nr" type="text" /></td>
                </tr>
                <tr>
                    <td><label for="comment">Kommentarer:</label></td><td><textarea id="comment" style="margin-left: 5px" class="comment" name="comment"></textarea></td>
                </tr>
            </table>
        </form>
        <button class="btn btn-default" style="margin-top: 5px" onclick="add_booking()">Tilføj</button> <button class="btn btn-default" style="margin-top: 5px" onclick="cancel_add_booking()">Fortryd</button>
        <hr class="ruler" />
    </div>
    <div class="admin_menu_form" id="edit_booking">
        <form>
            <table class="table">
                <thead>
                    <tr>
                        <th></th><th>Nuværende</th><th>Nyt indhold</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><label for="booking_id_old">Booking id:</label></td><td><input id="booking_id_old" name="booking_id_old" type="text" disabled></td>
                    </tr>
                    <tr>
                        <td><label for="item_old">Vare:</label></td><td><input id="item_old" name="item_old" type="text" disabled /></td><td><select id="item_new" name="item_new" ></select></td>
                    </tr>
                    <tr>
                        <td><label for="start_date_old">Startdato:</label></td><td><input id="start_date_old" name="start_date_old" type="text" disabled /></td><td><input id="start_date_new" name="start_date_new" type="text" /></td>
                    </tr>
                    <tr>
                        <td><label for="start_time_old">Starttid:</label></td><td><input id="start_time_old" name="start_time_old" type="text" disabled /></td><td><select id="start_time_new" name="start_time_new" type="text">
                                <option value="09:00:00">9:00</option>
                                <option value="10:00:00">10:00</option>
                                <option value="11:00:00">11:00</option>
                                <option value="12:00:00">12:00</option>
                                <option value="13:00:00">13:00</option>
                                <option value="14:00:00">14:00</option>
                                <option value="15:00:00">15:00</option>
                                <option value="16:00:00">16:00</option>
                                <option value="17:00:00">17:00</option>
                                <option value="18:00:00">18:00</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td><label for="end_date_old">Slutdato:</label></td><td><input id="end_date_old" name="end_date_old" type="text" disabled /></td><td><input id="end_date_new" name="end_date_new" type="text" /></td>
                    </tr>
                    <tr>
                        <td><label for="end_time_old">Sluttid:</label></td><td><input id="end_time_old" name="end_time_old" type="text" disabled /></td><td><select id="end_time_new" name="end_time_new" type="text" >
                                <option value="09:00:00">9:00</option>
                                <option value="10:00:00">10:00</option>
                                <option value="11:00:00">11:00</option>
                                <option value="12:00:00">12:00</option>
                                <option value="13:00:00">13:00</option>
                                <option value="14:00:00">14:00</option>
                                <option value="15:00:00">15:00</option>
                                <option value="16:00:00">16:00</option>
                                <option value="17:00:00">17:00</option>
                                <option value="18:00:00">18:00</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td><label for="booking_interval_new">Min. timer til:</label></td><td><input id="booking_interval_old" name="booking_interval_old" type="text" disabled="" /></td><td><input id="booking_interval_new" name="booking_interval_new" type="number" min="0" /></td>
                    </tr>
                    <tr>
                        <td><label for="customer_name_old">Kundenavn:</label></td><td><input id="customer_name_old" name="customer_name_old" type="text" disabled /></td><td><input id="customer_name_new" name="customer_name_new" type="text" /></td>
                    </tr>
                    <tr>
                        <td><label for="customer_email_old">Kunde e-mail:</label></td><td><input id="customer_email_old" name="customer_email_old" type="text" disabled /></td><td><input id="customer_email_new" name="customer_email_new" type="text" /></td>
                    </tr>
                    <tr>
                        <td><label for="customer_phone_nr_old">Kunde tlf. nr.:</label></td><td><input id="customer_phone_nr_old" name="customer_phone_nr_old" type="text" disabled /></td><td><input id="customer_phone_nr_new" name="customer_phone_nr_new" type="text" /></td>
                    </tr>
                    <tr>
                        <td><label for="comment_old">Kommentar:</label></td><td><textarea id="comment_old" name="comment_old" class="comment" disabled></textarea></td><td><textarea id="comment_new" name="comment_new" class="comment"></textarea></td>
                    </tr>
                    <tr>
                        <td><label for="status_old">Status:</label></td><td><input id="status_old"  name="status_old" type="text" disabled /></td><td><select id="status_new" name="status_new"></select></td>
                    </tr>
                </tbody>
            </table>
        </form>
        <button class="btn btn-default" onclick="edit_booking()">Bekræft rettelse</button> <button  class="btn btn-default" onclick="cancel_edit_booking()">Fortryd</button>
        <hr class="ruler" />
    </div>
</div>