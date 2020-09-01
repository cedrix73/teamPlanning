<?php

include_once '../config.php';
require_once ABS_CLASSES_PATH.$dbFile;
require_once ABS_CLASSES_PATH.'DbAccess.php';
require_once ABS_CLASSES_PATH.'CvfDate.php';
require_once ABS_CLASSES_PATH.'Localisation.php';


/* 
 * Affichage des services en fonction des départements
 * sélectionnées.
 */

$retour = '';   
// Connexion
$dbaccess = new DbAccess($dbObj);
$handler = $dbaccess->connect();
if($handler===FALSE){
    $retour = 'Problème de connexion à la base ';
}else{ 
    $departementLibelle = null;
    $siteId = null;
    if(isset($_REQUEST['site_sel']) 
            && !is_null($_REQUEST['site_sel']) 
            &&  $_REQUEST['site_sel'] == true)
    {
        $siteId = $_REQUEST['site_sel'];
    }

    if(isset($_REQUEST['departement_sel']) 
        && !is_null($_REQUEST['departement_sel']) 
        &&  $_REQUEST['departement_sel'] == true)
    {
        $departementLibelle = $_REQUEST['departement_sel'];
    }

    
// affichage des jours par ressources
    $localisation = new Localisation($dbaccess);
    $tabServices = $localisation->getServicesByDepartement($siteId, $departementLibelle);
    $retour = json_encode($tabServices);
}
echo $retour;


?>
