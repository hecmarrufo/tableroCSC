<?php
include_once 'conn.php';
$codigo2 = $_POST['codigo'];
$codigo = explode(' ', $codigo2, 2);
session_start();
$_SESSION['numloc'];

$sql = "select Hora, sum(qtickets)*100.0/
(select sum(qtickets)from tsp_heatmap Where nrolocal=".$_SESSION['numloc']." And codfam=".$codigo[0].")tkts 
from tsp_heatmap Where nrolocal=".$_SESSION['numloc']." And codfam=".$codigo[0]." group by Hora order by Hora";

$result = sqlsrv_query($conn, $sql);
$result_array = array();

while($info=sqlsrv_fetch_array($result)){
    unset($hora,$tkts);
    $hora = $info['Hora'];
    $tkts = ROUND($info['tkts']);
    $result_array[] = array("hora" => $hora, "tkts" => $tkts);
}

$sql = "select diasemana,Hora,SUM(qtickets)tkts from tsp_heatmap where nrolocal=".$_SESSION['numloc']." And codfam=".$codigo[0]." group by hora,diasemana order by hora";

$result = sqlsrv_query($conn, $sql);
$result_array2 = array();

while($info=sqlsrv_fetch_array($result)){
    unset($diaF,$horaF,$tktsF);
    $diaF = $info['diasemana'];
    $horaF = $info['Hora'];
    $tktsF = $info['tkts'];
    $result_array2[] = array("diaF" => $diaF, "horaF" => $horaF, "tktsF" => $tktsF);
}

$sql="select sexo,(COUNT(sexo)*100/
(select COUNT(sexo)from tsp_heatmap Where nrolocal=".$_SESSION['numloc']." And codfam=".$codigo[0]."))Psex 
from tsp_heatmap Where nrolocal=".$_SESSION['numloc']." And codfam=".$codigo[0]." group by sexo order by sexo";

$result = sqlsrv_query($conn, $sql);
$result_array3 = array();

while($info=sqlsrv_fetch_array($result)){
    unset($sexo,$Psex);
    $sexo = $info['sexo'];
    $Psex = ROUND($info['Psex']);
    $result_array3[] = array("sexo" => $sexo, "Psex" => $Psex);
}

$sql="select diasemana, (sum(qtickets)*100/
(select SUM(qtickets)from tsp_heatmap Where nrolocal=".$_SESSION['numloc']." And codfam=".$codigo[0]."))suma
from tsp_heatmap Where nrolocal=".$_SESSION['numloc']." And codfam=".$codigo[0]." group by diasemana order by diasemana";

$result = sqlsrv_query($conn, $sql);
$result_array4 = array();

while($info=sqlsrv_fetch_array($result)){
    unset($diasemana,$suma);
    $diasemana = $info['diasemana'];
    $suma = ROUND($info['suma']);
    $result_array4[] = array("diasemana" => $diasemana, "suma" => $suma);
}

$resultados=array("1" => $result_array, "2" => $result_array2, "3" => $result_array3, "4" => $result_array4);
echo json_encode($resultados);

include_once 'closeconn.php';
?>