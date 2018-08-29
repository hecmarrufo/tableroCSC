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
        select {
            display: inline-block;
            height:30px;
            width: 100%;
            padding: 2px 10px 2px 2px;
            outline: none;
            color: #000000;
            border: 1px solid #C8BFC4;
            border-radius: 4px;
            box-shadow: inset 1px 1px 2px #ddd8dc;
            background: #DDD;
        }
        #filtro{
            display: inline-block;
            height:25px;
            width: 70%;
            padding: 2px 10px 2px 2px;
            outline: none;
            color: #000000;
            border: 1px solid #C8BFC4;
            border-radius: 4px;
            box-shadow: inset 1px 1px 2px #ddd8dc;
            background: #DDD;
        }
        button{
            display: inline-block;
            height:30px;
            outline: none;
            color: #000000;
            margin-left: 1%;
            border: 1px solid #C8BFC4;
            border-radius: 4px;
            box-shadow: inset 1px 1px 2px #ddd8dc;
            background: #DDD;
        }
        .icn{
            height: 100px;
        }
        .sep{
            margin-left: 35px;
            margin-right: 35px;
        }
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
                <div>
                    <?php
                    $sql ="select Distinct categoria from tsp_heatmap Where nrolocal=".$_SESSION['numloc']." order by categoria";
                    $stmt = sqlsrv_query($conn, $sql);
                    echo "<select name='descfam' id='descfam'>";
                    echo '<option value="">- Descripción Familia Productos -</option>';
                    while($info=sqlsrv_fetch_array($stmt)){
                        unset($name);
                        $categoria = $info['categoria'];
                        $categoria2 = explode(' ', $categoria, 2);
                        echo '<option value="'.$categoria2[0].'">'.$categoria.'</option>';
                    }
                    echo "</select>";
                    sqlsrv_free_stmt($stmt);
                    ?>
                </div>
                <div>
                    <input type="text" id="filtro" placeholder="Buscar.."><button onclick="find()"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <br>
            <div class="container">
                <div id="graph"></div>
                <div><br>
                    <img class="icn" src="./icons/user-male-circle.png">
                    <img class="icn" src="./icons/user-female-circle.png">
                    <div id="barr1"
                         style="
                         height: 20px;
                         border: #000 solid 2px;
                         border-radius: 10px;
                         background-image: linear-gradient(to right, dodgerblue , hotpink);
                         color: #fff;
                        ">0%<span class='sep'></span>0%</div>
                    <hr>
                    <img class="icn" src="./icons/sun.png">
                    <img class="icn" src="./icons/bright-moon.png">
                    <div id="barr2"
                         style="height: 20px;border: #000 solid 2px;border-radius: 10px;
                         background-image: linear-gradient(to right, khaki , midnightblue);
                         color: #fff;">
                        0%<span class='sep'></span>0%</div>
                    <hr>
                    <img class="icn" src="./icons/calendar.png">
                    <div id="barr3"
                         style="height: 20px;border: #000 solid 2px;
                         border-radius: 10px;
                         background-image: linear-gradient(to right, #ccc , #DDD);
                         color: #000;">
                        0%<span class='sep'></span>0%</div>
                    <div>Lun-Jue<span class='sep'></span>Vie-Dom</div>
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
    let modificar = function (datos) {
        zingchart.exec('graph', 'modify', {
            graphid : 0,
            data :datos,
            height: '500',
            width: '99%'
        });
        $( "#descfam" ).find( ":hidden" ).show();
        $('#filtro').val('').attr("placeholder", "Buscar..");
    };
    let separar = function(obj,parte){
        let resultado=[];
        for (x in obj){
            resultado[x]=obj[x][parte];
        }
        return resultado;
    };
    let ddsemana = function(obj,ds){
        let resobj=[{'hrs':0,'tks':0}];
        let zz=0;
        const modelo=(separar(total,'hora'));
        for (x in obj) {
            if (obj[x]['diaF'].trim()===ds){
                resobj[zz]={'hrs':obj[x]['horaF'],'tks':obj[x]['tktsF']};
                zz++;
            }
        }
        //si no se vendio nada en ese dia, poner las horas corresp, y los tks en 0
        if (resobj.length===1 && resobj[0]['hrs']===0 && resobj[0]['tks']===0){
            let resulta =[];
            resobj[0]={'hrs':modelo[0],'tks':0};
            for (qq=1; qq<modelo.length; qq++) {
                resobj.push({'hrs':modelo[qq],'tks':0});
            }
            for (ee in resobj){
                resulta[ee]=resobj[ee]['tks'];
            }
            return resulta;
        }
        //si los del medio son diferentes
        function checkmid() {
            for (ww in modelo){
                if (resobj[ww]['hrs']!==modelo[ww])
                    resobj.splice(ww, 0, {'hrs':modelo[ww],'tks':0});
            }
        }
        //si el 1ero o el ult son diferentes:
        function ciclo() {
            if ((modelo.length !== resobj.length)&&(resobj[0]['hrs']!==modelo[0])){
                resobj.splice(0, 0, {'hrs':modelo[0],'tks':0});
                ciclo();
            } else if ((modelo.length !== resobj.length)&&(resobj[resobj.length-1]['hrs']!==modelo[modelo.length-1])){
                resobj.push({'hrs':modelo[modelo.length-1],'tks':0});
                ciclo();
            } else if (modelo.length !== resobj.length) {
                checkmid();
                ciclo();
            }
            // }
        }
        ciclo();
        //separar el objeto y return solo los tks, en el orden de las hras (ya llega ordenado)
        let resulta2=[];
        for (ee in resobj){
            resulta2[ee]=resobj[ee]['tks'];
        }
        let resulta3=[];
        const suma = resulta2.reduce((x, y) => x + y);
        for(rr in resulta2){
            resulta3.push(      Math.round(   ((resulta2[rr]/suma)*100)  )     );
        }
        return resulta3;
    };
    // function percentage(num, per){
    //     return (num/100)*per;
    // }
    let newdata = function(obj1,obj2){
        // const max = Math.max( ...separar(obj2,'tktsF') );
        let config = {
            "type": "heatmap",
            "plotarea" : {
                "margin" : "dynamic"
            },
            "plot":{
                "aspect":"brightness",
                "value-box": {"text": "%v%"},
                "background-color":"#"+<?PHP echo $_SESSION['cadena'].'[0]';?>,
                "rules": [
                    /*
                    {
                        "rule":"%p == 0",
                        "background-color":"",
                        "borderBottom": "5px solid #FFF",
                    },
                    {
                        "rule": "%v >="+percentage(max,80),
                        "alpha":1
                    },
                    {
                        "rule": "%v <"+percentage(max,80)+" && %v >="+percentage(max,60),
                        "alpha":0.8
                    },
                    {
                        "rule": "%v <"+percentage(max,60)+" && %v >="+percentage(max,40),
                        "alpha":0.6
                    },
                    {
                        "rule": "%v <"+percentage(max,40)+" && %v >="+percentage(max,20),
                        "alpha":0.4
                    },
                    {
                        "rule": "%v <"+percentage(max,20),
                        "alpha":0.2
                    },
                    */
                    {
                        "rule": "%v < 1",
                        "background-color": "none",
                    },
                    {
                        "rule":"%p == 0",
                        "background-color":"",
                        "borderBottom": "3px solid #FFF",
                    },
                    {
                        "rule":"%p == 1",
                        // "background-color":"",
                        "borderTop": "3px solid #FFF",
                    },

                ],
            },
            "scale-y": {
                "mirrored": true,
                "labels": ["<strong>TOTAL</strong>","Lun","Mar","Mié","Jue","Vie","Sab","Dom"],
                "guide": {
                    "visible": false
                },
            },
            "scale-x": {
                "labels": separar(obj1,'hora'),
                "format":"%v:00",
                "max-items":20,
                "placement":"opposite",
                "guide": {
                    "visible": false
                },
            },
            "series": [
                {"values": separar(obj1,'tkts'),/*Ttl*/},
                {"values": ddsemana(obj2,"1- Lunes")/*Lun*/},
                {"values": ddsemana(obj2,"2- Martes")},
                {"values": ddsemana(obj2,"3- Miércoles")},
                {"values": ddsemana(obj2,"4- Jueves")},
                {"values": ddsemana(obj2,"5- Viernes")},
                {"values": ddsemana(obj2,"6- Sábado")},
                {"values": ddsemana(obj2,"7- Domingo")/*Dom*/},
            ]
        };
        modificar(config);
    };
    function zero(obj){
        if(obj.length<2){
            if (obj[0]['sexo']==='F')
                obj.push({'sexo':'M','Psex':0});
            else
                obj.splice(0, 0, {'sexo':'F','Psex':0});
        }
    }
    function dn(obj){
        let mor=0;let nig=0;
        for (x in obj){
            if (parseInt(obj[x]['hora'])<15)
                mor+=obj[x]['tkts'];
            else
                nig+=obj[x]['tkts'];
        }
        return [mor,nig];
    }
    function wday(obj){
        let sem=0;let fds=0;
        for (x in obj){
            if (obj[x]['diasemana'].trim()==='1- Lunes'||
                obj[x]['diasemana'].trim()==='2- Martes'||
                obj[x]['diasemana'].trim()==='3- Miércoles'||
                obj[x]['diasemana'].trim()==='4- Jueves'){
                sem+=obj[x]['suma'];
            }else if (
                obj[x]['diasemana'].trim()==='5- Viernes'||
                obj[x]['diasemana'].trim()==='6- Sábado'||
                obj[x]['diasemana'].trim()==='7- Domingo'){
                fds+=obj[x]['suma'];
            }
        }
        return[sem,fds];
    }
    // let total=[];let demas=[];

    // $('#filtro').keyup(function () {
    //     let valthis = $(this).val().toLowerCase();
    //     let num = 0;
    //     $('select#descfam>option').each(function () {
    //         let text = $(this).text().toLowerCase();
    //         if(text.indexOf(valthis) !== -1)
    //         {$(this).show(); $(this).prop('selected',true);}
    //         else{$(this).hide();}
    //     });
    // });
    function llenar(){
        let cod = $("#descfam").val();
        $.ajax({
            url: 'populateHM.php',
            type: 'post',
            data: {codigo:cod},
            dataType: 'json',
            success:function(response){
                total=response[1];
                demas=response[2];
                sexo=response[3];
                weekday=response[4];
                // console.log(weekday);
                newdata(total,demas);
                zero(sexo);
                // console.log(wday(weekday));
                // console.log(dn(total)[0]);
                document.getElementById("barr1").innerHTML = sexo[1]['Psex'] + "%<span class='sep'></span>" + sexo[0]['Psex'] + "%";
                document.getElementById("barr2").innerHTML = dn(total)[0] + "%<span class='sep'></span>" + dn(total)[1] + "%";
                document.getElementById("barr3").innerHTML = wday(weekday)[0] + "%<span class='sep'></span>" + wday(weekday)[1] + "%";
            },
            error:function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    }
    $(document).ready(function(){
        $("#descfam").change(function(){
            llenar();
        });
    });
    // $('select#descfam').onblur{
    //     llenar();
    // }

    function dale() {
        let uno =$('#descfam option:not([style*=none])').length;
        console.log(uno);
        if(uno === 1){
            llenar();
            $( "#descfam" ).find( ":hidden" ).show();
            $('#filtro').val('');
        }else if(uno <= 0){
            $( "#descfam" ).find( ":hidden" ).show();
            $('#filtro').val('').attr("placeholder", "Categoría no encontrada");
        }
    }
    function find() {
        let valthis = $('#filtro').val().toLowerCase();
        $('select#descfam>option').each(function () {
            let text = $(this).text().toLowerCase();
            if(text.indexOf(valthis) !== -1){
                $(this).show(); $(this).prop('selected',true);
            }else{
                $(this).hide();
            }
        });
        dale();
    }
</script>
<script type="text/javascript">

    var graph = {
        "type": "heatmap",
        "plotarea" : {
            "margin" : "dynamic"
        },
        "plot":{
            "aspect":"brightness"
        },
        "scale-y": {
            "mirrored": true,
            "labels": ["","","","","","","","","","","","","",],
        },
        "scale-x": {
            "labels": ["","","","","","","","","","","","","",],
            "max-items":20,
            "placement":"opposite",
        },
        "series": [
            {"values": []},{"values": []},{"values": []},{"values": []},{"values": []},{"values": []},{"values": []},{"values": []}
        ]
    };
    zingchart.render({
        id : 'graph',
        data: {
            graphset: [graph]
        },
        height: '500',
        width: '99%'
    });
</script>
</body>
</html>