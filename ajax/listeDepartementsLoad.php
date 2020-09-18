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
if($handler===false){
    $retour = 'Problème de connexion à la base ';
}else{
    $site = null;
    if(isset($_POST['site_sel']) 
            && !is_null($_POST['site_sel']) 
            &&  $_POST['site_sel'] == true
            && ctype_alnum($_POST['site_sel']))
    {
        $site = $_POST['site_sel'];
    }

    $contexteInsertion = false;
    if(isset($_POST['contexte_insertion']) 
            && !is_null($_POST['contexte_insertion']) 
            &&  $_POST['contexte_insertion'] == true
            && ctype_alnum($_POST['contexte_insertion']))
    {
        $contexteInsertion = $_POST['contexte_insertion'];
    }

    
    // affichage des jours par ressources
    $localisation = new Localisation($dbaccess);
    $tabDepartements = $localisation->getDepartementsBySite($site, $contexteInsertion);
    
    $retour = json_encode($tabDepartements);
}
$dbaccess->close($handler);
echo $retour;


?>
