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
    $sql ="SELECT qctes, q_veces FROM tsp_js_apertura_frecuencia_qveces_mes WHERE nrolocal=".$_SESSION['numloc']." ORDER BY q_veces";
    $stmt = sqlsrv_query($conn, $sql);
    ?>
    let cant=[<?php
        while($info=sqlsrv_fetch_array($stmt)){
            echo '{"veces":'.$info['q_veces'].
                ',"clientes":'.$info['qctes'].'},';
        }
        ?>];
    <?php sqlsrv_free_stmt( $stmt);
    $sql ="SELECT (cast(qtkts as decimal(19,2))/cast(qctes as decimal (19,2))) as prom FROM tsp_js_apertura_frecuencia_mes WHERE nrolocal=".$_SESSION['numloc'];
    $stmt = sqlsrv_query($conn, $sql);
    ?>
    let prom=<?php
        while($info=sqlsrv_fetch_array($stmt)){
            echo $info['prom'];
        }
        ?>;
    // console.dir(cant);
    <?php sqlsrv_free_stmt( $stmt);include 'closeconn.php';?>
    // let promedio=[0,0];
    for(x in cant){
        // promedio[0] += cant[x]['veces'];
        // promedio[1] += cant[x]['clientes'];
        if(x >5){
            cant[5]['clientes']+=cant[x]['clientes'];
        }
    }
    console.log(Math.round( prom * 10 ) / 10);
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
                <div>
                    <p id="text"></p>
                </div>
                <div>
                    <div id="graph"></div>
                </div>
            </div>
        </div>
        <div id="footer">
            <img id="piedp" src="./logoscsc/footer.png">
        </div>
    </div>
</div>
<script src= "https://cdn.zingchart.com/zingchart.min.js"></script>
<script> zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";</script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script type="text/javascript">
    const graph = {
        backgroundColor:'none',
        type: "ring",
        labels:[
            {
                "text":"Frecuencia<br>Mensual<br>Promedio",
                // "font-family":"Georgia",
                "font-size":"12",
                "x":"90%",
                "y":"5%"
            },
            {
                "text":Math.round( prom * 10 ) / 10,
                "font-color":"#29A2CC",
                "font-family":"Georgia",
                "font-size":"40",
                "x":"90%",
                "y":"12%"
            },
        ],
        plot: {
            slice:'50%',
            borderWidth:0,
            backgroundColor:'#FBFCFE',
            animation:{
                effect:2,
                sequence:3
            },
            valueBox: [
                {
                    type: 'all',
                    text: '%t',
                    placement: 'out'
                },
                {
                    type: 'all',
                    text: '%npv%',
                    placement: 'in'
                }
            ]
        },
        tooltip:{
            fontSize:16,
            anchor:'c',
            x:'50%',
            y:'45%',
            sticky:true,
            backgroundColor:'none',
            borderWidth:0,
            thousandsSeparator:',',
            text:'<span style="font-weight: bold; color:%color">%v clientes:</span><br><span style="font-weight: bold;color:%color">%t</span>',
            mediaRules:[
                {
                    maxWidth:500,
                    y:'54%',
                }
            ]
        },
        plotarea: {
            backgroundColor: 'transparent',
            borderWidth: 0,
            borderRadius: "0 0 0 10",
            margin: "0 0 0 0",
        },
        legend : {
            toggleAction:'none',
            backgroundColor:'none',
            borderWidth:0,
            adjustLayout:true,
            align:'center',
            verticalAlign:'bottom',
            marker: {
                type:'circle',
                cursor:'pointer',
                borderWidth:0,
                size:6
            },
            item: {
                fontColor: "#777",
                cursor:'pointer',
                offsetX:-6,
                fontSize:14
            },
            mediaRules:[
                {
                    // maxWidth:"80%",
                    maxHeight:"70%",
                    visible:false
                }
            ]
        },
        scaleR:{
            refAngle:270
        },
        series : [
            {
                text: "1 vez",
                values : [cant[0]['clientes']],
                lineColor: "#F17A24",
                backgroundColor: "#F17A24",
                lineWidth: 1,
                marker: {
                    backgroundColor: '#F17A24'
                }
            },
            {
                text: "2 veces",
                values : [cant[1]['clientes']],
                lineColor: "#CF7C99",
                backgroundColor: "#CF7C99",
                lineWidth: 1,
                marker: {
                    backgroundColor: '#CF7C99'
                }
            },
            {
                text: "3 veces",
                values : [cant[2]['clientes']],
                lineColor: "#C3A33F",
                backgroundColor: "#C3A33F",
                lineWidth: 1,
                marker: {
                    backgroundColor: '#C3A33F'
                }
            },
            {
                text: "4 veces",
                values : [cant[3]['clientes']],
                lineColor: "#3695D0",
                backgroundColor: "#3695D0",
                lineWidth: 1,
                marker: {
                    backgroundColor: '#3695D0'
                }
            },
            {
                text: "5 veces",
                values : [cant[4]['clientes']],
                lineColor: "#E80C60",
                backgroundColor: "#E80C60",
                lineWidth: 1,
                marker: {
                    backgroundColor: '#E80C60'
                }
            },
            {
                text: ">6 veces",
                values : [cant[5]['clientes']],
                lineColor: "#9B26AF",
                backgroundColor: "#9B26AF",
                lineWidth: 1,
                marker: {
                    backgroundColor: '#9B26AF'
                }
            }
        ]
    };

    zingchart.render({
        id : 'graph',
        data: {
            graphset: [graph]
        },
        height: '499',
        width: '99%'
    });
</script>
</body>
</html>