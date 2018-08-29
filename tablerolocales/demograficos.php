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
    $sql="
select	fl_rango_edad, ROUND((margencubo-porcdtofinanciero)*100,0) as margenneto
from	(select nrolocal, fl_rango_edad, dtofinanciero/vtabrutapos as porcdtofinanciero, margencubo
from	(Select	qq.*, (vtaciva+dtocomercial+dtofinanciero)vtabrutapos, ((vtacubo-costo)/vtacubo)margencubo
from	(select	nrolocal, fl_rango_edad ,SUM(venta_civa)vtaciva, SUM(desc_financiero)dtofinanciero, SUM(desc_comercial)dtocomercial, 
		sum(costo_siva)costo, SUM(vta_cubo)vtacubo
from	tsp_js_datos_demograficos
where	nrolocal ='".$_SESSION['numloc']."'
group by fl_rango_edad, nrolocal)qq)ww)ee";
    $stmt = sqlsrv_query($conn, $sql);
    ?>
    let data=[<?php
        while($info=sqlsrv_fetch_array($stmt)){
            echo '{"edad":"'.$info['fl_rango_edad'].
                '","mneto":'.$info['margenneto'].'},';
        }
        ?>];
    <?php sqlsrv_free_stmt( $stmt);
    $sql="SET NOCOUNT ON
select fl_rango_edad,nrolocal, sum(q_tkts)  as Tkts, sum(q_ctes) as Cts, SUM(vta_cubo) as Vta
into #temp 
from tsp_js_datos_demograficos
where nrolocal=".$_SESSION['numloc']."
group by fl_rango_edad, nrolocal
order by fl_rango_edad

select SUM(tkts)tottkts, SUM(cts) as totcts, SUM(vta) as totvta
into #tt1 from #temp

alter table #temp add totaltkts int
alter table #temp add totalcts int
alter table #temp add totalvta int

update #temp set totaltkts = (select tottkts from #tt1)
update #temp set totalcts = (select totcts from #tt1)
update #temp set totalvta = (select totvta from #tt1)

select fl_rango_edad,
ROUND((cast(tkts as decimal(19,2))/cast(totaltkts as decimal (19,2))*100),0) as porcentajetkts,
ROUND((cast(cts as decimal(19,2))/cast(totalcts as decimal (19,2))*100),0) as porcentajects,
ROUND((cast(vta as decimal(19,2))/cast(totalvta as decimal (19,2))*100),0) as porcentajevta
from #temp a

drop table #temp
drop table #tt1";
    $stmt = sqlsrv_query($conn, $sql);?>
    let datos=[<?php
        while($info=sqlsrv_fetch_array($stmt)){
            echo '{"edad":"'.$info['fl_rango_edad'].
                '","tkts":'.$info['porcentajetkts'].
                ',"clis":'.$info['porcentajects'].
                ',"vta":'.$info['porcentajevta'].'},';
        }
        ?>];
    <?php sqlsrv_free_stmt( $stmt);
    $sql="
select      fl_rango_edad, ROUND((margencubo-porcdtofinanciero)*100,0) as margenneto
from  (select cadena, fl_rango_edad, dtofinanciero/vtabrutapos as porcdtofinanciero, margencubo
from  (Select     qq.*, (vtaciva+dtocomercial+dtofinanciero)vtabrutapos, ((vtacubo-costo)/vtacubo)margencubo
from  (select     cadena, fl_rango_edad ,SUM(venta_civa)vtaciva, SUM(desc_financiero)dtofinanciero, SUM(desc_comercial)dtocomercial, 
            sum(costo_siva)costo, SUM(vta_cubo)vtacubo
from  tsp_js_datos_demograficos
where cadena = '".$_SESSION['cadena']."'
group by fl_rango_edad, cadena)qq)ww)ee";
    $stmt = sqlsrv_query($conn, $sql);
    ?>
    let dataC=[<?php
        while($info=sqlsrv_fetch_array($stmt)){
            echo '{"edad":"'.$info['fl_rango_edad'].
                '","mneto":'.$info['margenneto'].'},';
        }
        ?>];
    <?php sqlsrv_free_stmt( $stmt);
    $sql="SET NOCOUNT ON
select fl_rango_edad,cadena, sum(q_tkts)  as Tkts, sum(q_ctes) as Cts, SUM(vta_cubo) as Vta
into #cad 
from tsp_js_datos_demograficos
where cadena='".$_SESSION['cadena']."'
group by fl_rango_edad, cadena
order by fl_rango_edad

select SUM(tkts)tottkts, SUM(cts) as totcts, SUM(vta) as totvta
into #cade from #cad

alter table #cad add totaltkts int
alter table #cad add totalcts int
alter table #cad add totalvta varchar(50)

update #cad set totaltkts = (select tottkts from #cade)
update #cad set totalcts = (select totcts from #cade)
update #cad set totalvta = (select totvta from #cade)

select fl_rango_edad,
ROUND((cast(tkts as decimal(19,2))/cast(totaltkts as decimal (19,2))*100),0) as porcentajetkts,
ROUND((cast(cts as decimal(19,2))/cast(totalcts as decimal (19,2))*100),0) as porcentajects,
ROUND((cast(vta as decimal(19,2))/cast(totalvta as decimal (19,2))*100),0) as porcentajevta
from #cad a

drop table #cad
drop table #cade";
    $stmt = sqlsrv_query($conn, $sql);?>
    let datosC=[<?php
        while($info=sqlsrv_fetch_array($stmt)){
            echo '{"edad":"'.$info['fl_rango_edad'].
                '","tkts":'.$info['porcentajetkts'].
                ',"clis":'.$info['porcentajects'].
                ',"vta":'.$info['porcentajevta'].'},';
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
                    <div id="myChart"></div>
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
<script type="text/javascript">
    const Vea = ['FCCE01','E31D1A'];
    const Disco = ['EB2A31','F48985'];
    const Jumbo = ['16A751','A5CE3A'];
    var myConfig = {
        "layout":"2x2",
        "graphset":[
            {
                "type":"mixed",
                "background-color":"none",
                "title":{
                    "text":"Clientes"
                },
                "plot":{
                    "bar-width":"60%",
                    "bars-overlap":"60%",
                    "hover-state":{
                        "border-color":"#000",
                        "border-width":1,
                        "visible":1
                    },
                    "tooltip":{
                        "visible":0
                    }
                },
                "legend":{
                    "background-color":"none",
                    "border-width":0,
                    "shadow":false,
                    "layout":"float",
                    "margin":"0px 2% auto auto",
                    "border-color":"none",
                    "toggle-action":"none",
                },
                "scale-x":{
                    "values":[
                        data[4]['edad'],
                        data[0]['edad'],
                        data[1]['edad'],
                        data[2]['edad'],
                        data[3]['edad'],
                    ],
                    "max-items":7,
                },
                "series":[
                    {
                        "type":"bar",
                        "values":[
                            datosC[4]['clis'],
                            datosC[0]['clis'],
                            datosC[1]['clis'],
                            datosC[2]['clis'],
                            datosC[3]['clis'],
                        ],
                        "text":"Cadena",
                        "value-box":[
                            {
                                "text": "%v%",
                                "placement": "top-out",
                                "font-color": "#808080",
                                "alpha":0.5,
                                "padding-right":14,
                            },
                        ],
                        "background-color":"#"+<?PHP echo $_SESSION['cadena'].'[0]';?>,
                        // "alpha":"0.9"
                    },
                    {
                        "type":"bar",
                        "values":[
                            datos[4]['clis'],
                            datos[0]['clis'],
                            datos[1]['clis'],
                            datos[2]['clis'],
                            datos[3]['clis'],
                        ],
                        "text":"Local",
                        "value-box":[
                            {
                                "text": "%v%",
                                "placement": "top-out",
                                "font-color": "#"+<?PHP echo $_SESSION['cadena'].'[1]';?>,
                                "padding-left":13,
                            },
                        ],
                        "background-color":"#"+<?PHP echo $_SESSION['cadena'].'[1]';?>,
                        "alpha":"1"
                    },
                ]
            },
            {
                "type":"mixed",
                "background-color":"none",
                "title":{
                    "text":"Tickets"
                },
                "plot":{
                    "bar-width":"60%",
                    "bars-overlap":"60%",
                    "hover-state":{
                        "border-color":"#000",
                        "border-width":1,
                        "visible":1
                    },
                    "tooltip":{
                        "visible":0
                    }
                },
                "legend":{
                    "background-color":"none",
                    "border-width":0,
                    "shadow":false,
                    "layout":"float",
                    "margin":"0px 2% auto auto",
                    "border-color":"none",
                    "toggle-action":"none",
                },
                "scale-x":{
                    "values":[
                        data[4]['edad'],
                        data[0]['edad'],
                        data[1]['edad'],
                        data[2]['edad'],
                        data[3]['edad'],
                    ],
                    "max-items":7,
                },
                "series":[
                    {
                        "type":"bar",
                        "values":[
                            datosC[4]['tkts'],
                            datosC[0]['tkts'],
                            datosC[1]['tkts'],
                            datosC[2]['tkts'],
                            datosC[3]['tkts'],
                        ],
                        "text":"Cadena",
                        "value-box":[
                            {
                                "text": "%v%",
                                "placement": "top-out",
                                "font-color": "#808080",
                                "alpha":0.5,
                                "padding-right":14,
                            },
                        ],
                        "background-color":"#"+<?PHP echo $_SESSION['cadena'].'[0]';?>,
                    },
                    {
                        "type":"bar",
                        "values":[
                            datos[4]['tkts'],
                            datos[0]['tkts'],
                            datos[1]['tkts'],
                            datos[2]['tkts'],
                            datos[3]['tkts'],
                        ],
                        "text":"Local",
                        "value-box":[
                            {
                                "text": "%v%",
                                "placement": "top-out",
                                "font-color": "#"+<?PHP echo $_SESSION['cadena'].'[1]';?>,
                                "padding-left":13,
                            },
                        ],
                        "background-color":"#"+<?PHP echo $_SESSION['cadena'].'[1]';?>,
                        "alpha":"1"
                    },
                ]
            },
            {
                "type":"mixed",
                "background-color":"none",
                "title":{
                    "text":"Margen Neto"
                },
                "plot":{
                    "bar-width":"60%",
                    "bars-overlap":"60%",
                    "hover-state":{
                        "border-color":"#000",
                        "border-width":1,
                        "visible":1
                    },
                    "tooltip":{
                        "visible":0
                    }
                },
                "legend":{
                    "background-color":"none",
                    "border-width":0,
                    "shadow":false,
                    "layout":"float",
                    "margin":"0px 2% auto auto",
                    "border-color":"none",
                    "toggle-action":"none",
                },
                "scale-x":{
                    "values":[
                        data[4]['edad'],
                        data[0]['edad'],
                        data[1]['edad'],
                        data[2]['edad'],
                        data[3]['edad'],
                    ],
                    "max-items":7,
                },
                "series":[
                    {
                        "type":"bar",
                        "values":[
                            dataC[4]['mneto'],
                            dataC[0]['mneto'],
                            dataC[1]['mneto'],
                            dataC[2]['mneto'],
                            dataC[3]['mneto'],
                        ],
                        "text":"Cadena",
                        "value-box":[
                            {
                                "text": "%v%",
                                "placement": "top-out",
                                "font-color": "#808080",
                                "alpha":0.5,
                                "padding-right":14,
                            },
                        ],
                        "background-color":"#"+<?PHP echo $_SESSION['cadena'].'[0]';?>,
                    },
                    {
                        "type":"bar",
                        "values":[
                            data[4]['mneto'],
                            data[0]['mneto'],
                            data[1]['mneto'],
                            data[2]['mneto'],
                            data[3]['mneto'],
                        ],
                        "text":"Local",
                        "value-box":[
                            {
                                "text": "%v%",
                                "placement": "top-out",
                                "font-color": "#"+<?PHP echo $_SESSION['cadena'].'[1]';?>,
                                "padding-left":13,
                            },
                        ],
                        "background-color":"#"+<?PHP echo $_SESSION['cadena'].'[1]';?>,
                        "alpha":"1"
                    },
                ]
            },
            {
                "type":"mixed",
                "background-color":"none",
                "title":{
                    "text":"Venta Cubo"
                },
                "plot":{
                    "bar-width":"60%",
                    "bars-overlap":"60%",
                    "hover-state":{
                        "border-color":"#000",
                        "border-width":1,
                        "visible":1
                    },
                    "tooltip":{
                        "visible":0
                    }
                },
                "legend":{
                    "background-color":"none",
                    "border-width":0,
                    "shadow":false,
                    "layout":"float",
                    "margin":"0px 2% auto auto",
                    "border-color":"none",
                    "toggle-action":"none",
                },
                "scale-x":{
                    "values":[
                        data[4]['edad'],
                        data[0]['edad'],
                        data[1]['edad'],
                        data[2]['edad'],
                        data[3]['edad'],
                    ],
                    "max-items":7,
                },
                "series":[
                    {
                        "type":"bar",
                        "values":[
                            datosC[4]['vta'],
                            datosC[0]['vta'],
                            datosC[1]['vta'],
                            datosC[2]['vta'],
                            datosC[3]['vta'],
                        ],
                        "text":"Cadena",
                        "value-box":[
                            {
                                "text": "%v%",
                                "placement": "top-out",
                                "font-color": "#808080",
                                "alpha":0.5,
                                "padding-right":14,
                            },
                        ],
                        "background-color":"#"+<?PHP echo $_SESSION['cadena'].'[0]';?>,
                        "alpha":"1"
                    },
                    {
                        "type":"bar",
                        "values":[
                            datos[4]['vta'],
                            datos[0]['vta'],
                            datos[1]['vta'],
                            datos[2]['vta'],
                            datos[3]['vta'],
                        ],
                        "text":"Local",
                        "value-box":[
                            {
                                "text": "%v%",
                                "placement": "top-out",
                                "font-color": "#"+<?PHP echo $_SESSION['cadena'].'[1]';?>,
                                "padding-left":13,
                            },
                        ],
                        "background-color":"#"+<?PHP echo $_SESSION['cadena'].'[1]';?>,
                        "alpha":"1"
                    },
                ]
            },

        ]
    };
    zingchart.render({
        id: 'myChart',
        data: myConfig,
        height: 500,
        width: 1200
    });
</script>
</body>
</html>