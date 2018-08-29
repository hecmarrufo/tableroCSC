<?php
/**
 * Created by PhpStorm.
 * User: hmarrufo
 * Date: 26/07/2018
 * Time: 03:46 PM
 */
include 'conn.php';
session_start();
$_SESSION['local'];
$_SESSION['numloc'];
$_SESSION['cadena'];
if(!$_SESSION['local']){
    header('Location: entrada.php');
    die();
}
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
                    <a href="logout.php">Salir</a>
                </div>
            </div>
        </div>
        <div id="content">
            <div class="container">
                <div class="tarjeta">
                    <a href="abandonadores.php">
                        <img class='icons' src=".\icons\add client.png">
                    </a>
                    <p class="nombres">Recuperación<br>Abandonadores</p>
                </div>
                <div class="tarjeta">
                    <a href="cumples.php">
                        <img class='icons' src=".\icons\birthday-cake.png">
                    </a>
                    <p class="nombres">Resultado<br>Cumpleaños</p>
                </div>
                <div class="tarjeta">
                    <a href="frecuencia.php">
                        <img class='icons' src=".\icons\change-user-male.png">
                    </a>
                    <p class="nombres">Frecuencia<br>Clientes</p>
                </div>
                <div class="tarjeta">
                    <a href="heatmap.php">
                        <img class='icons' src=".\icons\toilet.png">
                    </a>
                    <p class="nombres">Heatmap</p>
                </div>
                <!--                <div class="tarjeta">-->
                <!--                    <img class='icons' src=".\icons\happy.png">-->
                <!--                    <p class="nombres">Satisfacción<br>Última Compra</p>-->
                <!--                </div>-->
                <div class="tarjeta">
                    <a href="demograficos.php">
                        <img class='icons' src=".\icons\edad2.png" style="height: 9vw;width: 10vw">
                    </a>
                    <p class="nombres">Datos<br>Demográficos</p>
                </div>
                <div class="tarjeta">
                    <a href="canjesala.php" class="confirmation">
                        <img class='icons' src=".\icons\giving-tickets.png">
                        <p class="nombres">Canjes<br>En sala</p>
                    </a>
                </div>
                <div class="tarjeta">
                    <img class='icons' src=".\icons\money-box.png">
                    <p class="nombres">Inversión<br>Local</p>
                </div>
                <div class="tarjeta">
                    <a href="altasmail.php">
                        <img class='icons' src=".\icons\email.png">
                        <p class="nombres">Altas<br>Con Mail</p>
                    </a>
                </div>
                <div class="tarjeta">
                    <a href="perfilcliente.php">
                        <img class='icons' src=".\icons\membership-card.png">
                        <p class="nombres">Perfil Cliente<br>Fidelizado</p>
                    </a>
                </div>
                <div class="tarjeta">
                    <img class='icons' src=".\icons\genealogy.png">
                    <p class="nombres">Análisis<br>Categorías</p>
                </div>
                <div class="tarjeta">
                    <img class='icons' src=".\icons\birthday.png">
                    <p class="nombres">Reporte oferta<br>Cajero</p>
                </div>
                <div class="tarjeta">
                    <img class='icons' src=".\icons\group-of-projects.png">
                    <p class="nombres">Ranking<br>Participación</p>
                </div>
                <div class="tarjeta">
                    <img class='icons' src=".\icons\bad coupon.png">
                    <p class="nombres">Cupones<br>Baja Efectividad</p>
                </div>
                <div class="tarjeta">
                    <img class='icons' src=".\icons\cancel.png">
                    <p class="nombres">Reclamos</p>
                </div>

                <div class="tarjeta">
                    <img class='icons' src=".\icons\new-message.png">
                    <p class="nombres">Captación<br>E-Mails</p>
                </div>
                <div class="tarjeta">
                    <img class='icons' src=".\icons\timeline.png">
                    <p class="nombres">KPI Mensual<br>de VPN</p>
                </div>
                <div class="tarjeta">
                    <img class='icons' src=".\icons\identity-theft.png">
                    <p class="nombres">Reporte venta<br>Fidelizada</p>
                </div>
                <div class="tarjeta">
                    <img class='icons' src=".\icons\tarj en proceso.png">
                    <p class="nombres">Tarjetas<br>en Proceso</p>
                </div>
            </div>
            <div class="container">
            </div>
        </div>
        <div id="footer">
            <img id="piedp" src="./logoscsc/footer.png">
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script type="text/javascript">
    $('.confirmation').on('click', function () {
        return confirm('Este reporte tarda al rededor de 30 segundos en prepararse \n ¿Continuar?');
    });
</script>
</body>
</html>