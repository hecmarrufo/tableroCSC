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
select mes,count(email) as email into #ab
from TSP_altas_email_reporte
where año = ".date("Y")."
And nrolocal=".$_SESSION['numloc']."
and email='SI'
group by mes order by mes

select mes,count(email) as email into #cd
from TSP_altas_email_reporte
where año = ".date("Y")."
And nrolocal=".$_SESSION['numloc']."
group by mes order by mes

select a.mes,
((a.email *100)/b.email)ttl
into #final
from #ab a  join #cd b
on a.mes=b.mes

--de la cadena
select mes,count(email) as email into #ab2
from TSP_altas_email_reporte
where año = ".date("Y") ."
And cadena='".$_SESSION['cadena']."'
and email='SI'
group by mes order by mes

select mes,count(email) as email into #cd2
from TSP_altas_email_reporte
where año = ".date("Y")."
And cadena='".$_SESSION['cadena']."'
group by mes order by mes


select a.mes,
((a.email *100)/b.email)ttl2
into #final2
from #ab2 a  join #cd2 b
on a.mes=b.mes


select #final.mes, #final.ttl,#final2.ttl2
from #final
inner join #final2 on #final.mes=#final2.mes

drop table #ab2
drop table #cd2
drop table #final2
drop table #ab
drop table #cd
drop table #final

";
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
                ',"ttl":'.$info['ttl'].
                ',"ttl2":'.$info['ttl2'].'},';
        }
        ?>];
    <?php sqlsrv_free_stmt( $stmt);include 'closeconn.php';?>
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
                            data[0]['ttl'],
                            data[1]['ttl'],
                            data[2]['ttl'],
                            data[3]['ttl'],
                            data[4]['ttl'],
                            data[5]['ttl'],
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
                            data[0]['ttl2'],
                            data[1]['ttl2'],
                            data[2]['ttl2'],
                            data[3]['ttl2'],
                            data[4]['ttl2'],
                            data[5]['ttl2'],
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

</script>
</body>
</html>