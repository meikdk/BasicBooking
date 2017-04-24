<?php
/**
 * Created by PhpStorm.
 * User: chroes
 * Date: 01-09-2016
 * Time: 12:49
 */
?>
<div id="admin_container" class="wrap settings">
    <!--<div>
        <h2>Maks antal dage</h2>
        <form>
            <div class="settings">
                <label for="max_booking_count_current"><h4>Nuværende</h4></label><br />
                <input id="max_booking_count_current" name="max_booking_count_current" type="number" min="1" disabled />
            </div>
            <div class="settings">
                <label for="max_booking_count_new"><h4>Nyt indhold</h4></label><br />
                <input id="max_booking_count_new" name="max_booking_count_new" type="number" min="1" />
            </div>
        </form>
        <button class="btn btn-default" style="margin-top: 5px" onclick="update_max_booking_count()">Opdater</button> <button class="btn btn-default" style="margin-top: 5px" onclick="reset_max_booking_count()">Nulstil antal</button>
    </div>
    <br />-->
    <div>
        <h2>Auto reserverings e-mail</h2>
        <form>
            <div class="settings">
                <h4><label for="response_email_old">Nuværende</label></h4>
                <textarea id="response_email_old" disabled></textarea>
            </div>
            <div class="settings">
                <h4><label for="confirmation_email_new">Nyt indhold</label></h4>
                <textarea id="response_email_new"></textarea>
            </div>
            <div class="settings" >
                <h4>Brugbare tags:</h4>
                <ul>
                    <li><b>%item%</b> - Varen der udlejes.</li>
                    <li><b>%start_date%</b> - Startdato for udlejning.</li>
                    <li><b>%end_date%</b> - Slutdato for udlejning.</li>
                    <li><b>%customer_name%</b> - Navnet på lejer.</li>
                    <li><b>%customer_email%</b> - Lejers email adresse.</li>
                    <li><b>%customer_phone_nr%</b> - Lejers telefon nummer.</li>
                    <li><b>%comment%</b> - Kommentar til udlejning.</li>
                    <li><b>%site_name%</b> - Navnet på denne hjemmeside. Sættes i Indstillinger -> Generelt</li>
                    <li><b>%admin_email%</b> - Kontakt adresse. Sættes i Indstillinger -> Generelt</li>
                </ul>
            </div>
        </form>
        <button class="btn btn-default" onclick="update_auto_email()">Opdater</button> <button class="btn btn-default" onclick="reset_auto_email()">Nulstil e-mail</button>
    </div>
    <br />
    <div>
        <h2>Auto bekræftning e-mail</h2>
        <form>
            <div class="settings">
                <h4><label for="confirmation_email_old">Nuværende</label></h4>
                <textarea id="confirmation_email_old" disabled></textarea>
            </div>
            <div class="settings">
                <h4><label for="confirmation_email_new">Nyt indhold</label></h4>
                <textarea id="confirmation_email_new"></textarea>
            </div>
            <div class="settings" >
                <h4>Brugbare tags:</h4>
                <ul>
                    <li><b>%item%</b> - Varen der udlejes.</li>
                    <li><b>%start_date%</b> - Startdato for udlejning.</li>
                    <li><b>%end_date%</b> - Slutdato for udlejning.</li>
                    <li><b>%customer_name%</b> - Navnet på lejer.</li>
                    <li><b>%customer_email%</b> - Lejers email adresse.</li>
                    <li><b>%customer_phone_nr%</b> - Lejers telefon nummer.</li>
                    <li><b>%comment%</b> - Kommentar til udlejning.</li>
                    <li><b>%site_name%</b> - Navnet på denne hjemmeside. Sættes i Indstillinger -> Generelt</li>
                    <li><b>%admin_email%</b> - Kontakt adresse. Sættes i Indstillinger -> Generelt</li>
                </ul>
            </div>
        </form>
        <button class="btn btn-default" onclick="update_confirmation_email()">Opdater</button> <button class="btn btn-default" onclick="reset_confirmation_email()">Nulstil e-mail</button>
    </div>
    <br />
    <div>
        <h2>Auto afvisnings e-mail</h2>
        <form>
            <div class="settings">
                <h4><label for="rejection_email_old">Nuværende</label></h4>
                <textarea id="rejection_email_old" disabled></textarea>
            </div>
            <div class="settings">
                <h4><label for="rejection_email_new">Nyt indhold</label></h4>
                <textarea id="rejection_email_new"></textarea>
            </div>
            <div class="settings" >
                <h4>Brugbare tags:</h4>
                <ul>
                    <li><b>%item%</b> - Varen der udlejes.</li>
                    <li><b>%start_date%</b> - Startdato for udlejning.</li>
                    <li><b>%end_date%</b> - Slutdato for udlejning.</li>
                    <li><b>%customer_name%</b> - Navnet på lejer.</li>
                    <li><b>%customer_email%</b> - Lejers email adresse.</li>
                    <li><b>%customer_phone_nr%</b> - Lejers telefon nummer.</li>
                    <li><b>%comment%</b> - Kommentar til udlejning.</li>
                    <li><b>%site_name%</b> - Navnet på denne hjemmeside. Sættes i Indstillinger -> Generelt</li>
                    <li><b>%admin_email%</b> - Kontakt adresse. Sættes i Indstillinger -> Generelt</li>
                </ul>
            </div>
        </form>
        <button class="btn btn-default" onclick="update_rejection_email()">Opdater</button> <button class="btn btn-default" onclick="reset_rejection_email()">Nulstil e-mail</button>
    </div>
</div>