
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
//var intestazione = "";


$(document).ready(function(){

    var pathname = window.location.pathname;
    var state = "";


    // Get the path name of the page

     if(/OggiSTI_savedEvents.php/.test(pathname)) {
        state = "Salvato";
    }
    if(/OggiSTI_redactionEvents.php/.test(pathname)) {
        state = "Redazione";
    }
    if(/OggiSTI_reviewedEvents.php/.test(pathname)) {
        state="Approvazione";
    }
    if(/OggiSTI_publicatedEvents.php/.test(pathname)) {
        state = "Pubblicato";
    }
    if(/OggiSTI_allEvents.php/.test(pathname)) {
        state = "Tutti";
    }

    
    // Built events tables

    var url = "../Api/extractEvents.php"
    $.getJSON(url, {"state":state}, function (result) {
            $.each(result, function (index, item) {
                if(state=="Tutti"){
                // tables composed by all events    
                    if (item.id_evento_appr == item.id_evento) {
                        // event is in editing or reviewed state and publicated
                        var riga = "<tr class='item'><td>" + item.id_evento_appr + "</td>" +
                        "<td class=''>" + formatDatemmddyyyy(item.data_evento_appr) + "</td>" +
                        "<td><a href='../../OggiSTI_preview.php?id_evento="+item.id_evento_appr+"&id_state="+ item.stato_appr+"'>"+ item.titolo_ita_appr + "</a></td>" +
                        "<td>" + item.stato_appr + " - " + item.stato + "</td>" +
                        "<td>" + item.redattore + "</td>" + 
                        "<td><button type='button' id='" + item.stato_appr + "-" + item.id_evento + "' class='btn btn-default btnEvento glyphicon glyphicon glyphicon-edit'> </button>"+
                        "<button type='button' id='" + item.stato + "-" + item.id_evento + "' class='btn btn-default btnEvento glyphicon glyphicon glyphicon-edit'> </button></td>"+
                        "<td><button type='button' id='" +  item.stato_appr + "-" + item.id_evento + "' class='btn btn-default btnPreview glyphicon glyphicon-eye-open'> </button>" + 
                        "<button type='button' id='" +  item.stato + "-" + item.id_evento + "' class='btn btn-default btnPreview glyphicon glyphicon-eye-open'> </button></td></tr>";
                    }else if (item.id_evento == null && item.id_evento_appr != null) {
                        // event is only in editing or reviewed state
                        var riga = "<tr class='item'><td>" + item.id_evento_appr + "</td>" +
                        "<td class=''>" + formatDatemmddyyyy(item.data_evento_appr) + "</td>" +
                        "<td>" + item.titolo_ita_appr + "</td>" +
                        "<td>" + item.stato_appr + "</td>" +
                        "<td>" + item.redattore_appr + "</td>" + 
                        "<td><button type='button' id='" + item.stato_appr + "-" + item.id_evento + "' class='btn btn-default btnEvento glyphicon glyphicon glyphicon-edit'> </button></td>"+ 
                        "<td><button type='button' id='" +  item.stato_appr + "-" + item.id_evento + "' class='btn btn-default btnPreview glyphicon glyphicon-eye-open'> </button></td></tr>";
                    } else if (item.id_evento_appr == null && item.id_evento != null) {
                        // event only publicated
                        var riga = "<tr class='item'><td>" + item.id_evento + "</td>" +
                        "<td class=''>" + formatDatemmddyyyy(item.data_evento) + "</td>" +
                        "<td>" + item.titolo_ita + "</td>" +
                        "<td>" + item.stato + "</td>" +
                        "<td>" + item.redattore + "</td>" + 
                        "<td><button type='button' id='" + item.stato + "-" + item.id_evento + "' class='btn btn-default btnEvento glyphicon glyphicon glyphicon-edit'> </button></td>"+ 
                        "<td><button type='button' id='" +  item.stato + "-" + item.id_evento + "' class='btn btn-default btnPreview glyphicon glyphicon-eye-open'> </button></td></tr>"; 
                    }
                }else if(state=="Pubblicato"){
                    var fbIcon = "";
                    if(item.fb==1){
                        fbIcon = '<img src="../Img/iconFacebook.png" class="fbIcon" alt="FB Icon">'
                    }
                    var riga = "<tr class='item'><td>" + item.id_evento + "</td>" +
                    "<td class=''>" + formatDatemmddyyyy(item.data_evento) + "</td>" +
                    "<td><a href='../../OggiSTI_preview.php?id_evento="+item.id_evento+"&id_state="+ item.stato+"' target='_blank'>"+ item.titolo_ita + "</a></td>" +
                    "<td>" + item.stato + " "+ fbIcon +"</td>" +
                    "<td>" + item.redattore + "</td>" + 
                    "<td><button type='button' id='" + state + "-" + item.id_evento + "' class='btn btn-default btnEvento glyphicon glyphicon glyphicon-edit'> </button></td>";
                }else{
                // all the others tables
                var riga = "<tr class='item'><td>" + item.id_evento + "</td>" +
                    "<td class=''>" + formatDatemmddyyyy(item.data_evento) + "</td>" +
                    "<td><a href='../../OggiSTI_preview.php?id_evento="+item.id_evento+"&id_state="+ item.stato+"' target='_blank'>"+ item.titolo_ita + "</a></td>" +
                    "<td>" + item.stato + "</td>" +
                    "<td>" + item.redattore + "</td>" + 
                    "<td><button type='button' id='" + state + "-" + item.id_evento + "' class='btn btn-default btnEvento glyphicon glyphicon glyphicon-edit'> </button></td>";
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
    $( "table" ).on( "click", ".btnEvento", function() {
        var idTotal=$(this).attr("id");
        var idTotalArray = idTotal.split("-");
        var idState = idTotalArray[0];
        var idEvent = idTotalArray[1];
        var indirizzo = "OggiSTI_event.php?id_evento="+idEvent+"&id_state="+idState;
        window.open(indirizzo,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,width=900,height=1000");
    });

    // event click that open preview page
    $( "table" ).on( "click", ".btnPreview", function() {
        var idTotal=$(this).attr("id");
        var idTotalArray = idTotal.split("-");
        var idState = idTotalArray[0];
        var idEvent = idTotalArray[1];
        var indirizzo = "../../OggiSTI_preview.php?id_evento="+idEvent+"&id_state="+idState;
        window.open(indirizzo,  "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,width=900,height=1000");
    });

    // event click that open edit page
    $("#modificaEvento").click(function() {
        var id_evento = $("#idEvento").text();
        var indirizzo = "OggiSTI_edit.php?id_evento="+id_evento+"&messaggio=modifica";
        window.open(indirizzo,  "_self", "toolbar=yes,scrollbars=yes,resizable=yes,width=900,height=1000");
        //location.assign(indirizzo);
        //location.href = "modifica.php?id_evento="+id_evento+"";
    });

     // event click that open edit page for quickly update
     $("#modificaVeloce").click(function() {
        var id_evento = $("#idEvento").text();
        var id_state = $("#idStato").text();
        var indirizzo = "OggiSTI_edit.php?id_evento="+id_evento+"&messaggio=modificaVeloce&id_state="+id_state+"";
        window.open(indirizzo,  "_self", "toolbar=yes,scrollbars=yes,resizable=yes,width=900,height=1000");
        //location.assign(indirizzo);
        //location.href = "modifica.php?id_evento="+id_evento+"";
    });


    
    // $("#modalEliminaEvento").on('click','#eliminaDef',function(){
    //     var id_evento = $('.hidden_id_evento').val();
    //     window.location = "../api/eliminaEvento.php?id_evento="+id_evento+"";

    // });
    // $("#modalEliminaEventoPubblicato").on('click','#eliminaDef',function(){
    //     var id_evento = $('.hidden_id_evento').val();
    //     window.location = "../api/eliminaEventoPubblicato.php?id_evento="+id_evento+"";

    // });
    // $("#modalEliminaEventoTutti").on('click','#eliminaDef',function(){
    //     var id_evento = $('.hidden_id_evento').val();
    //     window.location = "../api/eliminaEventoTutti.php?id_evento="+id_evento+"";

    // });
    


    // ///////////////////
    // Edit page
    // //////////////////

    // disable enter 

    $('#addEvent').keypress(function(tasto) {

        if(tasto.which == 13) {

            return false;

        }

    });

    $("#date").focus(function(){
        $('#formData').removeClass("has-error has-feedback");
        $("#helpDate").html("");
        $('#glyphiconDate').removeClass("glyphicon glyphicon-remove form-control-feedback");
    });

    $("#title_ita").focus(function(){
        $('#formTitle_ita').removeClass("has-error has-feedback");
        $("#helpTitleIta").html("<span id='helpTitleIta' class='help-block'>La dimensione massima consigliata è di 70 caratteri. Consultare le <a href='../../LineeGuida/#LGTitolo' target='_blank'>linee guida sul titolo</a> per maggiori informazioni</span>");
        $('#glyphiconTitleIta').removeClass("glyphicon glyphicon-remove form-control-feedback");
    });

    $("#abstr_ita").focus(function(){
        $('#formAbstr_ita').removeClass("has-error has-feedback");
        $("#helpAbstrIta").html("La dimensione massima consigliata è di 280 caratteri");
        $('#glyphiconAbstrIta').removeClass("glyphicon glyphicon-remove form-control-feedback");
    });

    $("#applica").click(function() {
        tinyMCE.triggerSave();
        var data = $('[name="date"]').val();
        var titolo_ita = $('[name="title_ita"]').val();
        var abstr_ita = $("#abstr_ita").val();
        var desc_ita = $('[name="desc_ita"]').val();
        var campi="";
        if(!checkDate(data)){
            campi=campi+"<strong>Data</strong><br/>";

            //$('#formData').addClass("has-error has-feedback");
            //$('body,html').animate({scrollTop:0},800);
            //$("#helpDate").html("La data è inserita in un formato non valido, usare il formato dd/mm/yyyy");
            //$('#glyphiconDate').addClass("glyphicon glyphicon-remove form-control-feedback");
        }
        if(titolo_ita==""){
            campi=campi+"<strong>Titolo</strong><br/>";
            //$('body,html').animate({scrollTop:0},800);
            //$('#formTitle_ita').addClass("has-error has-feedback");
            //$("#helpTitleIta").html("Questo campo non può rimanere vuoto");
            //$('#glyphiconTitleIta').addClass("glyphicon glyphicon-remove form-control-feedback");
        }
        if(abstr_ita==""){
            campi=campi+"<strong>Descrizione Breve</strong><br/>";
            //$('body,html').animate({scrollTop:$('#formAbstr_ita').offset().top},800);
            //$('#formAbstr_ita').addClass("has-error has-feedback");
            //$("#helpAbstrIta").html("Questo campo non può rimanere vuoto");
        }
        if(campi!=""){
            campi="I seguenti campi non possono essere vuoti:<br/>"+campi;
            $("#campiMancanti").html(campi);
            $("#campiMancanti").show();
        }

    });

    // Count characters

    // italian title
    $outMax=140;
    $('#title_ita').keyup(function() {
        $max = $outMax;
        $len=$('#title_ita').val().length;
        $('#countBox_title_ita').text($max-$len);
        if($max-$len<30){
            $('#countBox_title_ita').css('color','#C30');
            $('#formTitle_ita').addClass("has-warning has-feedback");
            $('#glyphiconTitleIta').addClass("glyphicon glyphicon-warning-sign form-control-feedback");
        }
        if($max-$len>=30){
            $('#countBox_title_ita').css('color','#737373');
            $('#formTitle_ita').removeClass("has-warning has-feedback");
            $('#glyphiconTitleIta').removeClass("glyphicon glyphicon-warning-sign form-control-feedback");
        }
        if ($max-$len<0){
            $str = $('#title_ita').val();
            $str = $str.substring(0,$max);
            $('#title_ita').val($str);
            $('#countBox_title_ita').text(0);
        }
    });

    // english title
    $outMax=140;
    $('#title_eng').keyup(function() {
        $max = $outMax;
        $len=$('#title_eng').val().length;
        $('#countBox_title_eng').text($max-$len);
        if($max-$len<30){
            $('#countBox_title_eng').css('color','#C30');
            $('#formTitle_eng').addClass("has-warning has-feedback");
            $('#glyphiconTitleEng').addClass("glyphicon glyphicon-warning-sign form-control-feedback");
        }
        if($max-$len>=30){
            $('#countBox_title_eng').css('color','#737373');
            $('#formTitle_eng').removeClass("has-warning has-feedback");
            $('#glyphiconTitleEng').removeClass("glyphicon glyphicon-warning-sign form-control-feedback");
        }
        if ($max-$len<0){
            $str = $('#title_eng').val();
            $str = $str.substring(0,$max);
            $('#title_eng').val($str);
            $('#countBox_title_eng').text(0);
        }
    });



    $("#alertEvento").alert();
    $("#alertEvento").fadeTo(3000, 1000).slideUp(1000, function(){
        $("#alertEvento").slideUp(1000);
    });

    // Change language

    // eng to ita
    $("#btnItalian").click(function() {
        $('#btnItalian').addClass("active");
        $('#btnEnglish').removeClass("active");
        $('#formTitle_ita').removeClass("hidden");
        $('#formAbstr_ita').removeClass("hidden");
        $('#formDesc_ita').removeClass("hidden");
        $('#formTitle_eng').addClass("hidden");
        $('#formAbstr_eng').addClass("hidden");
        $('#formDesc_eng').addClass("hidden");
    });

    // ita to eng
    $("#btnEnglish").click(function() {
        $('#btnItalian').removeClass("active");
        $('#btnEnglish').addClass("active");
        $('#formTitle_ita').addClass("hidden");
        $('#formAbstr_ita').addClass("hidden");
        $('#formDesc_ita').addClass("hidden");
        $('#formTitle_eng').removeClass("hidden");
        $('#formAbstr_eng').removeClass("hidden");
        $('#formDesc_eng').removeClass("hidden");
    });

   

    // text editor tinyMCE

    tinymce.init({
        selector: '.textControl',
        height: 100,
        menu : { // this is the complete default configuration
            edit   : {title : 'Edit'  , items : 'undo redo | cut copy paste pastetext | selectall'},
            insert : {title : 'Insert', items : 'link | template hr charmap'},
            format : {title : 'Format', items : 'bold italic underline superscript subscript'},
            tools  : {title : 'Tools' , items : 'spellchecker code'}
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
        menu : { // this is the complete default configuration
            edit   : {title : 'Edit'  , items : 'undo redo | cut copy paste pastetext | selectall'},
            insert : {title : 'Insert', items : 'link | template hr charmap'},
            format : {title : 'Format', items : 'bold italic underline superscript subscript'},
            tools  : {title : 'Tools' , items : 'spellchecker code'}
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
    $(".form-control").on("keypress", function(e){
        if ( e.which === 13 && e.shiftKey ) {
            $(this).val(function(i,v){
                return v + "<br/>"; // or return v + "\n"; (whatever you want)
            });
        }
    });





});


