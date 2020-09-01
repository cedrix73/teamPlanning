<?php

require_once '../config.php';
require_once ABS_CLASSES_PATH.$dbFile;
require_once ABS_CLASSES_PATH.'DbAccess.php';
require_once ABS_CLASSES_PATH.'Localisation.php';
require_once ABS_GENERAL_PATH.'form_functions.php';
//require_once ABS_GENERAL_PATH.'form_functions.php';


/* 
 * Modification d'un type d'activité donné
 */

$retour = '';   
$isOk = false;

$typeLocalisation = '';
if (isset($_REQUEST['type_localisation']) && !is_null($_REQUEST['type_localisation']) &&  $_REQUEST['type_localisation'] == true) {
    $typeLocalisation = $_REQUEST['type_localisation'];
    $isOk = true;
}

$libelleLocalisation = '';
if (isset($_REQUEST['libelle_localisation']) && !is_null($_REQUEST['libelle_localisation']) &&  $_REQUEST['libelle_localisation'] == true) {
    $libelleLocalisation = $_REQUEST['libelle_localisation'];
    $isOk = true;
}

$descriptionLocalisation= '';
if (isset($_REQUEST['description_localisation']) && !is_null($_REQUEST['description_localisation']) &&  $_REQUEST['description_localisation'] == true) {
    $descriptionLocalisation = $_REQUEST['description_localisation'];
    $isOk = true;
}
if ($typeLocalisation != 'site') {
    if(isset($_REQUEST['key_localisation']) && !is_null($_REQUEST['key_localisation']) &&  $_REQUEST['key_localisation'] == true) {
        $keyLocalisation = $_REQUEST['key_localisation'];
        $isOk = true;
    }
}


if ($isOk === false) {
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
if ($isOk) {
    $localisation = new Localisation($dbaccess, $typeLocalisation);
    $tabInsert = array();
    $tabInsert['libelle'] = $libelleLocalisation;
    $tabInsert['description'] = $descriptionLocalisation;
    if ($typeLocalisation != 'site') {
        switch($typeLocalisation) {
            case 'departement':
                $tabInsert['site_id'] = $keyLocalisation;
            break;

            case 'site':
                $tabInsert['departement_id'] = $keyLocalisation;
            break;
        }
    }
    
    $insertion = $localisation->create($tabInsert);
    if (!$insertion) {
        $retour = 'Un problème est survenu lors de la création d\'un nouveau ' . $typeLocalisation . ' !';
        //$retour.= $activite->getSql();
    } else {
        $retour = 'Votre nouveau ' . $typeLocalisation . ' a été créé.';
    }
}

$dbaccess->close($handler);

//echo utf8_encode($retour);
echo $retour;


?>
