// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI - Today in computer history
// Package: OggiSTI Javascript function
// Title: manage javascript function
// File: OggiSTI_function.js
// Path: asset/js/
// Type: javascript
// Started: 2018-04-13
// Author(s): Nicolò Pratelli
// State: in use
//
//  Version history.
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

// Get parameter from URL
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

// Generates a new date in yyyy-mm-dd format
function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('-');
}

// Converts the date 
// from dd/mm/yyyy format to yyyy-mm-dd format
function reformatDateToEng(dateStr) {
    var dArr = dateStr.split("/");
    return dArr[2] + "-" + dArr[1] + "-" + dArr[0];
}

// function fot convert dates
// from yyyy-mm-dd to dd/mm/yyyy
function reformatDateToIta(dateStr) {
    var dArr = dateStr.split("-");
    return dArr[2] + "/" + dArr[1] + "/" + dArr[0];
}

// from yyyy-mm-dd to mm-dd(yyyy)
function formatDatemmddyyyy(dateStr) {
    var dArr = dateStr.split("-");
    return dArr[1] + "-" + dArr[2] + " (" + dArr[0] + ")";
}

// Computes the first day of the week from any day
function estremoInf(msc) {
    var selectedDate = msc;
    var d = new Date(selectedDate);
    var weekday = d.getDay();
    var firstW = new Date(selectedDate);
    if (weekday == 0) { return firstW.setDate(firstW.getDate(selectedDate) - 6); }
    if (weekday == 1) { return firstW.setDate(firstW.getDate(selectedDate)); }
    if (weekday == 2) { return firstW.setDate(firstW.getDate(selectedDate) - 1); }
    if (weekday == 3) { return firstW.setDate(firstW.getDate(selectedDate) - 2); }
    if (weekday == 4) { return firstW.setDate(firstW.getDate(selectedDate) - 3); }
    if (weekday == 5) { return firstW.setDate(firstW.getDate(selectedDate) - 4); }
    if (weekday == 6) { return firstW.setDate(firstW.getDate(selectedDate) - 5); }
}

// Computes the last day of the week from any day
function estremoSup(msc) {
    var selectedDate = msc;
    var d = new Date(selectedDate);
    var weekday = d.getDay();
    var lastW = new Date(selectedDate);
    if (weekday == 0) { return lastW.setDate(lastW.getDate(selectedDate)); }
    if (weekday == 1) { return lastW.setDate(lastW.getDate(selectedDate) + 6); }
    if (weekday == 2) { return lastW.setDate(lastW.getDate(selectedDate) + 5); }
    if (weekday == 3) { return lastW.setDate(lastW.getDate(selectedDate) + 4); }
    if (weekday == 4) { return lastW.setDate(lastW.getDate(selectedDate) + 3); }
    if (weekday == 5) { return lastW.setDate(lastW.getDate(selectedDate) + 2); }
    if (weekday == 6) { return lastW.setDate(lastW.getDate(selectedDate) + 1); }
}

// Converts months from number to letters
function convertiMesi(mese) {
    if (mese == 1) mese = "gennaio";
    if (mese == 2) mese = "febbraio";
    if (mese == 3) mese = "marzo";
    if (mese == 4) mese = "aprile";
    if (mese == 5) mese = "maggio";
    if (mese == 6) mese = "giugno";
    if (mese == 7) mese = "luglio";
    if (mese == 8) mese = "agosto";
    if (mese == 9) mese = "settembre";
    if (mese == 10) mese = "ottobre";
    if (mese == 11) mese = "novembre";
    if (mese == 12) mese = "dicembre";
    return mese;
}

// Remove the 0 before a single-digit number
function convertiGiorni(giorno) {
    if (giorno == "01") giorno = "1";
    if (giorno == "02") giorno = "2";
    if (giorno == "03") giorno = "3";
    if (giorno == "04") giorno = "4";
    if (giorno == "05") giorno = "5";
    if (giorno == "06") giorno = "6";
    if (giorno == "07") giorno = "7";
    if (giorno == "08") giorno = "8";
    if (giorno == "09") giorno = "9";
    return giorno;
}


function calcolaOrdinaleGiorno(giorno, mese) {
    ordinale = 0;
    switch (mese) {
        case 1:
            return ordinale = giorno - 1;
        case 2:
            return ordinale = 31 + giorno - 1;
        case 3:
            return ordinale = 60 + giorno - 1;
        case 4:
            return ordinale = 91 + giorno - 1;
        case 5:
            return ordinale = 121 + giorno - 1;
        case 6:
            return ordinale = 152 + giorno - 1;
        case 7:
            return ordinale = 182 + giorno - 1;
        case 8:
            return ordinale = 213 + giorno - 1;
        case 9:
            return ordinale = 244 + giorno - 1;
        case 10:
            return ordinale = 274 + giorno - 1;
        case 11:
            return ordinale = 305 + giorno - 1;
        case 12:
            return ordinale = 335 + giorno - 1;
    }
}


function calcolaGiornoDaOrdinale(ordinale) {
    ordinale = ordinale + 1;
    if (ordinale >= 1 && ordinale <= 31) {
        return ordinale + "-1";
    }
    if (ordinale >= 32 && ordinale <= 60) {
        ordinale = ordinale - 31;
        return ordinale + "-2";
    }
    if (ordinale >= 61 && ordinale <= 91) {
        ordinale = ordinale - 60;
        return ordinale + "-3";
    }
    if (ordinale >= 92 && ordinale <= 121) {
        ordinale = ordinale - 91;
        return ordinale + "-4";
    }
    if (ordinale >= 122 && ordinale <= 152) {
        ordinale = ordinale - 121;
        return ordinale + "-5";
    }
    if (ordinale >= 153 && ordinale <= 182) {
        ordinale = ordinale - 152;
        return ordinale + "-6";
    }
    if (ordinale >= 183 && ordinale <= 213) {
        ordinale = ordinale - 182;
        return ordinale + "-7";
    }
    if (ordinale >= 214 && ordinale <= 244) {
        ordinale = ordinale - 213;
        return ordinale + "-8";
    }
    if (ordinale >= 245 && ordinale <= 274) {
        ordinale = ordinale - 244;
        return ordinale + "-9";
    }
    if (ordinale >= 275 && ordinale <= 305) {
        ordinale = ordinale - 274;
        return ordinale + "-10";
    }
    if (ordinale >= 306 && ordinale <= 335) {
        ordinale = ordinale - 305;
        return ordinale + "-11";
    }
    if (ordinale >= 336 && ordinale <= 366) {
        ordinale = ordinale - 335;
        return ordinale + "-12";
    }
}


// Insert event information in the page
function modificaInfoEvento(data_evento, titolo_ita, abstr_ita, desc_ita, riferimenti, redattore, revisore1, revisore2, fonteimmagine, immagine) {
    var data = new Date();
    var aaaa;
    var dataEvento = data_evento;
    //var giorno = data.getDay();
    aaaa = data.getFullYear();
    var arrayDataEvento = dataEvento.split('-');
    var giornoEvento = arrayDataEvento[2];
    var meseEvento = arrayDataEvento[1];
    var annoEvento = arrayDataEvento[0];
    var differenzaAnni = parseInt(aaaa) - parseInt(annoEvento);
    giornoEvento = convertiGiorni(giornoEvento);
    meseEvento = convertiMesi(meseEvento);
    $("#oggiSTI_dataGiorno").html(giornoEvento);
    $("#oggiSTI_dataMese").html(meseEvento);
    $("#oggiSTI_dataEvento").html(annoEvento + '<br/><small id="oggiSTI_differenzaTempo">' + differenzaAnni + ' anni fa</small>');
    $("#oggiSTI_titoloEvento").html(titolo_ita);
    $("#oggiSTI_descrizione_breve").html(abstr_ita);
    $("#oggiSTI_descrizione").html(desc_ita);
    $("#oggiSTI_riferimenti").html(riferimenti);
    var utenti = redattore.split("<br/>");
    var redattori = "";
    for (var i = 0; i < utenti.length; i++) {
        if (i >= utenti.length - 2) {
            redattori += utenti[i];
        }
        else {
            redattori += utenti[i] + ", ";
        }
    }
    $("#oggiSTI_autoriEvento").html(redattori);
    $("#oggiSTI_revisoriEvento").html(revisore1 + ", " + revisore2);
    $("#oggiSTI_titoloRiferimenti").css("visibility", "visible");
    $("#oggiSTI_lineaAutori").css("visibility", "visible");
    $("#oggiSTI_lineaRevisori").css("visibility", "visible");
    $("#oggiSTI_immagineEvento").attr("title", fonteimmagine);
    if (immagine == "") {
        src = "Assets/Img/HMR_2017g_GC-Working.svg";
    } else {
        var src = "Assets/Img/eventi/" + immagine;
    }
    $("#oggiSTI_immagineEvento").attr("src", src);

}

// Cleans event information in the page
function pulisciCampi() {
    $("#oggiSTI_dataEvento").html("");
    $("#oggiSTI_titoloEvento").html("Nessun evento oggi");
    $("#oggiSTI_descrizione_breve").html("In questo giorno non è presente nessun evento. " +
        "Se vuoi collaborare con noi visita la pagina " + "<a href='Collaborare/'>come collaborare</a>");
    $("#oggiSTI_descrizione").html("");
    $("#oggiSTI_riferimenti").html("");
    $("#oggiSTI_autoriEvento").html("");
    $("#oggiSTI_sopraTitolo").css("visibility", "hidden");
    $("#oggiSTI_immagineEvento").attr("src", "Assets/Img/HMR_2017g_GC-Working.svg");
    $("#oggiSTI_immagineEvento").attr("alt", "");
    $("#oggiSTI_titoloStessoGiorno").html("");
    $("#oggiSTI_eventiLaterali").html("");
    $("#oggiSTI_titoloRiferimenti").css("visibility", "hidden");
    $("#oggiSTI_lineaAutori").css("visibility", "hidden");
    $("#oggiSTI_lineaRevisori").css("visibility", "hidden");
}

//function contro if data is real
function checkDate(string) {
    // language=JSRegexp
    var expr = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
    if (!expr.test(string)) {
        return false;
    } else {
        var year = parseInt(string.substr(6), 10);
        var month = parseInt(string.substr(3, 2), 10);
        var day = parseInt(string.substr(0, 2), 10);

        var date = new Date(year, month - 1, day);
        return date.getDate() === day && data.getMonth() + 1 === month && data.getFullYear() === year;
    }
}



// function buildEventsTables
// build a table extracting events from database with ajax
function buildEventsTables(url, headTable, idTableBody, idTable) {
    $.getJSON(url, function (result) {
        $.each(result, function (index, item) {
            headTable += "<tr class='item'><td>" + item.id_evento + "</td>" +
                "<td>" + item.titolo_ita + "</td>" +
                "<td class=''>" + formatDatemmddyyyy(item.data_evento) + "</td>" +
                "<td>" + item.stato + "</td>" +
                "<td>" + item.redattore + "</td>" +
                "<td><button type='button' id='" + item.id_evento + "' class='btn btn-default btnEvento glyphicon glyphicon-eye-open'> </button></td></tr>";
            $(idTableBody).html(headTable);

        });
        $(idTable).DataTable();
    });
}