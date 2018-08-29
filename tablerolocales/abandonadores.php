<?php
/**
 * Created by PhpStorm.
 * User: hmarrufo
 * Date: 06/08/2018
 * Time: 02:17 PM
 */
session_start();
$_SESSION['local'];
$_SESSION['numloc'];
if(!$_SESSION['local']){
    header('Location: entrada.php');
    die();
}
include 'conn.php';
$sql ="SELECT Aban_recuperados, aban_venta_recuperada,(100-((Cli_emails*100)/clientes))lost FROM Tsp_perfil_locales WHERE nrolocal=".$_SESSION['numloc'];
$stmt = sqlsrv_query($conn, $sql);
while($info=sqlsrv_fetch_array($stmt)){
    unset($cli, $plata);
    $cli = $info['Aban_recuperados'];
    $plata = $info['aban_venta_recuperada'];
    $lost = $info['lost'];
}
if($cli == NULL){
    $cli='Sin Info';
}
if($plata == NULL){
    $plata='Sin Info';;
}
sqlsrv_free_stmt( $stmt);
include 'closeconn.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Lalezar" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="main.css">
</head>
<body>
<div class="box">
    <div class="table-cell">
        <div id="header">
            <div id="contit">
                <div id="cadena">
                    <img id="logoC" src="./logoscsc/<?PHP echo trim($_SESSION['cadena']);?>.png">
                </div>
                <div id="titulo">
                    <p>Local:  <?php echo $_SESSION['numloc']." - ".$_SESSION['local']; ?></p>
                </div>
                <div id="salir">
                    <a href="landing.php">Volver</a>
                </div>
            </div>
        </div>
        <div id="content">
            <div class="container">

                <div class="">
                    <p class="gde"><?PHP echo $cli;?></p>
                    <p class="peq">Abandonadores Recuparados</p>


                    <img class="imgpq" src="./icons/good-quality.png">
                </div>

                <div>
                    <p class="gde"><?PHP echo $plata;?></p>
                    <p class="peq">Venta Generada por su regreso</p>


                    <img class="imgpq" src="./icons/coin-in-hand.png">
                </div>

                <div style="padding-bottom: 3%">
                    <p class="gde"><?PHP echo $lost;?>%</p>
                    <p class="peq">No se pudo contactar, ya que no declararon su Mail al momento de asociarse</p>

                    <img class="imgpq" src="./icons/goal.png">
                </div>

            </div>
        </div>
        <div id="footer">
            <img id="piedp" src="./logoscsc/footer.png">
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
</body>
</html>