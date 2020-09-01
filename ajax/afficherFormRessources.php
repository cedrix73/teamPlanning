<?php

include_once '../config.php';
require_once ABS_CLASSES_PATH.$dbFile;
require_once ABS_CLASSES_PATH.'DbAccess.php';
require_once ABS_CLASSES_PATH.'Ressource.php';
require_once ABS_GENERAL_PATH.'form_functions.php';


/* 
 * Affichage de tous les types d'activité
 * sélectionnées.
 */

$retour = '';   
// Connexion
$dbaccess = new DbAccess($dbObj);
$handler = $dbaccess->connect();
if ($handler === false) {
    $retour = 'Problème de connexion à la base ';
} else {

    $ressources = new Ressource($dbaccess);
    $tabChamps = array();
    $tabChamps = $ressources->getForm();
    $retour = '';
    if (is_array($tabChamps) && count($tabChamps) > 0) {
        $i = 0;
        $numGroupe = 0;
        $nbChampsParLigne = 4;
        $retour .= '<table id="tab_ressources" class= "tab_params">';
        // Liste de tous les types d'événement
        foreach ($tabActivites as $value) {
            $typeChamp = $value['typechamp'];
            $nomChamp = $value['nomchamp'];
            $modulo = $i % $nbChampsParLigne;
            $classeParite = ($numGroupe == 0 ? 'pair':'impair');
            if ($modulo == 0) {
                $retour .=   '<tr id='.$numGroupe.' class="'.$classeParite.'">';
            }
            if ($nomChamp == 'site') {
                $optionsSite = selectLoad('libelle', 'site', $dbaccess);
                echo '<select id="res_site">' . $optionsSite . "</select>";;

            } elseif ($nomChamp == 'departement_id') {
                $optionsDepartement = selectLoad('libelle', 'departement', $dbaccess);
                echo '<select id="res_departement">' . $optionsDepartement . "</select>";

            } elseif ($nomChamp == 'service_id') {
                $optionsService = selectLoad('libelle', 'service', $dbaccess);
                echo '<select id="res_site">' . $optionsService . "</select>";
            } else {

                switch($typeChamp) {
                    case 'varchar':
                        $retour .= '<td><input input type="text" id="res_' . $nomChamp 
                                . ' " value="' . $nomChamp . '" maxlength="30" /></td>';
                    break;
                    case 'date':
                        $retour .= '<td><input input type="text" id="res_'.$nomChamp 
                                .' size="10" maxlength="30" class="champ_date" /></td>';
                    break;
                }
            }
            
            if ($modulo == 3 || $i >= count($tabChamps)) {
                $retour .="</tr>";
                $numGroupe++;
            }
            if ($i >= count($tabChamps)-1) {
                $retour .= '<\table">';
                $retour .= '<td><input type="button" id="validation_ressource" value="valider" onclick="insererRessource('. $key .');"/></td>';
            }
            $i++;
        } 
        
    }
}
$dbaccess->close($handler);
$retour = utf8_encode($retour);
?>
