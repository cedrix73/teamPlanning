<?php

include_once '../config.php';
require_once ABS_CLASSES_PATH.$dbFile;
require_once ABS_CLASSES_PATH.'DbAccess.php';
require_once ABS_CLASSES_PATH.'Event.php';
require_once ABS_GENERAL_PATH.'form_functions.php';
//require_once ABS_GENERAL_PATH.'form_functions.php';


/* 
 * Modification d'un type d'activité donné
 */

$retour = '';   
$isOk = false;

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

$activiteLibelle= '';
if(isset($_REQUEST['activite_libelle']) && !is_null($_REQUEST['activite_libelle']) &&  $_REQUEST['activite_libelle'] == true){
    $activiteLibelle = $_REQUEST['activite_libelle'];
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

$insertion = false;
if($isOk){
    $activite = new Event($dbaccess);
    $tabInsert = array();
    $tabInsert['event_libelle'] = $activiteLibelle;
    $tabInsert['event_couleur'] = $activiteCouleur;
    $tabInsert['event_affichage'] = $activiteAbbrev;
    
    
    $insertion = $activite->create($tabInsert);
    if(!$insertion){
        $retour = 'Un problème est survenu lors de la création du type d\'activité !';
        //$retour.= $activite->getSql();
    }else{
        $retour = 'Votre nouveau type d\'activité a été créé.';
    }
}

$dbaccess->close($handler);
//echo utf8_encode($retour);
echo $retour;


?>