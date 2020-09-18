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
    
    
    if(isset($_POST['site_sel']) 
            && !is_null($_POST['site_sel']) 
            &&  $_POST['site_sel'] == true)
    {
        $site = $_POST['site_sel'];
    }

    $departement = null;
    if(isset($_POST['departement_sel']) 
        && !is_null($_POST['departement_sel']) 
        &&  $_POST['departement_sel'] == true)
    {
        $departement = $_POST['departement_sel'];
    }

    $contexteInsertion = false;
    if(isset($_POST['contexte_insertion']) 
            && !is_null($_POST['contexte_insertion']) 
            &&  $_POST['contexte_insertion'] == true){
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
