<?php

include_once '../config.php';
require_once ABS_CLASSES_PATH.$dbFile;
require_once ABS_CLASSES_PATH.'DbAccess.php';
require_once ABS_CLASSES_PATH.'CvfDate.php';
require_once ABS_CLASSES_PATH.'Planning.php';


/* 
 * Affichage des domaines en fonction des services
 * sélectionnées.
 */

$retour = '';   
$isOk = false;

$ressourceId = '';
if(isset($_REQUEST['ressource_id']) && !is_null($_REQUEST['ressource_id']) &&  $_REQUEST['ressource_id'] == true){
    $ressourceId = $_REQUEST['ressource_id'];
    $isOk = true;
}

$activiteSel = '';
if(isset($_REQUEST['activite_sel']) && !is_null($_REQUEST['activite_sel']) &&  $_REQUEST['activite_sel'] == true){
    $activiteSel = $_REQUEST['activite_sel'];
    $isOk = true;
}

$dateDebut = '';
if(isset($_REQUEST['date_debut']) && !is_null($_REQUEST['date_debut']) &&  $_REQUEST['date_debut'] == true){
    $dateDebut = $_REQUEST['date_debut'];
    $isOk = true;
}

$dateFin = '';
if(isset($_REQUEST['date_fin']) && !is_null($_REQUEST['date_fin']) &&  $_REQUEST['date_fin'] == true){
    $dateFin = $_REQUEST['date_fin'];
    $isOk = true;
}

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

if($isOk){
    $insertion = true;
    $planning = new Planning($dbaccess, $ressourceId, $activiteSel, $dateDebut, $dateFin);
    // Est ce qu'on a un evenement pour la même ressource et pour le(s) même(s) jour(s) ?
    $tabActivites = $planning->read();
    // Si tel est le cas, on le(s) supprime
    if(count($tabActivites) > 0){
        $suppression = $planning->delete();
    }
    
    
    if(!$suppression){
        $retour .= 'Problème lors de la suppression';
    }else{
        $retour .= "suppression effectuée avec succès.";
    }
    //$retour .= $planning->getSql();
}
$dbaccess->close($handler);
echo $retour;


?>
