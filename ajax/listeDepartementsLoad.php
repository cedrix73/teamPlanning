<?php

include_once '../config.php';
require_once ABS_CLASSES_PATH.$dbFile;
require_once ABS_CLASSES_PATH.'DbAccess.php';
require_once ABS_CLASSES_PATH.'CvfDate.php';
require_once ABS_CLASSES_PATH.'Localisation.php';


/* 
 * Affichage des domaines en fonction des départements
 * sélectionnées.
 */

$retour = '';   
// Connexion
$dbaccess = new DbAccess($dbObj);
$handler = $dbaccess->connect();
if($handler===FALSE){
    $retour = 'Problème de connexion à la base ';
}else{
    $siteId = null;
    if(isset($_REQUEST['site']) 
            && !is_null($_REQUEST['site']) 
            &&  $_REQUEST['site'] == true
            && $_REQUEST['site']!='Tous *'){
        $siteId = $_REQUEST['site'];
    }
    // affichage des jours par ressources
    $localisation = new Localisation($dbaccess);
    $tabDepartements = $localisation->getDepartementsBySite($siteId);
    
    $retour = json_encode($tabDepartements);
}
$dbaccess->close($handler);
echo $retour;


?>
