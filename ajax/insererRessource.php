<?php

require_once '../config.php';
require_once ABS_CLASSES_PATH.$dbFile;
require_once ABS_CLASSES_PATH.'DbAccess.php';
require_once ABS_CLASSES_PATH.'Ressource.php';
require_once ABS_GENERAL_PATH.'form_functions.php';
//require_once ABS_GENERAL_PATH.'form_functions.php';


/* 
 * Modification d'un type d'activité donné
 */

$retour = '';   
$isOk = false;




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
$tabJson = '';
$tabInsert = array();

if (isset($_REQUEST['json_datas']) && !is_null($_REQUEST['json_datas']) &&  $_REQUEST['json_datas'] == true) {
    $jsonString = $_REQUEST['json_datas'];
    $isOk = true;
    $tabJson = json_decode($jsonString, true);
    foreach($tabJson as $stdObj) {
        $nomChamp = $stdObj['nom'];
        $nomChampFinal = substr($nomChamp, 4);
        $valeurChamp = $stdObj['valeur'];
        $requiredChamp = isset($stdObj['required']) ? $stdObj['required'] : false;
        $tabInsert[$nomChampFinal] = $valeurChamp;
        switch($nomChampFinal) {
            
        }
    }
    
}else{
    $isOk = false;
}

//var_dump($tabInsert);




// validation champs en backoffice
$insertion = false;
if ($isOk) {
    $ressource = new Ressource($dbaccess);
    $tabInsert = array();

    
    
    
    $insertion = $ressource->create($tabInsert);
    if (!$insertion) {
        $retour = 'Un problème est survenu lors de la création d\'un collaborateur !';
        //$retour.= $activite->getSql();
    } else {
        $retour = 'Votre nouveau collaborateur a été créé.';
    }
}

$dbaccess->close($handler);

//echo utf8_encode($retour);
echo $retour;


?>
