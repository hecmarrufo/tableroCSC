<?php
include_once 'conn.php';
$cadena = $_POST['cadena'];
session_start();
$_SESSION['cadena']  = $cadena;
$sql = "SELECT región as reg FROM Tsp_dim_locales WHERE cadena='".$cadena."' GROUP BY región";
$result = sqlsrv_query($conn, $sql);
$result_array = array();
while($info=sqlsrv_fetch_array($result)){
    unset($reg);
    $reg = $info['reg'];

    $result_array[] = array("reg" => $reg);
}
echo json_encode($result_array);

include_once 'closeconn.php';
?>