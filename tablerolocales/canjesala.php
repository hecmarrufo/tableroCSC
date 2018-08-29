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
$_SESSION['cadena'];
if(!$_SESSION['local']){
    header('Location: entrada.php');
    die();
}
include 'conn.php';

?>
<script>
    <?php
    $sql ="SET NOCOUNT ON
select dw_fecha,fecha,mes into #fc
from repositorio_marketing..Dim_Fecha
where año=convert (varchar (4),DATEADD (yyyy,0,getdate()),112) and mes in ((month(DATEADD (dd,-8,getdate()))-1),(month(DATEADD (dd,-8,getdate()))-2),(month(DATEADD (dd,-8,getdate()))-3),(month(DATEADD (dd,-8,getdate()))-4),(month(DATEADD (dd,-8,getdate()))-5),(month(DATEADD (dd,-8,getdate()))-6))

--tkts
select 
dw_ticket,
SUM(dtociva) dto
into #tkt
from Repositorio_Marketing..Facts_Dto18 a inner join #fc b
on a.dw_fecha=b.dw_fecha
where dw_prefijo=736
group by dw_ticket

--
select
MONTH(fecha)mes,
a.dw_ticket,
dto,
b.NroLocal
into #tkts2
from #tkt a inner join Repositorio_Marketing..Facts_Tickets18 b
on a.dw_ticket=b.dw_ticket 
where NroLocal=".$_SESSION['numloc']."

--final
select 
mes,
SUM(dto) venta,
count(distinct dw_ticket)qtkts
from #tkts2 
where nrolocal=".$_SESSION['numloc']."
group by mes
order by 1
drop table #fc
drop table #tkt
drop table #tkts2
";
    if ($_SESSION['cadena']!='Vea'){
    $stmt = sqlsrv_query($conn, $sql);

    if( $stmt === false ) {
        if( ($errors = sqlsrv_errors() ) != null) {
            foreach( $errors as $error ) {
                echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                echo "code: ".$error[ 'code']."<br />";
                echo "message: ".$error[ 'message']."<br />";
            }
        }
    }
    ?>
    let data=[<?php
        while($info=sqlsrv_fetch_array($stmt)){
            echo '{"mes":'.$info['mes'].
                ',"venta":'.$info['venta'].
                ',"qtkts":'.$info['qtkts'].'},';
        }
        ?>];
    <?php sqlsrv_free_stmt( $stmt);include 'closeconn.php';}?>
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
                    <div id="graph"></div>
                    <div class="nodisp imgno">
                        <img src="icons/road-closure.png" class="nodisp">
                    </div>
                    <div class="nodisp">
                        <p>Lo sentimos, este reporte no está disponible para este Local.</p>
                    </div>
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
    // const Vea = ['FCCE01','E31D1A'];
    // const Disco = ['EB2A31','F48985'];
    // const Jumbo = ['16A751','A5CE3A'];
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
    if("<?PHP echo $_SESSION['cadena'];?>"!=='Vea'){
        document.getElementsByClassName('nodisp').style.display = 'none';
    const config = {
        "layout":"1x2",
        "graphset":[
            {
                "type":"bar",
                "background-color":"none",
                "title":{
                    "text":"Venta generada"
                },
                "plot":{
                    "hover-state":{
                        "border-color":"#000",
                        "border-width":1,
                        "visible":1
                    },
                    "tooltip":{
                        "visible":0
                    }
                },
                "scale-x":{
                    "values":[
                        meses[data[0]['mes']-1],
                        meses[data[1]['mes']-1],
                        meses[data[2]['mes']-1],
                        meses[data[3]['mes']-1],
                        meses[data[4]['mes']-1],
                        meses[data[5]['mes']-1],

                    ],
                    "max-items":7,
                },
                "scale-y":{
                    "visible":0,
                },
                "series":[
                    {
                        "type":"bar",
                        "values":[
                            data[0]['venta'],
                            data[1]['venta'],
                            data[2]['venta'],
                            data[3]['venta'],
                            data[4]['venta'],
                            data[5]['venta'],
                        ],
                        "value-box":[
                            {
                                "text": "$%v",
                                "placement": "top-out",
                            },
                        ],
                    },
                ]
            },
            {
                "type":"bar",
                "background-color":"none",
                "title":{
                    "text":"Clientes"
                },
                "plot":{
                    "hover-state":{
                        "border-color":"#000",
                        "border-width":1,
                        "visible":1
                    },
                    "tooltip":{
                        "visible":0
                    }
                },
                "scale-x":{
                    "values":[
                        meses[data[0]['mes']-1],
                        meses[data[1]['mes']-1],
                        meses[data[2]['mes']-1],
                        meses[data[3]['mes']-1],
                        meses[data[4]['mes']-1],
                    ],
                    "max-items":7,
                },
                "scale-y":{
                    "visible":0,
                },
                "series":[
                    {
                        "type":"bar",
                        "values":[
                            data[0]['qtkts'],
                            data[1]['qtkts'],
                            data[2]['qtkts'],
                            data[3]['qtkts'],
                            data[4]['qtkts'],
                            data[5]['qtkts'],
                        ],
                        "value-box":[
                            {
                                "text": "%v",
                                "placement": "top-out",
                            },
                        ],
                    },
                ]
            },
        ]
    };

        zingchart.render({
            id: 'graph',
            data: config,
            height: 500,
            width: 1200
        });
    }else{
        document.getElementsByClassName('nodisp').style.display = 'block';
    }
</script>
</body>
</html>