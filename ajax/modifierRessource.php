<?php

require_once '../config.php';
require_once ABS_CLASSES_PATH.$dbFile;
require_once ABS_CLASSES_PATH.'DbAccess.php';
require_once ABS_CLASSES_PATH.'Ressource.php';
require_once ABS_CLASSES_PATH.'ProcessFormulaires.php';
require_once ABS_GENERAL_PATH.'formFunctions.php';

/* 
 * Sanitization et vérification back-office du formulaire posté
 */

$retour = "";   
$isOk = false;
$msgErr = "";



if ($isOk === false) {
    $retour = 'Paramètres incorrects';
}


// Connexion
$dbaccess = new DbAccess($dbObj);
$handler = $dbaccess->connect();
if($handler===FALSE){
    $retour = "Problème de connexion à la base ";
    $isOk = false;
} else {


    $tabJson = "";
    $tabInsert = array();
    $validationClass = new ProcessFormulaires($dbaccess);

    if (isset($_POST['json_datas']) && !is_null($_POST['json_datas']) &&  $_POST['json_datas'] == true) {
        $isOk = true;
        $jsonString = $_POST['json_datas'];
        $isOk = true;
        $tabJson = json_decode($jsonString, true);
        $isOk = $validationClass->checkForm($tabJson);
        if(!$isOk) {
            $msgErr .= $validationClass->getMsgErreurs();
        }
    } else {
        $isOk = false;
        $msgErr .= "<br>Les données de la ressource sont manquantes.";
    }
    // On a collecté et verifié toutes les données

    $idRessource = '';
    if(isset($_POST['num_res']) && !is_null($_POST['num_res']) &&  $_POST['num_res'] == true &&  ctype_digit($_POST['num_res'])){
        $idRessource = $_POST['num_res'];
        $isOk = true;
    } else {
        $isOk = false;
        $msgErr .= "<br>Erreur: Paramètre manquant ou erroné !";
    }




    if($isOk) {
      // envoyer tableau INSERT en BD 
      $ressource = new Ressource($dbaccess);
      $tabInserts = $validationClass->getTabInsert();
      $retour = $ressource->update($tabInserts, $idRessource);
    } else {
      $retour = $msgErr;
    }
        
    $dbaccess->close($handler);
  }


echo $retour;


?>
