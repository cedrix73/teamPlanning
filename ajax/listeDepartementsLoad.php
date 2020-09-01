<?php

include_once '../config.php';
require_once ABS_CLASSES_PATH.$dbFile;
require_once ABS_CLASSES_PATH.'DbAccess.php';
require_once ABS_CLASSES_PATH.'CvfDate.php';
require_once ABS_CLASSES_PATH.'Ressource.php';


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
    if(isset($_REQUEST['site_sel']) 
            && !is_null($_REQUEST['site_sel']) 
            &&  $_REQUEST['site_sel'] == true
            && $_REQUEST['site_sel']!='Tous *'){
        $siteId = $_REQUEST['site_sel'];
    }
    // affichage des jours par ressources
    $ressource = new Ressource($dbaccess);
    $tabDepartements = $ressource->getDepartementsBySite($siteId);
    
    $retour = json_encode($tabDepartements);
}
$dbaccess->close($handler);
echo $retour;


?>
