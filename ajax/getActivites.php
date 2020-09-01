<?php

include_once '../config.php';
require_once ABS_CLASSES_PATH.$dbFile;
require_once ABS_GENERAL_PATH.'form_functions.php';


/* 
 * Création ou modification d'un événement selon la variable javascript 
 * infoRessource.action ={insertion, modification}
 */

$retour = '';   
$isOk = true;


// Connexion
$dbaccess = new DbAccess($dbObj);
$handler = $dbaccess->connect();
if($handler===FALSE){
    $retour = 'Problème de connexion à la base ';
    $isOk = false;
}

if($isOk){
    $tabActivites = tabLoad('event_libelle', 'event', $dbaccess);
    $strActivites = selectLoad($tabActivites);
    $retour = $strActivites;
}
$dbaccess->close($handler);
echo $retour;
?>
