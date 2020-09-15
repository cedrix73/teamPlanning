<?php

require_once '../config.php';
require_once ABS_CLASSES_PATH.$dbFile;
require_once ABS_CLASSES_PATH.'DbAccess.php';
require_once ABS_CLASSES_PATH.'Ressource.php';
require_once ABS_GENERAL_PATH.'form_functions.php';

/* 
 * Sanitization et vérification back-office du formulaire posté
 */

$retour = '';   
$isOk = false;
$arrayErr = array();



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

if (isset($_REQUEST['json_datas']) && !is_null($_REQUEST['json_datas']) &&  $_REQUEST['json_datas'] == false) {
    $isOk = false;
} else {
    $jsonString = $_REQUEST['json_datas'];
    $isOk = true;
    $tabJson = json_decode($jsonString, true);
    foreach($tabJson as $stdObj) {
        $nomChamp = $stdObj['nom'];
        $nomChampFinal = substr($nomChamp, 4);
        $valeurChamp = $stdObj['valeur'];
        $typeChamp = $stdObj['type'];
        $labelChamp = $stdObj['label'];
        $requiredChamp = isset($stdObj['required']) ? $stdObj['required'] : false;

        if(empty($valeurChamp)) {
            if($requiredChamp) {
              $isOk = false;
              $arrayErr[$nomChamp] = "Le champ " . $labelChamp . " est obligatoire.";
            }
        } else {
        
            switch($typeChamp) {
                case 'email':
                  $valeurChamp = filter_var($valeurChamp, FILTER_SANITIZE_EMAIL);
                  if(!filter_var($valeurChamp, FILTER_VALIDATE_EMAIL)) {
                    $isOk = false;
                    $arrayErr[$nomChamp] = "Le champ " . $labelChamp . " n'a pas une adresse email valide.";
                  }
                break;

                case 'text':
                case 'select-one':
                    $valeurChamp = filter_var($valeurChamp, FILTER_SANITIZE_STRING);
                    
                    if($labelChamp == "nom" || $labelChamp == "prenom") {
                        if (!preg_match("/^[a-zA-Z-\s' ]*$/", $valeurChamp)) {
                          $arrayErr[$nomChamp] = "Seul les lettres et les espaces sont authorisés pour le champ " . $labelChamp;
                          $isOk = false;
                        }
                    }
                    

                break;

                case 'date':
                    if (!preg_match("/^(\d{4})(-)(\d{1,2})(-)(\d{1,2})$/", $valeurChamp)) {
                      $arrayErr[$nomChamp] = "Seul le format date aaaa-mm-jj est authorisé pour le champ " . $labelChamp;
                      $isOk = false;
                    }
                break;

                case 'tel':
                  $valeurChamp = filter_var($valeurChamp, FILTER_SANITIZE_NUMBER_INT);
                  if (!preg_match("/^[0-9]{9,}$/", $valeurChamp)) {
                    $arrayErr[$nomChamp] = "Seul les chifres sont authorisés pour le champ " . $labelChamp;
                    $isOk = false;
                  }
                break;

                case 'num':
                  $valeurChamp = filter_var($valeurChamp, FILTER_SANITIZE_NUMBER_INT);
                  if(!filter_var($valeurChamp, FILTER_VALIDATE_INT)) {
                    $isOk = false;
                    $arrayErr[$nomChamp] = "Le champ " . $labelChamp . "ne contient pas de valeurs numériques.";
                  }
                break;

                default:
                  // select, radios

                break;
            }


        }



        if($isOk) {
          $tabInsert[$nomChampFinal] = $valeurChamp;
        }
        
    }

    // On a collecté et verifié toutes les données
    if($isOk) {
      // envoyer donnees en BD INSERT
    } else {
      return $arrayErr;
    }
    

    
}


  




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
