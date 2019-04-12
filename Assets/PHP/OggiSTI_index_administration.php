<?php

// ////////////////////////////////////////////////////////////////////////
//
// Project: OggiSTI
// Package: OggiSTI administration
// Title: Homepage administration
// File: OggiSTI_index_administration.php
// Path: OggiSTI/Assets/PHP
// Type: php
// Started: 2017-03-08
// Author(s): Nicolò Pratelli
// State: in use
//
// Version history.
// - 2017.04.18 Nicolò
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
include 'OggiSTI_sessionSet.php';
include 'OggiSTI_controlLogged.php';

?>

<!DOCTYPE html><html lang='it'><head><meta charset="UTF-8">

<title>Oggi nella storia dell'informatica - HMR</title>


<!-- Load OggiSTI standard libraries -->
<link rel='stylesheet' href='../../../Assets/Libs/Bootstrap/CSS/bootstrap.css'>
<link rel='stylesheet' href='../../../Assets/Libs/jQuery-UI/jquery-ui.css'>
<link rel='stylesheet' href='../../../Assets/Libs/jQuery-UI/jquery-ui.theme.css'>
<link rel='stylesheet' href='../../../Assets/Libs/jQuery-UI/jquery-ui.structure.css'>
<link rel='stylesheet' href='../../../Assets/Libs/DataTables/datatables.min.css'>


<script src='../../../Assets/Libs/jQuery/jquery-3.3.1.min.js'></script>
<script src='../../../Assets/Libs/jQuery-UI/jquery-ui.js'></script>
<script src='../JS/datepicker-it.js'></script>
<script src='../../../Assets/Libs/Bootstrap/JS/bootstrap.js'></script>
<script src="https://www.w3schools.com/lib/w3.js"></script>
<script src="../../../Assets/Libs/DataTables/datatables.min.js"></script>


<!-- Load HMR CSS styles & fonts -->
<link rel="stylesheet" type="text/css" href="../../../HMR_Style.css">

<!-- Load OggiSTI CSS styles & fonts -->
<link rel="stylesheet" type="text/css" href="../CSS/OggiSTI_Style.css">

<!-- Load favorite icon -->
<link rel="icon" type="image/png" href="../Img/logo-oggiSTI16x16.png" />

<!-- Load HMR standard libraries -->
<script type='text/javascript' src='../../../EPICAC/JSwebsite/searchAndSharing.js'></script>
<script type='text/javascript' src='../../../Assets/JS/HMR_CreaHTML.js'></script>

<!-- Load OggiSTI standard Javascript -->
<script src='../JS/OggiSTI_function.js'></script>

<!-- Load OggiSTI Almanac Javascript -->
<script src='../JS/OggiSTI_panel.js'></script>

<!-- To prevent most search engine crawlers indexing this page -->  
<meta name="robots" content="noindex">


</head>
<body>

<!-- Standard HMRWeb header ///////////////////////////////////////////////////
// For banner:
// - set level, 1 = "../", 2 = "../../" and so on;
// - set image, file name and extension, no path, has to be in /Assets/Images.
// For menu:
// - set level, same as banner;
// - set active menu entry, 1=Cronologia, 2=Eventi and so on.  -->
<div class="HMR_Banner">
	<script> creaHeader(3, 'HMR_2017g_GC-WebHeaderRite-270x105-3.png') </script>
</div>	
<div id="HMR_Menu" class="HMR_Menu" >
	<script> creaMenu(3, 5) </script>
</div>
	
	
<span class="stop"></span>
	
<!-- Actual page content starts here ///////////////////////////////////////-->
<div class="oggiSTI_content_amm">
    
<!-- OggiSTI navbar menu -->
<?php
    include 'OggiSTI_navbarMenu.php';
?>
   
<div class="jumbotron">
<div class="page-header">
    <h1 id="titoloPannelloControllo">Pannello di controllo OggiSTI</h1>     
  </div>
    <div class="panel-group col-sm-8" id="accordion">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">Eventi salvati <span id="savedCount" class="badge"></span></a>
        </h4>
      </div>
      <div id="collapse1" class="panel-collapse collapse in">
        <div id="listSavedEvents" class="panel-body">
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">Eventi in redazione <span id="notSavedCount" class="badge"></span></a>
        </h4>
      </div>
      <div id="collapse2" class="panel-collapse collapse">
        <div id="listNotSavedEvents" class="panel-body fixed-panel">
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">Documentazione</a>
        </h4>
      </div>
      <div id="collapse3" class="panel-collapse collapse">
        <div class="panel-body">
        <a href="../../LineeGuida/">
        <img src="../Img/iconGuidelines.png" title="Linee Guida per i redattori" class="img-thumbnail" alt="GuideLines" style="width:80px">
      </a>
      <a href="../../ChicagoStyle/">
        <img src="../Img/iconReferences.png" title="Linee Guida per i riferimenti"  class="img-thumbnail" alt="References" style="width:80px">
      </a>
        </div>
      </div>
    </div>
  </div> 

  <div class="col-sm-4">
    <div class="well well-lg">Eventi redatti <span id="redactedEvents" class="badge"></span></div>
    <div class="well well-lg">Eventi pubblicati <span id="publishedEvents" class="badge"></span></div>
    <div class="well well-lg"><a href="mailto:info@progettohmr.it?subject=[support] Problema con OggiSTI"><span class="glyphicon glyphicon-envelope"></span> Segnala un problema</a></div>
  </div>
<br class="stop"/>
    
</div>
</div>

<!-- Standard HMRWeb footer////////////////////////////////////////////////////
// Set:
// - level, 1 = "../", 2 = "../../" and so on;
// - set copyright start year, YYYY
// - set copyright end year, YYYY;
// - set copyright owner, default "Progetto HMR";
// - set date of page creation, YYYY/MM/DD.  -->

<div class="HMR_Footer">    
    <script> creaFooter(3, '2017', '2018', 'Nicolò Pratelli - G.A.Cignoni', '07/13/2017') </script>
</div>
   

    </body>
</html>
