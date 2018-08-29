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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Lalezar" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <style type="text/css">
    </style>
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
                <div class="tarjeta">
                    <p>Part. Venta Fidelizada</p>
                    <img class=icons src="icons/coins.png">
                </div>
                <div class="tarjeta">
                    <p>Part. Ticktes Fidelizados</p>
                    <img class=icons src="icons/tags.png">
                </div>
                <div class="tarjeta">
                    <p>Clientes Activos</p>
                    <img class=icons src="icons/conference.png">
                </div>
                <div class="tarjeta">
                    <p>Socios Premium</p>
                    <img class=icons src="icons/best-seller.png">
                </div>
            </div>
            <div class="container">
                <div class="tarjeta">
                    <p>Consumo Mensual</p>
                    <img class="icons" src="icons/purchase-order.png">
                </div>
                <div class="tarjeta">
                    <p>Consumo por género - Distribución por género</p>
                    <img class="icons" src="icons/man-woman.png">
                </div>
                <div class="tarjeta">
                    <p>Ticket Promedio</p>
                    <img class="icons" src="icons/shopping-cart.png">
                </div>
            </div>
            <div class="container">
                <div class="tarjeta">
                    <p>% de Clientes con Canje</p>
                    <img class="icons" src="icons/birthday.png">
                </div>
                <div class="tarjeta">
                    <p>Clientes sin Mail</p>
                    <img class="icons" src="icons/urgent-message.png">
                </div>
                <div class="tarjeta">
                    <p>Medios de Pago</p>
                    <img class="icons" src="icons/old-cash-register.png">
                </div>
            </div>
            <br>
        </div>
        <div id="footer">
            <img id="piedp" src="./logoscsc/footer.png">
        </div>
    </div>
</div>
<script src= "https://cdn.zingchart.com/zingchart.min.js"></script>
<script> zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";</script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript">
    const Vea = ['FCCE01','E31D1A'];
    const Disco = ['EB2A31','F48985'];
    const Jumbo = ['16A751','A5CE3A'];
</script>
</body>
</html>