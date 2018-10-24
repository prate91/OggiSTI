<?php
			
	require("config.php");
	
	//header('Content-Type : application/json');
	
	
    $query100="SELECT id_evento FROM eventioggi WHERE (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(data_evento, '%Y'))%100 = 0";
    $query50="SELECT id_evento FROM eventioggi WHERE (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(data_evento, '%Y'))%50 = 0 AND (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(data_evento, '%Y'))%100 != 0";
    $query25="SELECT id_evento FROM eventioggi WHERE (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(data_evento, '%Y'))%25 = 0 AND (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(data_evento, '%Y'))%50 != 0";
    $query10="SELECT id_evento FROM eventioggi WHERE (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(data_evento, '%Y'))%10 = 0 AND (DATE_FORMAT(CURDATE(), '%Y')-DATE_FORMAT(data_evento, '%Y'))%50 != 0";
    $queryUsato="SELECT id_evento FROM eventioggi";
    
    
    $arrayEventi = array();
    $resultUsato = mysqli_query($conn,$queryUsato);
    if (mysqli_num_rows($resultUsato) > 0) {
    $pt = mysqli_num_rows($resultUsato);
    // output data of each row
    while($rowUsato = mysqli_fetch_assoc($resultUsato)) {
       $arrayEventi[$rowUsato["id_evento"]] = $pt ;
       $pt=$pt-1;
    }
   
    //print_r($arrayEventi);
    //echo "<br/>";


    $result100 = mysqli_query($conn,$query100);
    if (mysqli_num_rows($result100) > 0) {
        while($row100 = mysqli_fetch_assoc($result100)) {
               $arrayEventi[$row100["id_evento"]] += 200;
    }
    }
    
    //print_r($arrayEventi);
    //echo "<br/>";

    $result50 = mysqli_query($conn,$query50);
    if (mysqli_num_rows($result50) > 0) {
        while($row50 = mysqli_fetch_assoc($result50)) {
               $arrayEventi[$row50["id_evento"]] += 100;
    }
    }
    
    
     //print_r($arrayEventi);
   // echo "<br/>";


    $result25 = mysqli_query($conn,$query25);
     if (mysqli_num_rows($result25) > 0) {
        while($row25 = mysqli_fetch_assoc($result25)) {
               $arrayEventi[$row25["id_evento"]] += 50;
    }
    }
    
    
     //print_r($arrayEventi);
    //echo "<br/>";


    $result10 = mysqli_query($conn,$query10);
    $result10 = mysqli_query($conn,$query10);
     if (mysqli_num_rows($result10) > 0) {
        while($row10 = mysqli_fetch_assoc($result10)) {
               $arrayEventi[$row10["id_evento"]] += 25;
    }
    }
   // print_r($arrayEventi);
    //echo "<br/>";
    
    $id_evento_oggi="";
    $point=0;
    foreach($arrayEventi as $id => $points) {
        if($points>$point){
            $point=$points;
            $id_evento_oggi=$id;
        }
    }
    
    //echo "Id scelto: ". $id_evento_oggi . " Punti: ".$point;
    
    $toinsert="INSERT INTO eventooggi (id_evento) VALUES ('$id_evento_oggi')";
    $result = mysqli_query($conn, $toinsert);
     } else {
        echo "0 results";
}
?>
