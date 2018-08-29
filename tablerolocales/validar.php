<?php
/**
 * Created by PhpStorm.
 * User: hmarrufo
 * Date: 06/08/2018
 * Time: 05:06 PM
 * Crea y divide las variables de session, tambien valida
 */

session_start();
$result = $_POST['local'];
$result_explode = explode(' - ', $result);
$_SESSION['local'] = $result_explode[1];
$_SESSION['numloc'] = $result_explode[0];
//$_SESSION['cadena'] = $result_explode[2];
$_SESSION['cadena'] = $_POST['cadena'];
if(!$_SESSION['local']){
    header('Location: entrada.php');
    die();
}else{
    header("Location: landing.php");
}
?>
