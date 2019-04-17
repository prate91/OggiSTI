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
// - 2017.12.10 Nicolò Pratelli
// Added the file and copyright information
// - 2017.12.09 Nicolò Pratelli
// Manage the extraction through url
// - 2017.03.08 Nicolò Pratelli
// First version
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


//var eventDate="";
var panels = "";

// update views counter
function updateCounter(id) {
    var eventId = id;
    var urlGet = "Assets/Api/updateCounter.php";
    $.get(urlGet, { "eventId": eventId });
}

function getSameDayEvents(eventId) {
    var url = "Assets/Api/getSameDayEvents.php";
    $.getJSON(url, { "eventId": eventId }, function (result) {
        $.each(result, function (index, item) {
            if (index == "status") {
                $("#oggiSTI_eventiLaterali").html(panels);
            } else {
                var eventDate = item.Date;
                var eventDateArray = eventDate.split('-');
                var eventYear = eventDateArray[0];
                panels += '<div class="itemLateral"><a href=?id=' + item.Id + '>' + eventYear + ' - ' + item.ItaTitle + '</a></div>'
            }
        });
        $("#oggiSTI_titoloStessoGiorno").html("Altri eventi nello stesso giorno");
        $("#oggiSTI_eventiLaterali").html(panels);
    });

}


function getNearestEvent(giorno, mese, count) {
    var ordinale = calcolaOrdinaleGiorno(giorno, mese);
    ordinale = ordinale + count;
    var data = calcolaGiornoDaOrdinale(ordinale);
    var eventDateArray = data.split('-');
    var eventDay = eventDateArray[0];
    var eventMonth = eventDateArray[1];

    var url = "Assets/Api/getEventsByDate.php";
    $.getJSON(url, { "eventDay": eventDay, "eventMonth": eventMonth }, function (result) {
        $.each(result, function (index, item) {
            if (index == "status") {
                // $("#oggiSTI_eventiLaterali").html(panels);
                if (count % 2 == 0) {
                    count = -1 * count;
                    count = count + 1;
                } else {
                    count = count + 1;
                    count = -1 * count;
                }
                getNearestEvent(eventDay, eventMonth, count);
            } else {
                var eventDate = item.Date;
                var eventDateArray = eventDate.split('-');
                var eventYear = eventDateArray[0];
                var eventMonth = eventDateArray[1];
                var eventDay = eventDateArray[2];
                panels += '<div class="itemLateral"><a href=?id=' + item.Id + '>' + eventYear + '/' + eventMonth + '/' + eventDay + ' - ' + item.ItaTitle + '</a></div>'
            }
        });
        $("#oggiSTI_titoloStessoGiorno").html("Eventi nel giorno più vicino");
        $("#oggiSTI_eventiLaterali").html(panels);
    });

}

// 1. L'evento di oggi (o della ricerca) c'è.
// Se ci sono altri eventi del giorno si segnalano in ordine cronologico.
// Non si segnalano altri eventi più o meno vicini.

// 2. L'evento di oggi (o della ricerca) non c'è.
// Si segnala l'assenza per onestà.
// Si segnalano gli eventi del giorno più vicino in ordine cronologico, in
// caso di equidistanza in avanti e in in dietro si sceglie il futuro.

// get today event
//      there is:
//          get other possible events in the same day
//      there isn't:
//          report that is missing
//          get the events in the nearest day
//          (in case of equidistance, future is better)


// get event by url
//      there is:
//          get other possible events in the same day
//      there isn't:
//          report that is missing
//          get the events in the nearest day
//          (in case of equidistance, future is better)




$(document).ready(function () {


    // extract event by id
    var id = getUrlParameter('id');
    if (id) {
        var url = "Assets/Api/getEventById.php";
        panels = "";
        //chiamata AJAX
        $.getJSON(url, { "eventId": id }, function (result) {
            $.each(result, function (index, item) {
                if (index == "status") {
                    pulisciCampi();
                    // Load home page
                    window.location = window.location.href.split("?")[0];
                } else {
                    modificaInfoEvento(item.Date, item.ItaTitle, item.ItaAbstract, item.ItaDescription, item.TextReferences, item.Editors, item.Reviser_1, item.Reviser_2, item.ImageCaption, item.Image);
                    $("#oggiSTI_sopraTitolo").css("visibility", "hidden");
                    $("#oggiSTI_giornoDiverso").html("");
                    $("#oggiSTI_meseDiverso").html("");
                    updateCounter(item.Id);

                    // get other possible events in the same day
                    getSameDayEvents(item.Id);

                }
            });
        });
    } else {
        var data = new Date();
        var gg, mm, aaaa;
        giorno = data.getDay();
        gg = data.getDate();
        mm = (data.getMonth()) + 1;
        numberMonth = mm;
        aaaa = data.getFullYear();
        var dataOggi = aaaa + "-" + mm + "-" + gg;
        mm = convertiMesi(mm);
        $("#oggiSTI_dataGiorno").html(gg);
        $("#oggiSTI_giornoDiverso").html(gg);
        $("#oggiSTI_dataMese").html(mm);
        $("#oggiSTI_meseDiverso").html(mm);

        //var url = "asset/api/controllaEventiGiornalieri.php"
        $.get("Assets/Api/checkTodayEvents.php", function (data) {
            if (data == 0) {
                pulisciCampi();
                // get the events in the nearest day
                getNearestEvent(gg, numberMonth, 1);
            } else {
                var eventDate = dataOggi;
                var url = "Assets/Api/getTodayEvent.php";
                panels = "";
                //chiamata AJAX
                $.getJSON(url, function (result) {
                    $.each(result, function (index, item) {
                        modificaInfoEvento(item.Date, item.ItaTitle, item.ItaAbstract, item.ItaDescription, item.TextReferences, item.Editors, item.Reviser_1, item.Reviser_2, item.ImageCaption, item.Image);
                        updateCounter(item.Id);
                        // get other possible events in the same day
                        getSameDayEvents(item.Id);
                    });
                });
            }

        });
    }


    // // Initialize datepicker
    // $("#oggiSTI_picker").datepicker({
    //     dateFormat: "dd/mm/yy", // date format
    //     yearRange: "c-5:c+5", // years calendar range
    //     changeYear: true,
    //     showOtherMonths: true,
    //     selectOtherMonths: true,
    //     showOn: "button",
    //     buttonImage: "Assets/Img/logo-oggiSTI_ico.png", // icon of the calendar
    //     buttonImageOnly: true,
    //     buttonText: "Seleziona una data", // button text
    //     onSelect: function (dateText) {
    //         $("#oggiSTI_titoloStessoGiorno").html("");
    //         var selectedDate = $("#oggiSTI_picker").datepicker("getDate"); // assign date to selected date
    //         var msec = Date.parse(selectedDate); // data convert
    //         var i = new Date(estremoInf(msec));  // get first day of the week
    //         var s = new Date(estremoSup(msec));  // get last day of the week
    //         var lowerDate = formatDate(i);
    //         var upperDate = formatDate(s);
    //         var dataSelezionata = this.value; // assign today date
    //         var eventDate = reformatDateToEng(dataSelezionata); // data convert in yyyy-mm-dd format
    //         // set url of the query that get event of choosen date by user
    //         var url = "Assets/Api/getCalendarDateEvent.php";
    //         var count = 0;
    //         var panelId = 1;
    //         panels = "";
    //         // AJAX  call get event of choosen date by user
    //         $.getJSON(url, { "eventDate": eventDate }, function (result) {
    //             $.each(result, function (index, item) {
    //                 if (index == "status") {
    //                     // empty result, search other event in the week
    //                     var d = new Date();
    //                     var arrayDataSelezionata = dataSelezionata.split('/');
    //                     var eventDay = arrayDataSelezionata[0];
    //                     var eventMonth = arrayDataSelezionata[1];
    //                     d.setDate(arrayDataSelezionata[0]);
    //                     d.setMonth(arrayDataSelezionata[1] - 1);
    //                     eventDay = convertiGiorni(eventDay);
    //                     eventMonth = convertiMesi(eventMonth);
    //                     $("#oggiSTI_sopraTitolo").html("Nella stessa settimana del <span id='oggiSTI_giornoDiverso'></span> <span id='oggiSTI_meseDiverso'></span>");
    //                     $("#oggiSTI_dataGiorno").html(eventDay);
    //                     $("#oggiSTI_dataMese").html(eventMonth);
    //                     $("#oggiSTI_giornoDiverso").html(eventDay);
    //                     $("#oggiSTI_meseDiverso").html(eventMonth);
    //                     var weekEventCount = 0;
    //                     var weekPanelId = 1;
    //                     panels = "";
    //                     // set url of other event in the week
    //                     var url = "Assets/Api/getWeekEvents.php";
    //                     $.getJSON(url, { "lowerDate": lowerDate, "upperDate": upperDate }, function (result) {
    //                         $.each(result, function (index, item) {
    //                             if (index == "status") {
    //                                 // empty result
    //                                 pulisciCampi();
    //                             } else {
    //                                 if (weekEventCount == 0) {
    //                                     modificaInfoEvento(item.Date, item.ItaTitle, item.ItaAbstract, item.ItaDescription, item.TextReferences, item.Editors, item.Reviser_1, item.Reviser_2, item.ImageCaption, item.Image);
    //                                     $("#oggiSTI_sopraTitolo").css("visibility", "visible");
    //                                     var eventId = item.Id;
    //                                     // update counter of the event
    //                                     var urlGet = "Assets/Api/updateCounter.php";
    //                                     $.get(urlGet, { "eventId": eventId });
    //                                 } else {
    //                                     $("#oggiSTI_titoloStessoGiorno").html("Altri eventi della settimana");
    //                                     var eventDate = item.Date;
    //                                     var eventDateArray = eventDate.split('-');
    //                                     var eventYear = eventDateArray[0];
    //                                     panels += '<div class="panel panel-default"><div class="panel-heading panel-heading-custom"><h4 class="panel-title"><a data-toggle="collapse" href="#collapse' + weekPanelId + '"><table><tr><td>' + eventYear + '</td></tr><tr><td>' + item.ItaTitle + '</td></tr></table></a></h4></div><div id="collapse' + weekPanelId + '" class="panel-collapse collapse"><div class="panel-body">' + item.ItaAbstract + '</div><a id="evento-' + item.Id + '" class="oggiSTI_apriEvento">apri evento</a></div></div>';
    //                                     weekPanelId++;
    //                                 }
    //                                 $("#oggiSTI_eventiLaterali").html(panels);
    //                                 weekEventCount++
    //                             }
    //                         });
    //                     });
    //                 } else {
    //                     // there is an event
    //                     if (count == 0) {
    //                         modificaInfoEvento(item.Date, item.ItaTitle, item.ItaAbstract, item.ItaDescription, item.TextReferences, item.Editors, item.Reviser_1, item.Reviser_2, item.ImageCaption, item.Image);
    //                         $("#oggiSTI_sopraTitolo").css("visibility", "hidden");
    //                         var eventId = item.Id;
    //                         var urlGet = "Assets/Api/updateCounter.php";
    //                         //chiamata AJAX
    //                         $.get(urlGet, { "eventId": eventId });
    //                     } else {
    //                         $("#oggiSTI_titoloStessoGiorno").html("Altri eventi del giorno");
    //                         var eventDate = item.Date;
    //                         var eventDateArray = eventDate.split('-');
    //                         var eventYear = eventDateArray[0];
    //                         panels += '<div class="panel panel-default"><div class="panel-heading panel-heading-custom"><h4 class="panel-title"><a data-toggle="collapse" href="#collapse' + panelId + '"><table><tr><td>' + eventYear + '</td></tr><tr><td>' + item.ItaTitle + '</td></tr></table></a></h4></div><div id="collapse' + panelId + '" class="panel-collapse collapse"><div class="panel-body">' + item.ItaAbstract + '</div><a id="evento-' + item.Id + '" class="oggiSTI_apriEvento">apri evento</a></div></div>';
    //                         panelId++;
    //                     }
    //                     $("#oggiSTI_eventiLaterali").html(panels);
    //                     count++;
    //                 }
    //             });
    //         });
    //     }
    // }).on("change", function () {
    //     //$("#messaggio").html("Got change event from field");
    // }, $.datepicker.regional["it"]);


    // // Open lateral events
    // $("#oggiSTI_eventiLaterali").on("click", ".oggiSTI_apriEvento", function () {
    //     var eventIdStr = $(this).attr("id");
    //     idArr = eventIdStr.split("-");
    //     var eventId = idArr[1];
    //     var url = "Assets/Api/getLateralEvent.php";
    //     var panelId = 1;
    //     panels = "";
    //     // Ajax call
    //     $.getJSON(url, { "eventId": eventId }, function (result) {
    //         $.each(result, function (index, item) {
    //             modificaInfoEvento(item.Date, item.ItaTitle, item.ItaAbstract, item.ItaDescription, item.TextReferences, item.Editors, item.Reviser_1, item.Reviser_2, item.ImageCaption, item.Image);
    //             $("#oggiSTI_sopraTitolo").css("visibility", "hidden");
    //             $("#oggiSTI_giornoDiverso").html("");
    //             $("#oggiSTI_meseDiverso").html("");
    //             var eventId = item.Id;
    //             var urlGet = "Assets/Api/updateCounter.php";
    //             //chiamata AJAX
    //             $.get(urlGet, { "eventId": eventId });
    //             $("#oggiSTI_titoloStessoGiorno").html("");
    //             var url = "Assets/Api/getLateralEvents.php";
    //             //chiamata AJAX
    //             $.getJSON(url, { "eventId": eventId }, function (result) {
    //                 $.each(result, function (index, item) {
    //                     if (index == "status") {
    //                         $("#oggiSTI_titoloStessoGiorno").html("");
    //                         $("#oggiSTI_eventiLaterali").html(panels);
    //                     } else {
    //                         $("#oggiSTI_titoloStessoGiorno").html("Altri eventi del giorno");
    //                         var eventDate = item.Date;
    //                         var eventDateArray = eventDate.split('-');
    //                         var eventYear = eventDateArray[0];
    //                         panels += '<div class="panel panel-default"><div class="panel-heading panel-heading-custom"><h4 class="panel-title"><a data-toggle="collapse" href="#collapse' + panelId + '"><table><tr><td>' + eventYear + '</td></tr><tr><td>' + item.ItaTitle + '</td></tr></table></a></h4></div><div id="collapse' + panelId + '" class="panel-collapse collapse"><div class="panel-body">' + item.ItaAbstract + '</div><a id="evento-' + item.Id + '" class="oggiSTI_apriEvento">apri evento</a></div></div>';
    //                         panelId++;
    //                     }
    //                 });
    //                 $("#oggiSTI_eventiLaterali").html(panels);
    //             });
    //         });
    //     });
    // });


    // // extract event by id
    // var id = getUrlParameter('id');
    // if (id) {
    //     var url = "Assets/Api/getLateralEvent.php";
    //     var panelId = 1;
    //     panels = "";
    //     //chiamata AJAX
    //     $.getJSON(url, { "eventId": id }, function (result) {
    //         $.each(result, function (index, item) {
    //             if (index == "status") {
    //                 pulisciCampi();
    //             } else {
    //                 modificaInfoEvento(item.Date, item.ItaTitle, item.ItaAbstract, item.ItaDescription, item.TextReferences, item.Editors, item.Reviser_1, item.Reviser_2, item.ImageCaption, item.Image);
    //                 $("#oggiSTI_sopraTitolo").css("visibility", "hidden");
    //                 $("#oggiSTI_giornoDiverso").html("");
    //                 $("#oggiSTI_meseDiverso").html("");
    //                 var eventId = item.Id;
    //                 var urlGet = "asset/api/updateCounter.php";
    //                 //chiamata AJAX
    //                 $.get(urlGet, { "eventId": eventId });
    //                 var url = "Assets/Api/getLateralEvents.php";
    //                 //chiamata AJAX
    //                 $.getJSON(url, { "eventId": eventId }, function (result) {
    //                     $.each(result, function (index, item) {
    //                         if (index == "status") {
    //                             $("#oggiSTI_eventiLaterali").html(panels);
    //                         } else {
    //                             var eventDate = item.Date;
    //                             var eventDateArray = eventDate.split('-');
    //                             var eventYear = eventDateArray[0];
    //                             panels += '<div class="panel panel-default"><div class="panel-heading panel-heading-custom"><h4 class="panel-title"><a data-toggle="collapse" href="#collapse' + panelId + '"><table><tr><td>' + eventYear + '</td></tr><tr><td>' + item.ItaTitle + '</td></tr></table></a></h4></div><div id="collapse' + panelId + '" class="panel-collapse collapse"><div class="panel-body">' + item.ItaAbstract + '</div><a id="evento-' + item.Id + '" class="oggiSTI_apriEvento">apri evento</a></div></div>';
    //                             panelId++;
    //                         }
    //                     });
    //                     $("#oggiSTI_titoloStessoGiorno").html("Altri eventi del giorno");
    //                     $("#oggiSTI_eventiLaterali").html(panels);
    //                 });
    //             }
    //         });
    //     });
    // } else {
    //     var data = new Date();
    //     var gg, mm, aaaa;
    //     giorno = data.getDay();
    //     gg = data.getDate();
    //     mm = (data.getMonth()) + 1;
    //     aaaa = data.getFullYear();
    //     var dataOggi = aaaa + "-" + mm + "-" + gg;
    //     mm = convertiMesi(mm);
    //     $("#oggiSTI_dataGiorno").html(gg);
    //     $("#oggiSTI_giornoDiverso").html(gg);
    //     //$("#dataGiornoSettimana").html(giorno);
    //     $("#oggiSTI_dataMese").html(mm);
    //     $("#oggiSTI_meseDiverso").html(mm);
    //     //$("#dataAnno").html(aaaa);
    //     var msec = Date.parse(data);
    //     var i = new Date(estremoInf(msec));
    //     var s = new Date(estremoSup(msec));
    //     var lowerDate = formatDate(i);
    //     var upperDate = formatDate(s);

    //     //var url = "asset/api/controllaEventiGiornalieri.php"
    //     $.get("Assets/Api/checkTodayEvents.php", function (data) {
    //         if (data == 0) {
    //             var weekEventCount = 0;
    //             var weekPanelId = 1;
    //             panels = "";
    //             var url = "Assets/Api/getWeekEvents.php";
    //             $.getJSON(url, { "lowerDate": lowerDate, "upperDate": upperDate }, function (result) {
    //                 $.each(result, function (index, item) {
    //                     if (index == "status") {
    //                         pulisciCampi();
    //                     } else {
    //                         if (weekEventCount == 0) {
    //                             modificaInfoEvento(item.Date, item.ItaTitle, item.ItaAbstract, item.ItaDescription, item.TextReferences, item.Editors, item.Reviser_1, item.Reviser_2, item.ImageCaption, item.Image);
    //                             $("#oggiSTI_sopraTitolo").css("visibility", "visible");
    //                             var eventId = item.Id;
    //                             var urlGet = "Assets/Api/updateCounter.php";
    //                             //chiamata AJAX
    //                             $.get(urlGet, { "eventId": eventId });
    //                         } else {
    //                             var eventDate = item.Date;
    //                             var eventDateArray = eventDate.split('-');
    //                             var eventYear = eventDateArray[0];
    //                             panels += '<div class="panel panel-default"><div class="panel-heading panel-heading-custom"><h4 class="panel-title"><a data-toggle="collapse" href="#collapse' + weekPanelId + '"><table><tr><td>' + eventYear + '</td></tr><tr><td>' + item.ItaTitle + '</td></tr></table></a></h4></div><div id="collapse' + weekPanelId + '" class="panel-collapse collapse"><div class="panel-body">' + item.ItaAbstract + '</div><a id="evento-' + item.Id + '" class="oggiSTI_apriEvento">apri evento</a></div></div>';
    //                             weekPanelId++;
    //                         }
    //                         $("#oggiSTI_titoloStessoGiorno").html("Altri eventi della settimana");
    //                         $("#oggiSTI_eventiLaterali").html(panels);
    //                         weekEventCount++
    //                     }
    //                 });
    //             });
    //         } else {
    //             var eventDate = dataOggi;
    //             var url = "Assets/Api/getTodayEvent.php";
    //             var panelId = 1;
    //             panels = "";
    //             //chiamata AJAX
    //             $.getJSON(url, function (result) {
    //                 $.each(result, function (index, item) {
    //                     if (index == "status") {
    //                         $.get("Assets/Api/estraiEventoGiornaliero.php", function (data) {
    //                             location.reload();
    //                         });
    //                         //window.location = "asset/api/estraiEventoGiornaliero.php";
    //                     } else {
    //                         modificaInfoEvento(item.Date, item.ItaTitle, item.ItaAbstract, item.ItaDescription, item.TextReferences, item.Editors, item.Reviser_1, item.Reviser_2, item.ImageCaption, item.Image);
    //                         var eventId = item.Id;
    //                         var urlGet = "Assets/Api/updateCounter.php";
    //                         //chiamata AJAX
    //                         $.get(urlGet, { "eventId": eventId });
    //                         var url = "Assets/Api/getLateralEvents.php";
    //                         //chiamata AJAX
    //                         $.getJSON(url, { "eventId": eventId }, function (result) {
    //                             $.each(result, function (index, item) {
    //                                 if (index == "status") {
    //                                     $("#oggiSTI_eventiLaterali").html(panels);
    //                                 } else {
    //                                     var eventDate = item.Date;
    //                                     var eventDateArray = eventDate.split('-');
    //                                     var eventYear = eventDateArray[0];
    //                                     panels += '<div class="panel panel-default"><div class="panel-heading panel-heading-custom"><h4 class="panel-title"><a data-toggle="collapse" href="#collapse' + panelId + '"><table><tr><td>' + eventYear + '</td></tr><tr><td>' + item.ItaTitle + '</td></tr></table></a></h4></div><div id="collapse' + panelId + '" class="panel-collapse collapse"><div class="panel-body">' + item.ItaAbstract + '</div><a id="evento-' + item.Id + '" class="oggiSTI_apriEvento">apri evento</a></div></div>';
    //                                     panelId++;
    //                                 }
    //                             });
    //                             $("#oggiSTI_titoloStessoGiorno").html("Altri eventi del giorno");
    //                             $("#oggiSTI_eventiLaterali").html(panels);
    //                         });
    //                     }
    //                 });
    //             });
    //         }

    //     });
    // }


});