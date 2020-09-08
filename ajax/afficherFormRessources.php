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
        
        $retour .= '<div class="legende_titre"><h1>Enregistrement d\'un collaborateur</h1></div>';
        $retour .= '<form action="">'; 
        $retour .= '<div id="panel_ressource" name = "panel_ressource"><table id="tab_ressources" class= "tab_params">';
        // Liste de tous les types d'événement
        foreach ($tabChamps as $value) {
            $typeChamp = $value['typechamp'];
            $nomChamp = $value['nomchamp'];
            $isNullable = $value['is_nullable'];
            $modulo = intval($i % $nbChampsParLigne );
            if ($modulo == 1) {
                $retour .=   '<tr id='.$numGroupe.'>';
                //  class="'.$classeParite.'"
            }
            $retour .= '<td>';
            $libelleChamp = underscoreToLibelle($nomChamp);
            // label
            $retour .= '<label for="res_' . $nomChamp . '">' . $libelleChamp . '</label>:&nbsp;';
            $required = ($isNullable == 'NO' ? 'required="required"' : '');
            

            // parsing champs
            if ($nomChamp == 'site_id') {
                $optionsSite = selectLoad('libelle', 'site', $dbaccess);
                $retour .=  '<select id="res_site" name ="res_site" '.$required
                        .' onchange="form_departements_load(this.options[this.selectedIndex].value)">' . $optionsSite . "</select>";

            } elseif ($nomChamp == 'departement_id') {
                //$optionsDepartement = selectLoad('libelle', 'departement', $dbaccess);
                $optionsDepartement = '';
                $retour .= '<select id="res_departement" name ="res_departement" '.$required
                        .' onchange="form_services_load(res_site.options[res_site.selectedIndex].value, options[this.selectedIndex].value);">' . $optionsDepartement . "</select>";

            } elseif ($nomChamp == 'service_id') {
                //$optionsService = selectLoad('libelle', 'service', $dbaccess);
                $optionsService = '';
                $retour .= '<select id="res_service" name="res_service" '.$required.'>' . $optionsService . "</select>";
                
            } elseif (strstr($nomChamp, 'mail') == true) {
                $retour .= '<input type="email" id="res_' . $nomChamp .' " name="res_' . $nomChamp .'"
                         ' . $required . ' placeholder="' . $nomChamp . '" maxlength="30" onchange="verifEmail($(this).attr(\'name\'));"/>';
            }else {
                switch($typeChamp) {
                    case 'varchar':
                        $retour .= '<input type="text" id="res_' . $nomChamp .' " name="res_' . $nomChamp .'"
                                ' . $required . ' placeholder="' . $nomChamp . '" maxlength="30" />';
                    break;
                    case 'date':
                        $retour .= '<input type="date" id="res_' . $nomChamp .'" name="res_' . $nomChamp .'" 
                        ' . $required . ' size="10" maxlength="10" class="champ_date" />';
                    break;
                }
            }
            $retour .= '</td>';
            $retour .= '<td id="res_' . $nomChamp . '_img" name ="res_' . $nomChamp . '_img">&nbsp;</td>';
            
            if ($modulo == $nbChampsParLigne || $i >= count($tabChamps)) {
                $retour .="</tr>";
                $numGroupe++;
            }
            if ($i >= count($tabChamps)-1) {
                $retour .= '<tr><td><input type="submit" id="validation_ressource" value="Enregistrer" onclick="validerSaisieRessource();"/></td></tr>'; 
                $retour .= '</table"></div>';
            }
            $i++;
        }
        
        
    }
    $retour .= '</form>';
}
$dbaccess->close($handler);
echo $retour;
?>
