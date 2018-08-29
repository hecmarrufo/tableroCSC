<?php
/**
 * Created by PhpStorm.
 * User: hmarrufo
 * Date: 26/07/2018
 * Time: 03:49 PM
 */
//include $_SERVER['DOCUMENT_ROOT'].'/san/php/mapa.php';
//include_once $_SERVER['DOCUMENT_ROOT'].'/san/php/dbcontrol.php';
$conn = sqlsrv_connect( "D095SRTEC55",  array("Database"=>"WorkDB","UID"=>"","PWD"=>"","CharacterSet" => "UTF-8"));
if( !$conn ) {
    echo "Connection could not be established.<br />";
    die( print_r( sqlsrv_errors(), true));
}
?>