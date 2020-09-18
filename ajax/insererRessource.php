<?php

require_once '../config.php';
require_once ABS_CLASSES_PATH.$dbFile;
require_once ABS_CLASSES_PATH.'DbAccess.php';
require_once ABS_CLASSES_PATH.'Ressource.php';
require_once ABS_GENERAL_PATH.'form_functions.php';

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
}


$tabJson = "";
$tabInsert = array();

if (isset($_POST['json_datas']) && !is_null($_POST['json_datas']) &&  $_POST['json_datas'] == false) {
    $isOk = false;
} else {
    $jsonString = $_POST['json_datas'];
    $isOk = true;
    $tabJson = json_decode($jsonString, true);
    try {
        foreach($tabJson as $stdObj) {
            $nomChamp = $stdObj['nom'];
            $nomChampFinal = substr($nomChamp, 4);
            $valeurChamp = $stdObj['valeur'];
            $typeChamp = $stdObj['type'];
            $labelChamp = $stdObj['label'];
            $requiredChamp = isset($stdObj['required']) ? $stdObj['required'] : false;

            // On ne prend pas en compte les champs vides
            if(empty($valeurChamp)) {
              // ... sauf s'ils sont obligatoires
                if($requiredChamp) {
                  $isOk = false;
                  $msgErr .= "Erreur: Le champ " . $labelChamp . " est obligatoire.";
                }
            } else {
            
                switch($typeChamp) {
                    case 'email':
                      $valeurChamp = filter_var($valeurChamp, FILTER_SANITIZE_EMAIL);
                      if(!filter_var($valeurChamp, FILTER_VALIDATE_EMAIL)) {
                        $isOk = false;
                        $msgErr .= "Erreur: Le champ " . $labelChamp . " n'a pas une adresse email valide.";
                      }
                    break;

                    case 'text':
                        $valeurChamp = filter_var($valeurChamp, FILTER_SANITIZE_STRING);
                        if($nomChampFinal == "nom" || $nomChampFinal == "prenom") {
                            if (!preg_match("/^[a-zA-Z-\séèàüöñøå' ]*$/", $valeurChamp)) {
                              $msgErr .= "Erreur: Seul les lettres et les espaces sont authorisés pour le champ " . $labelChamp;
                              $isOk = false;
                            }
                        }
                    break;

                    case 'select-one':
                      $valeurChamp = filter_var($valeurChamp, FILTER_SANITIZE_NUMBER_INT);
                      $nomChampFinal .= '_id';
                    break;

                    case 'date':
                        if (!preg_match("/^(\d{4})(-)(\d{1,2})(-)(\d{1,2})$/", $valeurChamp)) {
                          $msgErr .= "Erreur: Seul le format date aaaa-mm-jj est authorisé pour le champ " . $labelChamp;
                          $isOk = false;
                        }
                    break;

                    case 'tel':
                      $valeurChamp = filter_var($valeurChamp, FILTER_SANITIZE_NUMBER_INT);
                      if (!preg_match("/^[0-9]{9,}$/", $valeurChamp)) {
                        $msgErr .= "Erreur: Seul les chifres sont authorisés pour le champ " . $labelChamp;
                        $isOk = false;
                      }
                    break;

                    case 'num':
                      $valeurChamp = filter_var($valeurChamp, FILTER_SANITIZE_NUMBER_INT);
                      if(!filter_var($valeurChamp, FILTER_VALIDATE_INT)) {
                        $isOk = false;
                        $msgErr .= "Erreur: Le champ " . $labelChamp . "ne contient pas de valeurs numériques.";
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
    } catch (Exception $e) {
      echo "Erreur: Une erreur s'est produite lors de l'enregistrement du champ " . $labelChamp;
      $dbaccess->close($handler);
      exit();
    }


}


// On a collecté et verifié toutes les données
if(!$isOk) {
  $retour = $msgErr;
} else {
  // envoyer tableau en BD INSERT
  
  $ressource = new Ressource($dbaccess);
  $retour = $ressource->create($tabInsert);
}
    
$dbaccess->close($handler);
//echo utf8_encode($retour);
echo $retour;


?>
