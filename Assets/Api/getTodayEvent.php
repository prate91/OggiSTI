<?php
	include 'tablesFields.php';	
	require("functions.php");
	
		$query = "SELECT pE.Id, Date, ItaTitle, EngTitle, Image, ImageCaption, Icon, ItaAbstract, EngAbstract, ItaDescription, EngDescription, TextReferences, Keywords,Editors, Reviser_1, Reviser_2, State, Comment, Views, Fb FROM publishedEvents pE JOIN todayEvent tE ON pE.Id=tE.Id";
		echo loadDataTables($query, $tableFieldsAllPublicated, "yes");
	
?>
