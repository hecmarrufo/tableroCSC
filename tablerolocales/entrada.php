<!doctype html>
<html>
<head>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        //ajax call to db - populate locales based on region
        $(document).ready(function(){
            $("#cadenas").change(function(){
                let cad = $(this).val();
                $.ajax({
                    url: 'populate.php',
                    type: 'post',
                    data: {cadena:cad},
                    dataType: 'json',
                    success:function(response){
                        let len = response.length;
                        $("#regiones").empty().append("<option value=''>- Región -</option>");
                        for(i = 0; i<len; i++){
                            let reg = response[i]['reg'];
                            $("#regiones").append("<option value='"+reg+"'>"+reg+"</option>");
                        }
                    }
                });
            });
        });
        $(document).ready(function(){
            $("#regiones").change(function(){
                console.log("!");
                let reg = $(this).val();
                let cad = $("#cadenas").val();
                $.ajax({
                    url: 'populate2.php',
                    type: 'post',
                    data: {
                        "region":reg,
                        "cadena":cad
                    },
                    dataType: 'json',
                    success:function(response){
                        console.log(response);
                        let len = response.length;
                        $("#locales").empty().append("<option value=''>- Local -</option>");
                        for(i = 0; i<len; i++){
                            let local = response[i]['local'];
                            $("#locales").append("<option value='"+local+"'>"+local+"</option>");
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            });
        });
    </script>
    <style type="text/css">
        html{
            font-family: 'Lalezar', cursive;
            /*background-color: #dadada;*/
            background-color: rgba(218, 218, 218,0.4);
        }
        html,body {
            height: 95%;
        }
        .box{
            display: table;
            height: 100%;
            width: 100%;
        }
        .table-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        select {
            display: inline-block;
            height:30px;
            width: 180px;
            padding: 2px 10px 2px 2px;
            outline: none;
            /*color: #74646e;*/
            color: snow;
            border: 1px solid #C8BFC4;
            border-radius: 4px;
            box-shadow: inset 1px 1px 2px #ddd8dc;
            background: dodgerblue;
        }
        input{
            display: inline-block;
            height:30px;
            width: 100px;
            padding: 2px 10px 2px 2px;
            outline: none;
            /*color: #74646e;*/
            color: snow;
            border: 1px solid #C8BFC4;
            border-radius: 4px;
            box-shadow: inset 1px 1px 2px #ddd8dc;
            background: rgba(255,83,25,1);
        }
    </style>
</head>
<body>
<div class="box">
    <div class="table-cell">
        <form action="validar.php" method="post">
            <?php
            /**
             * Created by PhpStorm.
             * User: hmarrufo
             * Date: 20/07/2018
             * Time: 10:21 AM
             */
            include 'conn.php';
            $sql ="SELECT Cadena FROM Tsp_dim_locales WHERE [Abierto/cerrado]='Abierto' AND not Cadena='rincon jumbo' group by Cadena";
            $stmt = sqlsrv_query($conn, $sql);
            echo "<select name='cadena' id='cadenas'>";
            echo '<option value="">- Cadena -</option>';
            while($info=sqlsrv_fetch_array($stmt)){
                unset($name);
                $name = $info['Cadena'];
                echo '<option value="'.$name.'">'.$name.'</option>';
            }
            echo "</select>";
            sqlsrv_free_stmt($stmt);
            include_once 'closeconn.php';
            ?>
            <select id="regiones" name="region">
                <option value="">- Región -</option>
            </select>
            <select id="locales" name="local">
                <option value="0">- Local -</option>
            </select>
            <input type="submit">
        </form>
    </div>
</div>
</body>
</html>