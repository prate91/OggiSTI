<?php

require_once __DIR__.'/../../../../../Config/OggiSTI_adm.php';
require_once __DIR__.'/../../../../../Config/EPICAC_rd.php';
require_once __DIR__.'/../../../../../Config/Users_adm.php';


function OggiSTIDBConnect()
{
    $OggiSTI_db = new Database(OGGISTI_HOST, OGGISTI_USER_ADM, OGGISTI_PASSWORD_ADM, OGGISTI_DB_NAME);
    return $OggiSTI_db;
}

function EPICACDBConnect()
{
    $EPICAC_db = new Database(EPICAC_HOST, EPICAC_USER_RD, EPICAC_PASSWORD_RD, EPICAC_DB_NAME);
    return $EPICAC_db;
}

function UsersDBConnect()
{
    $Users_db = new Database(USERS_HOST, USERS_USER_ADM, USERS_PASSWORD_ADM, USERS_DB_NAME);
    return $Users_db;
}


?>