<?php

include_once '../config.php';
require_once ABS_CLASSES_PATH.$dbFile;
require_once ABS_CLASSES_PATH.'DbAccess.php';
require_once ABS_CLASSES_PATH.'Localisation.php';
require_once ABS_GENERAL_PATH.'form_functions.php';
//require_once ABS_GENERAL_PATH.'form_functions.php';


/* 
 * Affichage de tous les types d'activité
 * sélectionnées.
 */

$retour = '';   
// Connexion
$dbaccess = new DbAccess($dbObj);
$handler = $dbaccess->connect();
if($handler === false){
    $retour = 'Problème de connexion à la base ';
    $isOk = false;
}else{

    $typeLocalisation = '';
    if(isset($_POST['type_localisation']) 
        && !is_null($_POST['type_localisation']) 
        &&  $_POST['type_localisation'] == true
        && ctype_alnum($_POST['type_localisation']))
    {
        $typeLocalisation = $_POST['type_localisation'];
        $isOk = true;
    }
    if( !$isOk) 
    {
        $retour = "paramètres incorrects";
        
    } else {
        $localisation = new Localisation($dbaccess, $typeLocalisation);
        $tabLocalisation = array();
        $tabLocalisation = $localisation->getAll();
        $retour = '';
        
        $retour .= '<table id="tab_localisations" class= "tab_params">';
        $retour .= '<th>libellé</th><th>description</th><th>modification</th>';
        if ($typeLocalisation != 'site') {
            $retour .= '<th>catégorie</th>';
        }
        $typeSuperieur = ($typeLocalisation == 'departement' ? 'site' : 'departement');
        $typeSuperieurId = $typeSuperieur . '_id';
        $tabOptions = tabLoad('libelle', $typeSuperieur, $dbaccess);

        $classeParite = 'pair';

        if (is_array($tabLocalisation) && count($tabLocalisation) > 0 ) {
            $i = 1;
            // Liste de tous les types d'événement
            foreach ($tabLocalisation as $key => $value) {
                $id = $value['id'];
                $classeParite = ($i%2 == 0 ? 'pair':'impair');
                $retour .=   '<tr id='.$id.' class="'.$classeParite.'">';
                $retour .= '<td id="libelle_' . $id . '"><input type="text" class="legende_activite" disabled value="' . $value['libelle'] . '" /></td>';
                $retour .= '<td><input type="text" id="description_' . $id . '" disabled value="'.$value['description'].'" maxlength="250" /></td>';

                
                if ($typeLocalisation != 'site') {
                    // combobox des options liés à la clé secondaire  avec la bonne valeur sélectionnée
                    $options = getOptionsFromTab($tabOptions, $value[$typeSuperieurId]);
                    $retour .= '<td><select id ="key_' . $id . '"  disabled >' . $options . '</select></td>';
                }


                $retour .= '<td><input type="button" id="' . $id . '_validation_ligne" disabled value="valider" onclick="modifierTypeLocalisation('. $id .');"/></td>';
                $retour .="</tr>";
                $i++;
            } 
        }
        // Ajout d'un nouveau type d'événement
        $retour .=   '<tr id="newLine" class="'.$classeParite.'">';
        $retour .= '<td><input type="text" id="libelle_localisation" value="" /> </td>';
        $retour .= '<td><input type="text" id="description_localisation" value="" maxlength="250" /></td>';
        $typeSuperieur = null;
        if ($typeLocalisation != 'site') {
            $typeSuperieur = ($typeLocalisation == 'departement' ? 'site' : 'departement');
            $options = selectLoad('libelle', $typeSuperieur, $dbaccess);
            $options = getOptionsFromTab($tabOptions);
            $retour .= '<td><select id = "key_localisation">' . $options . '</select></td>';
        }
        $retour .= '<td><input id="new_validation" type="button" value="ajouter" onclick="insererTypeLocalisation(\'' . $typeLocalisation . '\');"/></td>';
        $retour .="</tr>";
        $retour .= '</table>';
        //$retour = utf8_encode($retour);
    
    

        ?><script>$(".choix_couleur").colorpicker({
            strings: "Couleurs variées,Couleurs de base,+ de couleurs,- de couleurs,Palette,Historique,Pas encore d'historique."
        });</script><?php
    }
}

$dbaccess->close($handler);
//echo utf8_encode($retour);
echo $retour;


?>
