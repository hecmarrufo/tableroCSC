<?php
include_once 'conn.php';
session_start();
$_SESSION['cadena'];
$region = $_POST['region'];

$sql = "SELECT local FROM Tsp_dim_locales 
WHERE [Abierto/cerrado]='Abierto' AND Cadena ='".$_SESSION['cadena']."' AND región ='".$region."' ORDER BY nrolocal";
$result = sqlsrv_query($conn, $sql);
$result_array = array();
while($info=sqlsrv_fetch_array($result)){
    unset($loc);
//    $loc = $region.' - '.$cadena;
    $loc = $info['local'];

    $result_array[] = array("local" => $loc);
}
echo json_encode($result_array);
include_once 'closeconn.php';
?>