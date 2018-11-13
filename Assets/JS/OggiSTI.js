
// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI - Today in computer history
// Package:  OggiSTI administration
// Title: script for control OggiSTI administration
// File: javascript.js
// Path: asset/js
// Type: javascript
// Started: 2017-03-08
// Author(s): Nicolò Pratelli
// State: in use
//
// Version history.
// - 2017.03.08 Nicolò Pratelli
// First version
// - 2017.12.05 Nicolò Pratelli
// Added the file and copyright information
// - 2018.02.26 Nicolò Pratelli
// Update the method for extract events
// - 2018.06.06 Nicolò Pratelli
// Open edit page and event page in the same window
// - 2018.10.25 Nicolò
// Added facebook icon control in the publicated events table
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

var intestazione_tabella = "";
var intestazione_tabella_redazione = "";
var intestazione_tabella_approvazione = "";
var intestazione_tabella_utenti = "";
var intestazione_tabella_temp = "";
var fbIcon = "";
//var intestazione = "";


$(document).ready(function () {

    var pathname = window.location.pathname;
    var state = "";


    // Get the path name of the page

    if (/OggiSTI_savedEvents.php/.test(pathname)) {
        state = "Salvato";
    }
    if (/OggiSTI_redactionEvents.php/.test(pathname)) {
        state = "Redazione";
    }
    if (/OggiSTI_reviewedEvents.php/.test(pathname)) {
        state = "Approvazione";
    }
    if (/OggiSTI_publishedEvents.php/.test(pathname)) {
        state = "Pubblicato";
    }
    if (/OggiSTI_allEvents.php/.test(pathname)) {
        state = "Tutti";
    }


    // Built events tables

    var url = "../Api/extractEvents.php"
    $.getJSON(url, { "state": state }, function (result) {
        $.each(result, function (index, item) {
            if (state == "Tutti") {
                // tables composed by all events
                fbIcon = "";
                if (item.fb == 1) {
                    fbIcon = '<img src="../Img/iconFacebook.png" class="fbIcon" alt="FB Icon">'
                }
                var riga = "<tr class='item'>" +
                    "<td>" + item.Id + "</td>" +
                    "<td>" + formatDatemmddyyyy(item.Date) + "</td>" +
                    "<td><a href='../../OggiSTI_preview.php?eventId=" + item.Id + "&stateId=" + item.State + "' target='_blank'>" + item.ItaTitle + "</a></td>" +
                    "<td>" + item.State + " " + fbIcon + " " + item.Views + "</td>" +
                    "<td>" + item.Editors + "</td>" +
                    "<td><button type='button' id='" + item.State + "-" + item.Id + "' class='btn btn-default btnEvento glyphicon glyphicon glyphicon-edit'> </button></td>" +
                    "</tr>";
            } else if (state == "Pubblicato") {
                fbIcon = "";
                if (item.fb == 1) {
                    fbIcon = '<img src="../Img/iconFacebook.png" class="fbIcon" alt="FB Icon">'
                }
                var riga = "<tr class='item'>" +
                    "<td>" + item.Id + "</td>" +
                    "<td>" + formatDatemmddyyyy(item.Date) + "</td>" +
                    "<td><a href='../../?id=" + item.Id + "' target='_blank'>" + item.ItaTitle + "</a></td>" +
                    "<td>" + item.State + " " + fbIcon + "</td>" +
                    "<td>" + item.Editors + "</td>" +
                    "<td><button type='button' id='" + state + "-" + item.Id + "' class='btn btn-default btnEvento glyphicon glyphicon glyphicon-edit'> </button></td>" +
                    "</tr>";
            } else {
                if (index == "status") {
                    riga = "";
                } else if (index == "details") {
                    riga = "";
                } else {
                    // all the others tables
                    var riga = "<tr class='item'>" +
                        "<td>" + item.Id + "</td>" +
                        "<td>" + formatDatemmddyyyy(item.Date) + "</td>" +
                        "<td><a href='../../OggiSTI_preview.php?eventId=" + item.Id + "&stateId=" + item.State + "' target='_blank'>" + item.ItaTitle + "</a></td>" +
                        "<td>" + item.State + "</td>" +
                        "<td>" + item.Editors + "</td>" +
                        "<td><button type='button' id='" + state + "-" + item.Id + "' class='btn btn-default btnEvento glyphicon glyphicon glyphicon-edit'> </button></td>" +
                        "</tr>";
                }
            }
            // built the table 
            intestazione_tabella += riga;
            $("#eventListBody").html(intestazione_tabella);

        });
        // set 25 events per table
        $('#eventList').DataTable({
            "pageLength": 25
        });
    });


    // event click that open event page
    $("table").on("click", ".btnEvento", function () {
        var idTotal = $(this).attr("id");
        var idTotalArray = idTotal.split("-");
        var idState = idTotalArray[0];
        var idEvent = idTotalArray[1];
        var indirizzo = "OggiSTI_event.php?eventId=" + idEvent + "&stateId=" + idState;
        window.open(indirizzo, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,width=900,height=1000");
    });

    // event click that open preview page
    $("table").on("click", ".btnPreview", function () {
        var idTotal = $(this).attr("id");
        var idTotalArray = idTotal.split("-");
        var idState = idTotalArray[0];
        var idEvent = idTotalArray[1];
        var indirizzo = "../../OggiSTI_preview.php?eventId=" + idEvent + "&stateId=" + idState;
        window.open(indirizzo, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,width=900,height=1000");
    });

    // event click that open edit page
    $("#modificaEvento").click(function () {
        var eventId = $("#idEvento").text();
        var indirizzo = "OggiSTI_edit.php?eventId=" + eventId + "&message=modifica";
        window.open(indirizzo, "_self", "toolbar=yes,scrollbars=yes,resizable=yes,width=900,height=1000");
        //location.assign(indirizzo);
        //location.href = "modifica.php?eventId="+eventId+"";
    });

    // event click that open edit page for quickly update
    $("#modificaVeloce").click(function () {
        var eventId = $("#idEvento").text();
        var stateId = $("#idStato").text();
        var indirizzo = "OggiSTI_edit.php?eventId=" + eventId + "&message=modificaVeloce&stateId=" + stateId + "";
        window.open(indirizzo, "_self", "toolbar=yes,scrollbars=yes,resizable=yes,width=900,height=1000");
        //location.assign(indirizzo);
        //location.href = "modifica.php?eventId="+eventId+"";
    });



    // $("#modalEliminaEvento").on('click','#eliminaDef',function(){
    //     var eventId = $('.hidden_eventId').val();
    //     window.location = "../api/eliminaEvento.php?eventId="+eventId+"";

    // });
    // $("#modalEliminaEventoPubblicato").on('click','#eliminaDef',function(){
    //     var eventId = $('.hidden_eventId').val();
    //     window.location = "../api/eliminaEventoPubblicato.php?eventId="+eventId+"";

    // });
    // $("#modalEliminaEventoTutti").on('click','#eliminaDef',function(){
    //     var eventId = $('.hidden_eventId').val();
    //     window.location = "../api/eliminaEventoTutti.php?eventId="+eventId+"";

    // });



    // ///////////////////
    // Edit page
    // //////////////////

    // disable enter 

    $('#addEvent').keypress(function (tasto) {

        if (tasto.which == 13) {

            return false;

        }

    });

    $("#date").focus(function () {
        $('#formData').removeClass("has-error has-feedback");
        $("#helpDate").html("");
        $('#glyphiconDate').removeClass("glyphicon glyphicon-remove form-control-feedback");
    });

    $("#title_ita").focus(function () {
        $('#formItaTitle').removeClass("has-error has-feedback");
        $("#helpTitleIta").html("<span id='helpTitleIta' class='help-block'>La dimensione massima consigliata è di 70 caratteri. Consultare le <a href='../../LineeGuida/#LGTitolo' target='_blank'>linee guida sul titolo</a> per maggiori informazioni</span>");
        $('#glyphiconTitleIta').removeClass("glyphicon glyphicon-remove form-control-feedback");
    });

    $("#abstr_ita").focus(function () {
        $('#formItaAbstract').removeClass("has-error has-feedback");
        $("#helpAbstrIta").html("La dimensione massima consigliata è di 280 caratteri");
        $('#glyphiconAbstrIta').removeClass("glyphicon glyphicon-remove form-control-feedback");
    });

    $("#applica").click(function () {
        tinyMCE.triggerSave();
        var data = $('[name="eventDate"]').val();
        var titolo_ita = $('[name="itaTitle"]').val();
        var abstr_ita = $("#itaAbstract").val();
        var desc_ita = $('[name="itaDescription"]').val();
        var campi = "";
        if (!checkDate(data)) {
            campi = campi + "<strong>Data</strong><br/>";

            //$('#formData').addClass("has-error has-feedback");
            //$('body,html').animate({scrollTop:0},800);
            //$("#helpDate").html("La data è inserita in un formato non valido, usare il formato dd/mm/yyyy");
            //$('#glyphiconDate').addClass("glyphicon glyphicon-remove form-control-feedback");
        }
        if (titolo_ita == "") {
            campi = campi + "<strong>Titolo</strong><br/>";
            //$('body,html').animate({scrollTop:0},800);
            //$('#formItaTitle').addClass("has-error has-feedback");
            //$("#helpTitleIta").html("Questo campo non può rimanere vuoto");
            //$('#glyphiconTitleIta').addClass("glyphicon glyphicon-remove form-control-feedback");
        }
        if (abstr_ita == "") {
            campi = campi + "<strong>Descrizione Breve</strong><br/>";
            //$('body,html').animate({scrollTop:$('#formItaAbstract').offset().top},800);
            //$('#formItaAbstract').addClass("has-error has-feedback");
            //$("#helpAbstrIta").html("Questo campo non può rimanere vuoto");
        }
        if (campi != "") {
            campi = "I seguenti campi non possono essere vuoti:<br/>" + campi;
            $("#campiMancanti").html(campi);
            $("#campiMancanti").show();
        }

    });

    // Count characters

    // italian title
    $outMax = 140;
    $('#title_ita').keyup(function () {
        $max = $outMax;
        $len = $('#title_ita').val().length;
        $('#countBox_title_ita').text($max - $len);
        if ($max - $len < 30) {
            $('#countBox_title_ita').css('color', '#C30');
            $('#formItaTitle').addClass("has-warning has-feedback");
            $('#glyphiconTitleIta').addClass("glyphicon glyphicon-warning-sign form-control-feedback");
        }
        if ($max - $len >= 30) {
            $('#countBox_title_ita').css('color', '#737373');
            $('#formItaTitle').removeClass("has-warning has-feedback");
            $('#glyphiconTitleIta').removeClass("glyphicon glyphicon-warning-sign form-control-feedback");
        }
        if ($max - $len < 0) {
            $str = $('#title_ita').val();
            $str = $str.substring(0, $max);
            $('#title_ita').val($str);
            $('#countBox_title_ita').text(0);
        }
    });

    // english title
    $outMax = 140;
    $('#title_eng').keyup(function () {
        $max = $outMax;
        $len = $('#title_eng').val().length;
        $('#countBox_title_eng').text($max - $len);
        if ($max - $len < 30) {
            $('#countBox_title_eng').css('color', '#C30');
            $('#formEngTitle').addClass("has-warning has-feedback");
            $('#glyphiconTitleEng').addClass("glyphicon glyphicon-warning-sign form-control-feedback");
        }
        if ($max - $len >= 30) {
            $('#countBox_title_eng').css('color', '#737373');
            $('#formEngTitle').removeClass("has-warning has-feedback");
            $('#glyphiconTitleEng').removeClass("glyphicon glyphicon-warning-sign form-control-feedback");
        }
        if ($max - $len < 0) {
            $str = $('#title_eng').val();
            $str = $str.substring(0, $max);
            $('#title_eng').val($str);
            $('#countBox_title_eng').text(0);
        }
    });



    $("#alertEvento").alert();
    $("#alertEvento").fadeTo(3000, 1000).slideUp(1000, function () {
        $("#alertEvento").slideUp(1000);
    });

    // Change language

    // eng to ita
    $("#btnItalian").click(function () {
        $('#btnItalian').addClass("active");
        $('#btnEnglish').removeClass("active");
        $('#formItaTitle').removeClass("hidden");
        $('#formItaAbstract').removeClass("hidden");
        $('#formItaDescription').removeClass("hidden");
        $('#formEngTitle').addClass("hidden");
        $('#formEngAbstract').addClass("hidden");
        $('#formEngDescription').addClass("hidden");
    });

    // ita to eng
    $("#btnEnglish").click(function () {
        $('#btnItalian').removeClass("active");
        $('#btnEnglish').addClass("active");
        $('#formItaTitle').addClass("hidden");
        $('#formItaAbstract').addClass("hidden");
        $('#formItaDescription').addClass("hidden");
        $('#formEngTitle').removeClass("hidden");
        $('#formEngAbstract').removeClass("hidden");
        $('#formEngDescription').removeClass("hidden");
    });



    // text editor tinyMCE

    tinymce.init({
        selector: '.textControl',
        height: 100,
        menu: { // this is the complete default configuration
            edit: { title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall' },
            insert: { title: 'Insert', items: 'link | template hr charmap' },
            format: { title: 'Format', items: 'bold italic underline superscript subscript' },
            tools: { title: 'Tools', items: 'spellchecker code' }
        }
        ,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code wordcount'
        ],
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
            if ($(".textControl").prop('readonly')) {
                editor.settings.readonly = true;
            }
        },
        toolbar: 'undo redo | bold italic | preview | spellchecker code',
        content_css: '//www.tinymce.com/css/codepen.min.css'
    });

    tinymce.init({
        selector: '.longTextControl',
        height: 300,
        menu: { // this is the complete default configuration
            edit: { title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall' },
            insert: { title: 'Insert', items: 'link | template hr charmap' },
            format: { title: 'Format', items: 'bold italic underline superscript subscript' },
            tools: { title: 'Tools', items: 'spellchecker code' }
        }
        ,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code wordcount'
        ],
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
            if ($(".textControl").prop('readonly')) {
                editor.settings.readonly = true;
            }
        },
        toolbar: 'undo redo | bold italic | preview | spellchecker code',
        content_css: '//www.tinymce.com/css/codepen.min.css'
    });

    // Insert <br/> if press shift+enter in title
    $(".form-control").on("keypress", function (e) {
        if (e.which === 13 && e.shiftKey) {
            $(this).val(function (i, v) {
                return v + "<br/>"; // or return v + "\n"; (whatever you want)
            });
        }
    });

});


