<?php

include_once '../config.php';
require_once ABS_CLASSES_PATH.$dbFile;
require_once ABS_CLASSES_PATH.'DbAccess.php';
require_once ABS_CLASSES_PATH.'Event.php';
//require_once ABS_GENERAL_PATH.'form_functions.php';


/* 
 * Modification d'un type d'activité donné
 */

$retour = '';   
$isOk = false;

$activiteId = '';
if(isset($_REQUEST['activite_id']) && !is_null($_REQUEST['activite_id']) &&  $_REQUEST['activite_id'] == true){
    $activiteId = $_REQUEST['activite_id'];
    $isOk = true;
}

$activiteCouleur = '';
if(isset($_REQUEST['activite_couleur']) && !is_null($_REQUEST['activite_couleur']) &&  $_REQUEST['activite_couleur'] == true){
    $activiteCouleur = $_REQUEST['activite_couleur'];
    $isOk = true;
}

$activiteAbbrev = '';
if(isset($_REQUEST['activite_abbrev']) && !is_null($_REQUEST['activite_abbrev']) &&  $_REQUEST['activite_abbrev'] == true){
    $activiteAbbrev = $_REQUEST['activite_abbrev'];
    $isOk = true;
}

// On enlève le # qu'on ne souhaite pas sauver en base
$activiteCouleur = str_replace('#', '', $activiteCouleur);


if($isOk===FALSE){
    $retour = 'Paramètres incorrects';
}

// Connexion
$dbaccess = new DbAccess($dbObj);
$handler = $dbaccess->connect();
if($handler===FALSE){
    $retour = 'Problème de connexion à la base ';
    $isOk = false;
}

$modification = false;
if($isOk){
    $activite = new Event($dbaccess);
    $modification = $activite->update($activiteId, $activiteCouleur, utf8_decode($activiteAbbrev));
    if(!$modification){
        $retour = 'Un problème est survenu lors de la màj du type d\'activité !';
        $retour.= $activite->getSql();
    }else{
        $retour = 'Màj effectuée !';
    }
}
echo $retour;

$dbaccess->close($handler);
//echo utf8_encode($retour);


?>
