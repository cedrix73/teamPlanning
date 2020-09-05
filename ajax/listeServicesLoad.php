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
if($handler===false){
    $retour = 'Problème de connexion à la base ';
}else{ 
    $site = null;
    
    
    if(isset($_REQUEST['site_sel']) 
            && !is_null($_REQUEST['site_sel']) 
            &&  $_REQUEST['site_sel'] == true)
    {
        $site = $_REQUEST['site_sel'];
    }

    $departement = null;
    if(isset($_REQUEST['departement_sel']) 
        && !is_null($_REQUEST['departement_sel']) 
        &&  $_REQUEST['departement_sel'] == true)
    {
        $departement = $_REQUEST['departement_sel'];
    }

    $contexteInsertion = false;
    if(isset($_REQUEST['contexte_insertion']) 
            && !is_null($_REQUEST['contexte_insertion']) 
            &&  $_REQUEST['contexte_insertion'] == true){
        $contexteInsertion = true;
    }
    
// affichage des jours par ressources
    $localisation = new Localisation($dbaccess);
    $tabServices = $localisation->getServicesByDepartement($site, $departement, $contexteInsertion);
    $retour = json_encode($tabServices);
}
$dbaccess->close($handler);
echo $retour;


?>
