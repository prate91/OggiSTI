<!--///////////////////////////////////////////////////////////////////////////
//
// Project:   HMR OggiSTI, today in computing history
// Package:   OggiSTI generated PHP pages
// Title:     Custom initial content of the navbar menu in Administrations page.
// File:      OggiSTI_navbarMenu.php
// Path:      /OggiSTI/Assets/PHP/
// Type:      php
// Started:   2018.04.13
// Author(s): Nicolò Pratelli
// State:     online
//
// Version history.
// - 2018.04.22  Nicolò
//   First version
//
// ////////////////////////////////////////////////////////////////////////////
//
// This file is part of software developed by the HMR Project
// Further information at: http://progettohmr.it
// Copyright (C) 2017-2018 HMR Project.OggiSTI, G.A. Cignoni, N. Pratelli
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
// ///////////////////////////////////////////////////////////////////////-->
<!-- Navbar menu -->
<nav class="navbar navbar-inverse" role="navigation">
	<div class="navbar-header">
		<button	type="button" class= "navbar-toggle" data-toggle="collapse" data-target="#nav-toggle">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>
	<div class="collapse navbar-collapse" id="nav-toggle">
	<ul class="nav	navbar-nav">
		<li><a href="OggiSTI_index_administration.php">Home</a></li>
		<li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Eventi
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="OggiSTI_savedEvents.php">Salvati</a></li>
          <li><a href="OggiSTI_redactionEvents.php">In redazione</a></li>
          <li><a href="OggiSTI_reviewedEvents.php">In approvazione</a></li>
          <li><a href="OggiSTI_publicatedEvents.php">Pubblicati</a></li>
        </ul>
      </li>
		<li><a href="OggiSTI_edit.php">Aggiungi evento</a></li>
	</ul>
	<form class="navbar-form navbar-right" role="search">
		<div class="text-right iconaUser"><a href="../../../Administration/Assets/PHP/welcome.php"><span class="glyphicon glyphicon-user"></span> <?php echo $autore; ?></a></div>
		</form>
	</div>
</nav>