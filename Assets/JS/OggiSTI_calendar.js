// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI - Today in computer history
// Package: almanac
// Title: manage events extraction in almanac page
// File: javascriptApp.js
// Path: asset/js/
// Type: javascript
// Started: 2017-03-08
// Author(s): Nicolò Pratelli
// State: in use
//
//  Version history.
// - 2017.03.08 Nicolò Pratelli
// First version
// - 2017.12.09 Nicolò Pratelli
// Manage the extraction through url
// - 2017.12.10 Nicolò Pratelli
// Added the file and copyright information
// - 2019.05.04 Nicolò Pratelli
// Changed GUI of events
//
// ////////////////////////////////////////////////////////////////////////
//
// This file is part of software developed by the HMR Project
// Further information at: http://progettohmr.it
// Copyright (C) 2017 HMR Project, Nicolò Pratelli
//
// This is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published
// by the Free Software Foundation; either version 3.0 of the License,
// or (at your option) any later version.
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty
// of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU General Public License for more details.
// You should have received a copy of the GNU General Public License
// along with this program; if not, see <http://www.gnu.org/licenses/>.
//
// ////////////////////////////////////////////////////////////////////////


$(document).ready(function () {



    // Initialize datepicker
    $("#oggiSTI_picker").datepicker({
        dateFormat: "dd/mm/yy", // date format
        yearRange: "c-5:c+5", // years calendar range
        changeYear: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        showOn: "button",
        buttonImage: "../Assets/Img/logo-oggiSTI_ico.png", // icon of the calendar
        buttonImageOnly: true,
        buttonText: "Seleziona una data", // button text
        onSelect: function (dateText) {
            $("#oggiSTI_titoloStessoGiorno").html("");
            var selectedDate = $("#oggiSTI_picker").datepicker("getDate"); // assign date to selected date
            var dataSelezionata = this.value; // assign today date
            var eventDate = reformatDateToEng(dataSelezionata); // data convert in yyyy-mm-dd format
            // set url of the query that get event of choosen date by user
            var url = "../Assets/Api/getCalendarDateEvent.php";
            var count = 0;
            var panelId = 1;
            panels = "";
            // AJAX  call get event of choosen date by user
            $.getJSON(url, { "eventDate": eventDate }, function (result) {
                $.each(result, function (index, item) {
                    if (index == "status") {
                        // empty result, search other event in the week
                        panels = "Nessun evento in questo giorno";
                        $("#OggiSTI_calendarEventsQuery").html(panels);
                    } else {
                        // there is an event
                        // append all event 
                        var eventDate = item.Date;
                        var eventDateArray = eventDate.split('-');
                        var eventYear = eventDateArray[0];
                        panels += "<div>(" + eventYear + ") <a href='../?id=" + item.Id + "'>" + item.ItaTitle + "</a></div>";
                        // if (count == 0) {
                        //     modificaInfoEvento(item.Date, item.ItaTitle, item.ItaAbstract, item.ItaDescription, item.TextReferences, item.Editors, item.Reviser_1, item.Reviser_2, item.ImageCaption, item.Image);
                        //     $("#oggiSTI_sopraTitolo").css("visibility", "hidden");
                        //     var eventId = item.Id;
                        //     var urlGet = "Assets/Api/updateCounter.php";
                        //     //chiamata AJAX
                        //     $.get(urlGet, { "eventId": eventId });
                        // } else {
                        //     $("#oggiSTI_titoloStessoGiorno").html("Altri eventi del giorno");
                        //     var eventDate = item.Date;
                        //     var eventDateArray = eventDate.split('-');
                        //     var eventYear = eventDateArray[0];
                        //     panels += '<div class="panel panel-default"><div class="panel-heading panel-heading-custom"><h4 class="panel-title"><a data-toggle="collapse" href="#collapse' + panelId + '"><table><tr><td>' + eventYear + '</td></tr><tr><td>' + item.ItaTitle + '</td></tr></table></a></h4></div><div id="collapse' + panelId + '" class="panel-collapse collapse"><div class="panel-body">' + item.ItaAbstract + '</div><a id="evento-' + item.Id + '" class="oggiSTI_apriEvento">apri evento</a></div></div>';
                        //     panelId++;
                        // }
                        $("#OggiSTI_calendarEventsQuery").html(panels);
                    }
                });
            });
        }
    }).on("change", function () {
        //$("#messaggio").html("Got change event from field");
    }, $.datepicker.regional["it"]);


    $("#oggiSTI_visualizza_eventi").on("click", ".cellTableEventsFull", function () {
        var dayOrdinal = $(this).attr("id");
        var day = calcolaGiornoDaOrdinale(parseInt(dayOrdinal));
        var eventDateArray = day.split('-');
        var eventDay = eventDateArray[0];
        var eventMonth = eventDateArray[1];
        panels = "";
        var url = "../Assets/Api/getEventsByDate.php";
        $.getJSON(url, { "eventDay": eventDay, "eventMonth": eventMonth }, function (result) {
            $.each(result, function (index, item) {
                if (index == "status") {
                    // Empty
                } else {
                    var eventDate = item.Date;
                    var eventDateArray = eventDate.split('-');
                    var eventYear = eventDateArray[0];
                    panels += "<div>(" + eventYear + ") <a href='../?id=" + item.Id + "'>" + item.ItaTitle + "</a></div>";
                    $("#oggiSTI_picker").val(reformatDateToIta(eventDate));
                }
            });
            $("#OggiSTI_calendarEventsQuery").html(panels);
        });

    });



});
