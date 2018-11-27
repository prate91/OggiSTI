<?php
      
    require_once (__DIR__.'/../../../../Config/Database.class.php');
    require_once (__DIR__.'/../../../../Config/OggiSTI_adm.php');

    $OggiSTI_db = new Database(OGGISTI_HOST, OGGISTI_USER_ADM, OGGISTI_PASSWORD_ADM, OGGISTI_DB_NAME);

    $result =  $OggiSTI_db->select("SELECT * FROM published_events");

    if(true == $result['success'])
    {
        echo "Number of rows: " . $result['count'] ."<br />";
        foreach($result['rows'] as $row)
        {
            echo "Id: "             . $row['Id']            ."<br />";
            echo "Date: "           . $row['Date']          ."<br />";
            echo "ItaTitle: "       . $row['ItaTitle']      ."<br />";
            echo "ItaAbstract: "    . $row['ItaAbstract']   ."<br />";
            echo "<hr />";
        }
    }

    if(false == $result['success'])
    {
        echo "An error has occurred: " . $result['error'] ."<br />";
    }

    // $EPICAC = new Database;
    // $OggiSTI = new Database;

    // function selectDb($database, $db, $privileges){
	//     return $database->getConnection($db, $privileges);
    // }

    // //$EPICAC_conn = selectDb($EPICAC,'EPICAC', 'rd');
    // $EPICAC_conn = $EPICAC->getConnection('EPICAC', 'rd');
    // $OggiSTI_conn = $OggiSTI->getConnection('OggiSTI', 'adm');
    // //$OggiSTI_conn = selectDb($OggiSTI,'EPICAC', 'rd');


    // echo "Host information: " . mysqli_get_host_info($EPICAC_conn) . PHP_EOL;
    // echo "Host information: " . mysqli_get_host_info($OggiSTI_conn) . PHP_EOL;

?>