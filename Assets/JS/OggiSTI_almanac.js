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

//var dataEvento="";
var pannelli = "";

$(document).ready(function(){

    
// Initialize datepicker
$( "#oggiSTI_picker" ).datepicker({
      dateFormat : "dd/mm/yy", // date format
      yearRange: "c-5:c+5", // years calendar range
      changeYear: true, 
      showOtherMonths: true,
      selectOtherMonths: true,
      showOn: "button",
      buttonImage: "Assets/Img/logo-oggiSTI_ico.png", // icon of the calendar
      buttonImageOnly: true,
      buttonText: "Seleziona una data", // button text
      onSelect: function(dateText) {
        $("#oggiSTI_titoloStessoGiorno").html("");
        var selectedDate = $( "#oggiSTI_picker" ).datepicker( "getDate" ); // assign date to selected date
        var msec = Date.parse(selectedDate); // data convert
        var i = new Date(estremoInf(msec));  // get first day of the week
        var s = new Date(estremoSup(msec));  // get last day of the week
        var dataInf = formatDate(i); 
        var dataSup =  formatDate(s); 
        var dataSelezionata = this.value; // assign today date
        var data_evento = reformatDateToEng(dataSelezionata); // data convert in yyyy-mm-dd format
        // set url of the query that get event of choosen date by user
        var url = "Assets/Api/getCalendarDateEvent.php";
        var count = 0;
        var id_pannello = 1;
        pannelli = "";
  		  // AJAX  call get event of choosen date by user
  		  $.getJSON(url, {"data_evento":data_evento}, function(result){
  				$.each(result, function(index, item){
            if(index=="status"){
            // empty result, search other event in the week
              var d = new Date();
              var arrayDataSelezionata = dataSelezionata.split('/');
              var giornoEvento = arrayDataSelezionata[0];
              var meseEvento = arrayDataSelezionata[1];
              d.setDate(arrayDataSelezionata[0]);
              d.setMonth(arrayDataSelezionata[1]-1);
              giornoEvento = convertiGiorni(giornoEvento);
              meseEvento = convertiMesi(meseEvento);
              $("#oggiSTI_sopraTitolo").html("Nella stessa settimana del <span id='oggiSTI_giornoDiverso'></span> <span id='oggiSTI_meseDiverso'></span>");
              $("#oggiSTI_dataGiorno").html(giornoEvento);
              $("#oggiSTI_dataMese").html(meseEvento);
              $("#oggiSTI_giornoDiverso").html(giornoEvento);
              $("#oggiSTI_meseDiverso").html(meseEvento);
              var contaEventiSettimana = 0;
              var id_pannello_settimana = 1;
              pannelli = "";
              // set url of other event in the week
              var url = "Assets/Api/getWeekEvents.php";
              $.getJSON(url, {"dataInf":dataInf, "dataSup":dataSup}, function(result){
                $.each(result, function(index, item){
			            if(index=="status"){
                  // empty result
			              pulisciCampi();
                  }else {
                    if (contaEventiSettimana == 0) {
                      modificaInfoEvento(item.data_evento, item.titolo_ita, item.abstr_ita, item.desc_ita, item.riferimenti, item.redattore, item.ver_1, item.ver_2, item.fonteimmagine, item.immagine);
                      $("#oggiSTI_sopraTitolo").css("visibility", "visible");
                      var id_evento = item.id_evento;
                      // update counter of the event
                      var urlGet = "Assets/Api/updateCounter.php";
                      $.get(urlGet, {"id_evento": id_evento});
                    }else {
                      $("#oggiSTI_titoloStessoGiorno").html("Altri eventi della settimana");
                      var dataEvento = item.data_evento;
                      var arrayDataEvento = dataEvento.split('-');
                      var annoEvento = arrayDataEvento[0];
                      pannelli += '<div class="panel panel-default"><div class="panel-heading panel-heading-custom"><h4 class="panel-title"><a data-toggle="collapse" href="#collapse' + id_pannello_settimana + '"><table><tr><td>' + annoEvento + '</td></tr><tr><td>' + item.titolo_ita + '</td></tr></table></a></h4></div><div id="collapse' + id_pannello_settimana + '" class="panel-collapse collapse"><div class="panel-body">' + item.abstr_ita + '</div><a id="evento-' + item.id_evento + '" class="oggiSTI_apriEvento">apri evento</a></div></div>';
                      id_pannello_settimana++;
                    }
                    $("#oggiSTI_eventiLaterali").html(pannelli);
                    contaEventiSettimana++
                  }
                });
              });
            }else{
              // there is an event
  						if(count==0){
                modificaInfoEvento(item.data_evento, item.titolo_ita, item.abstr_ita, item.desc_ita, item.riferimenti, item.redattore, item.ver_1, item.ver_2,  item.fonteimmagine, item.immagine);
                $("#oggiSTI_sopraTitolo").css("visibility", "hidden");
                var id_evento = item.id_evento;
                var urlGet = "Assets/Api/updateCounter.php";
  			        //chiamata AJAX
                $.get( urlGet, {"id_evento":id_evento} );
              }else{
                $("#oggiSTI_titoloStessoGiorno").html("Altri eventi del giorno");
                var dataEvento = item.data_evento;
                var arrayDataEvento = dataEvento.split('-');
                var annoEvento = arrayDataEvento[0];
                pannelli += '<div class="panel panel-default"><div class="panel-heading panel-heading-custom"><h4 class="panel-title"><a data-toggle="collapse" href="#collapse'+id_pannello+'"><table><tr><td>' + annoEvento + '</td></tr><tr><td>' + item.titolo_ita + '</td></tr></table></a></h4></div><div id="collapse'+id_pannello+'" class="panel-collapse collapse"><div class="panel-body">'+item.abstr_ita+'</div><a id="evento-'+item.id_evento+'" class="oggiSTI_apriEvento">apri evento</a></div></div>';
                id_pannello++;
              }
              $("#oggiSTI_eventiLaterali").html(pannelli);
              count++;
            }
  				});
  			});
    }
  }).on("change", function() {
     //$("#messaggio").html("Got change event from field");
  }, $.datepicker.regional[ "it" ]);


// Open lateral events
$( "#oggiSTI_eventiLaterali" ).on( "click", ".oggiSTI_apriEvento", function() {
    var id_eventoStr=$(this).attr("id");
    idArr = id_eventoStr.split("-"); 
    var id_evento = idArr[1];    
     var url = "Assets/Api/getLateralEvent.php";
     var id_pannello = 1;
        pannelli ="";
		// Ajax call
		$.getJSON(url, {"id_evento":id_evento}, function(result){
				$.each(result, function(index, item){
            modificaInfoEvento(item.data_evento, item.titolo_ita, item.abstr_ita, item.desc_ita, item.riferimenti, item.redattore, item.ver_1, item.ver_2, item.fonteimmagine, item.immagine);
            $("#oggiSTI_sopraTitolo").css("visibility", "hidden");
            $("#oggiSTI_giornoDiverso").html("");
            $("#oggiSTI_meseDiverso").html("");
            var id_evento = item.id_evento;
            var urlGet = "Assets/Api/updateCounter.php";
		        //chiamata AJAX
            $.get( urlGet, {"id_evento":id_evento} );
            $("#oggiSTI_titoloStessoGiorno").html("");
            var url = "Assets/Api/getLateralEvents.php";
		        //chiamata AJAX
            $.getJSON(url, {"id_evento":id_evento}, function(result){
              $.each(result, function(index, item){
                if(index=="status"){
                    $("#oggiSTI_titoloStessoGiorno").html("");
                    $("#oggiSTI_eventiLaterali").html(pannelli);
                }else{         
                    $("#oggiSTI_titoloStessoGiorno").html("Altri eventi del giorno");
                    var dataEvento = item.data_evento;
                    var arrayDataEvento = dataEvento.split('-');
                    var annoEvento = arrayDataEvento[0];
                    pannelli += '<div class="panel panel-default"><div class="panel-heading panel-heading-custom"><h4 class="panel-title"><a data-toggle="collapse" href="#collapse'+id_pannello+'"><table><tr><td>' + annoEvento + '</td></tr><tr><td>' + item.titolo_ita + '</td></tr></table></a></h4></div><div id="collapse'+id_pannello+'" class="panel-collapse collapse"><div class="panel-body">'+item.abstr_ita+'</div><a id="evento-'+item.id_evento+'" class="oggiSTI_apriEvento">apri evento</a></div></div>';
                        id_pannello++;
                    }
              });
              $("#oggiSTI_eventiLaterali").html(pannelli);
            });
				});
		});
 });

    
    // extract event by id
    var id = getUrlParameter('id');
    if(id){
        var url = "Assets/Api/getLateralEvent.php";
        var id_pannello = 1;
        pannelli ="";
        //chiamata AJAX
        $.getJSON(url, {"id_evento":id}, function(result){
            $.each(result, function(index, item){
                if(index=="status"){
                    pulisciCampi();
                }else {
                    modificaInfoEvento(item.data_evento, item.titolo_ita, item.abstr_ita, item.desc_ita, item.riferimenti, item.redattore, item.ver_1, item.ver_2, item.fonteimmagine, item.immagine);
                    $("#oggiSTI_sopraTitolo").css("visibility", "hidden");
                    $("#oggiSTI_giornoDiverso").html("");
                    $("#oggiSTI_meseDiverso").html("");
                    var id_evento = item.id_evento;
                    var urlGet = "asset/api/updateCounter.php";
                    //chiamata AJAX
                    $.get(urlGet, {"id_evento": id_evento});
                    var url = "Assets/Api/getLateralEvents.php";
                    //chiamata AJAX
                    $.getJSON(url, {"id_evento": id_evento}, function (result) {
                        $.each(result, function (index, item) {
                            if (index == "status") {
                                $("#oggiSTI_eventiLaterali").html(pannelli);
                            } else {
                                var dataEvento = item.data_evento;
                                var arrayDataEvento = dataEvento.split('-');
                                var annoEvento = arrayDataEvento[0];
                                pannelli += '<div class="panel panel-default"><div class="panel-heading panel-heading-custom"><h4 class="panel-title"><a data-toggle="collapse" href="#collapse' + id_pannello + '"><table><tr><td>' + annoEvento + '</td></tr><tr><td>' + item.titolo_ita + '</td></tr></table></a></h4></div><div id="collapse' + id_pannello + '" class="panel-collapse collapse"><div class="panel-body">' + item.abstr_ita + '</div><a id="evento-' + item.id_evento + '" class="oggiSTI_apriEvento">apri evento</a></div></div>';
                                id_pannello++;
                            }
                        });
                        $("#oggiSTI_titoloStessoGiorno").html("Altri eventi del giorno");
                        $("#oggiSTI_eventiLaterali").html(pannelli);
                    });
                }
            });
        });
    }else {
        var data = new Date();
        var gg, mm, aaaa;
        giorno = data.getDay();
        gg = data.getDate();
        mm = (data.getMonth()) + 1;
        aaaa = data.getFullYear();
        var dataOggi = aaaa + "-" + mm + "-" + gg;
        mm = convertiMesi(mm);
        $("#oggiSTI_dataGiorno").html(gg);
        $("#oggiSTI_giornoDiverso").html(gg);
        //$("#dataGiornoSettimana").html(giorno);
        $("#oggiSTI_dataMese").html(mm);
        $("#oggiSTI_meseDiverso").html(mm);
        //$("#dataAnno").html(aaaa);
        var msec = Date.parse(data);
        var i = new Date(estremoInf(msec));
        var s = new Date(estremoSup(msec));
        var dataInf = formatDate(i);
        var dataSup = formatDate(s);

        //var url = "asset/api/controllaEventiGiornalieri.php"
        $.get("Assets/Api/checkTodayEvents.php", function (data){
            if (data == 0) {
                var contaEventiSettimana = 0;
                var id_pannello_settimana = 1;
                pannelli = "";
                var url = "Assets/Api/getWeekEvents.php";
                $.getJSON(url, {"dataInf": dataInf, "dataSup": dataSup}, function (result) {
                    $.each(result, function (index, item) {
                        if (index == "status") {
                            pulisciCampi();
                        } else {
                            if (contaEventiSettimana == 0) {
                                modificaInfoEvento(item.data_evento, item.titolo_ita, item.abstr_ita, item.desc_ita, item.riferimenti, item.redattore, item.ver_1, item.ver_2, item.fonteimmagine, item.immagine);
                                $("#oggiSTI_sopraTitolo").css("visibility", "visible");
                                var id_evento = item.id_evento;
                                var urlGet = "Assets/Api/updateCounter.php";
                                //chiamata AJAX
                                $.get(urlGet, {"id_evento": id_evento});
                            } else {
                                var dataEvento = item.data_evento;
                                var arrayDataEvento = dataEvento.split('-');
                                var annoEvento = arrayDataEvento[0];
                                pannelli += '<div class="panel panel-default"><div class="panel-heading panel-heading-custom"><h4 class="panel-title"><a data-toggle="collapse" href="#collapse' + id_pannello_settimana + '"><table><tr><td>' + annoEvento + '</td></tr><tr><td>' + item.titolo_ita + '</td></tr></table></a></h4></div><div id="collapse' + id_pannello_settimana + '" class="panel-collapse collapse"><div class="panel-body">' + item.abstr_ita + '</div><a id="evento-' + item.id_evento + '" class="oggiSTI_apriEvento">apri evento</a></div></div>';
                                id_pannello_settimana++;
                            }
                            $("#oggiSTI_titoloStessoGiorno").html("Altri eventi della settimana");
                            $("#oggiSTI_eventiLaterali").html(pannelli);
                            contaEventiSettimana++
                        }
                    });
                });
            } else {
                var data_evento = dataOggi;
                var url = "Assets/Api/getTodayEvent.php";
                var id_pannello = 1;
                pannelli = "";
                //chiamata AJAX
                $.getJSON(url, function (result) {
                    $.each(result, function (index, item) {
                        if (index == "status") {
                            $.get("Assets/Api/estraiEventoGiornaliero.php", function (data) {
                                location.reload();
                            });
                            //window.location = "asset/api/estraiEventoGiornaliero.php";
                        } else {
                            modificaInfoEvento(item.data_evento, item.titolo_ita, item.abstr_ita, item.desc_ita, item.riferimenti, item.redattore, item.ver_1, item.ver_2, item.fonteimmagine, item.immagine);
                            var id_evento = item.id_evento;
                            var urlGet = "Assets/Api/updateCounter.php";
                            //chiamata AJAX
                            $.get(urlGet, {"id_evento": id_evento});
                            var url = "Assets/Api/getLateralEvents.php";
                            //chiamata AJAX
                            $.getJSON(url, {"id_evento": id_evento}, function (result) {
                                $.each(result, function (index, item) {
                                    if (index == "status") {
                                        $("#oggiSTI_eventiLaterali").html(pannelli);
                                    } else {
                                        var dataEvento = item.data_evento;
                                        var arrayDataEvento = dataEvento.split('-');
                                        var annoEvento = arrayDataEvento[0];
                                        pannelli += '<div class="panel panel-default"><div class="panel-heading panel-heading-custom"><h4 class="panel-title"><a data-toggle="collapse" href="#collapse' + id_pannello + '"><table><tr><td>' + annoEvento + '</td></tr><tr><td>' + item.titolo_ita + '</td></tr></table></a></h4></div><div id="collapse' + id_pannello + '" class="panel-collapse collapse"><div class="panel-body">' + item.abstr_ita + '</div><a id="evento-' + item.id_evento + '" class="oggiSTI_apriEvento">apri evento</a></div></div>';
                                        id_pannello++;
                                    }
                                });
                                $("#oggiSTI_titoloStessoGiorno").html("Altri eventi del giorno");
                                $("#oggiSTI_eventiLaterali").html(pannelli);
                            });
                        }
                    });
                });
            }

        });
    }
            
            
});