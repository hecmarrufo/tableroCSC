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
<script>
    <?php
    $sql ="select clientes,venta_total,mes from tsp_cupones_cumple_locales where NroLocal=".$_SESSION['numloc']." ORDER BY mes";
    $stmt = sqlsrv_query($conn, $sql);
    ?>
    let cumpl=[<?php
        while($info=sqlsrv_fetch_array($stmt)){
            echo '{"venta":'.$info['venta_total'].
                ',"clientes":'.$info['clientes'].
                ',"mes":'.$info['mes'].'},';
        }
        ?>];
    <?php sqlsrv_free_stmt( $stmt);include 'closeconn.php';?>
    const meses = [
        "Enero",
        "Febrero",
        "Marzo",
        "Abril",
        "Mayo",
        "Junio",
        "Julio",
        "Agosto",
        "Septiembre",
        "Octubre",
        "Noviembre",
        "Diciembre",
    ];
    let ml = [];
    for(x in cumpl){
        ml[x] = meses[cumpl[x]['mes']];
    }
    let separar = function (obj,x) {
        z=[];
        for(y in obj){
            z[y]=obj[y][x];
        }
        return z;
    }
</script>
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

                <div id="myChart"></div>

            </div>

        </div>
        <div id="footer">
            <img id="piedp" src="./logoscsc/footer.png">
        </div>
    </div>
</div>
<script src= "https://cdn.zingchart.com/zingchart.min.js"></script>
<script> zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";</script>

<script type="text/javascript">
    const myConfig = {
        layout:"1x2",

        graphset:[
            {//1er graf
                "background-color":"none",
                "type":"bar",
                "title":{
                    "text":"Clientes que utilizaron el cupón "
                },
                "plot": {
                    "value-box": {
                        "text": "%v<br>clientes",
                        "placement": "top-out",
                        // "font-color": "white"
                    }
                },
                "scale-x": {
                    "values": ml,
                    "item": {
                    },
                },
                "scale-y":{
                    "visible":0,
                },
                series: [
                    {
                        values: separar(cumpl,"clientes"),
                    },
                ]
            },
            {//2do graf
                "background-color":"none",
                "type":"bar",
                "title":{
                    "text":"Venta Generada"
                },
                "plot": {
                    "value-box": {
                        "text": "%v $",
                        "placement": "top-out",
                        // "font-color": "white"
                    }
                },
                "scale-x": {
                    "values": ml,
                    "item": {
                    },
                },
                "scale-y":{
                    "visible":0,
                },
                series: [
                    {
                        values: separar(cumpl,"venta"),
                    },
                ]
            },
        ]
    };

    zingchart.render({
        id: 'myChart',
        data: myConfig,
        height: 500,
        width: 1000
    });
</script>

</body>
</html>