
// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI - Today in computer history
// Package:  OggiSTI administration
// Title: script for control OggiSTI administration index
// File: OggiSTI_panel.js
// Path: Assets/JS
// Type: javascript
// Started: 2018-10-25
// Author(s): Nicolò Pratelli
// State: in use
//
// Version history.
// - 2018.10.25 Nicolò
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

var savedList = "";
var notSavedList = "";
var countSaved = 0;
var countNotSaved = 0;

var url = "../Api/extractUserSavedEvents.php"
$.getJSON(url, function (result) {
    $.each(result, function (index, item) {
        countSaved++;
        var row = '<a href="OggiSTI_event.php?id_evento='+item.id_evento+'&id_state=Redazione" class="list-group-item" target="_blank">'+item.data_evento+' '+item.titolo_ita+'</a>';
        savedList += row;
    });
    $("#listSavedEvents").html(savedList);
    $("#savedCount").html(countSaved);
});

var url = "../Api/extractNotSavedEvents.php"
$.getJSON(url, function (result) {
    $.each(result, function (index, item) {
        countNotSaved++;
        var row = '<a href="OggiSTI_event.php?id_evento='+item.id_evento+'&id_state=Redazione" class="list-group-item" target="_blank">'+item.data_evento+' '+item.titolo_ita+'</a>';
        notSavedList += row;
    });
    $("#listNotSavedEvents").html(notSavedList);
    $("#notSavedCount").html(countNotSaved);
});


var numberRedacted = "";
var url = "../Api/countRedactedEvents.php"
$.getJSON(url, function (result) {
    $.each(result, function (index, item) {
        numberRedacted = item.numeroEventi;
    });
    $("#redactedEvents").html(numberRedacted);
});

var numberPublicated = "";
var url = "../Api/countPublicatedEvents.php"
$.getJSON(url, function (result) {
    $.each(result, function (index, item) {
        numberPublicated = item.numeroEventi;
    });
    $("#publicatedEvents").html(numberPublicated);
});
