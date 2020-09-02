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
    $tabChamps = $dbaccess->getTableDatas('ressource');
    $retour = '';
    if (is_array($tabChamps) && count($tabChamps) > 0) {
        $i = 0;
        $numGroupe = 0;
        $nbChampsParLigne = 3;
        $retour .= '<table id="tab_ressources" class= "tab_params">';
        // Liste de tous les types d'événement
        foreach ($tabChamps as $value) {
            $typeChamp = $value['typechamp'];
            $nomChamp = $value['nomchamp'];
            $modulo = intval($i % $nbChampsParLigne );
            //$classeParite = ($numGroupe % 2 == 0 ? 'pair':'impair');
            if ($modulo == 1) {
                $retour .=   '<tr id='.$numGroupe.'>';
                //  class="'.$classeParite.'"
            }
            
            if ($nomChamp == 'site_id') {
                $optionsSite = selectLoad('libelle', 'site', $dbaccess);
                $retour .=  '<td><label for="res_' . $nomChamp . '">Site: </label><select id="res_site" name ="res_site">' . $optionsSite . "</select></td>";

            } elseif ($nomChamp == 'departement_id') {
                $optionsDepartement = selectLoad('libelle', 'departement', $dbaccess);
                $retour .= '<td><label for="res_' . $nomChamp . '">Departement: </label><select id="res_departement" name ="id="res_departement">' . $optionsDepartement . "</select></td>";

            } elseif ($nomChamp == 'service_id') {
                $optionsService = selectLoad('libelle', 'service', $dbaccess);
                $retour .= '<td><label for="res_' . $nomChamp . '">Service: </label><select id="res_service" name="res_service">' . $optionsService . "</select></td>";
            } else {
                $libelleChamp = underscoreToLibelle($nomChamp);
                switch($typeChamp) {
                    case 'varchar':
                        $retour .= '<td><label for="res_' . $nomChamp . '">' . $libelleChamp . '</label>:&nbsp;<input input type="text" id="res_' . $nomChamp .' " name="res_' . $nomChamp .'"
                                . " placeholder="' . $nomChamp . '" maxlength="30" /></td>';
                    break;
                    case 'date':
                        $retour .= '<td><label for="res_' . $nomChamp . '">'. $libelleChamp . '</label>:&nbsp;<input input type="date" id="res_' . $nomChamp .'" name="res_' . $nomChamp .'" 
                                    size="10" maxlength="10" class="champ_date" /></td>';
                    break;
                }
            }
            
            if ($modulo == $nbChampsParLigne || $i >= count($tabChamps)) {
                $retour .="</tr>";
                $numGroupe++;
            }
            if ($i >= count($tabChamps)-1) {
                $retour .= '<tr><td><input type="button" id="validation_ressource" value="Enregistrer" onclick="insererRessource();"/></td></tr>'; 
                $retour .= '</table">';
                
            }
            $i++;
        }
        
        
    }
}
$dbaccess->close($handler);
echo $retour;
?>
