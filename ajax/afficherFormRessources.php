<?php

include_once '../config.php';
require_once ABS_CLASSES_PATH.$dbFile;
require_once ABS_CLASSES_PATH.'DbAccess.php';
require_once ABS_GENERAL_PATH.'formFunctions.php';
require_once ABS_CLASSES_PATH. 'ProcessFormulaires.php';

/**
 * @name        ProcessFormulaires
 * @author      cvonfelten
 * @description Classe créant et validant les formulaires
 * 
 */

Class RessourceProcessFormulaires extends ProcessFormulaires {

    public function __construct($dbaccess, $tableName = null) 
    {
        parent::__construct($dbaccess, $tableName);
        
    } 

    /**
     * @name           getSpecificFields
     * @description    surcharge la méthode mère avec les listes déroulantes 
     *                 spécifiques à la localisation des ressources. 
     * @param          String $nomChamp :nom du champ à identifier 
     * @param          String $required :Si champ requis: 'required="required' sinon vide. 
     * @return         String   $retour :Section du formulaire au format html
     */
    public function getSpecificFields($nomChamp, $required) {
        $retour = null;
        if ($nomChamp == 'site_id') {
            $optionsSite = selectLoad('libelle', 'site', $this->getDbAccess());
            $retour .=  '<select id="res_site" name ="res_site" '.$required
                    .' alt = "selectionnez un site" onchange="form_departements_load(this.options[this.selectedIndex].value)">' . $optionsSite . "</select>";

        } elseif ($nomChamp == 'departement_id') {
            $optionsDepartement = '';
            $retour .= '<select id="res_departement" name ="res_departement" '.$required
                    .' alt = "selectionnez un département" onchange="form_services_load(res_site.options[res_site.selectedIndex].value, options[this.selectedIndex].value);">' . $optionsDepartement . "</select>";

        } elseif ($nomChamp == 'service_id') {
            $optionsService = '';
            $retour .= '<select id="res_service" name="res_service" '.$required.' alt = "selectionnez un service">' . $optionsService . "</select>";
        }

        return $retour;
    }

}



/**
 * Ce script surcharge la fonction ProcessFormulaires:getFormFromTable
 */

$retour = '';   
// Connexion
$dbaccess = new DbAccess($dbObj);
$handler = $dbaccess->connect();
$retour = null;
if ($handler === false) {
    $retour = 'Problème de connexion à la base ';
} else {

    $ressourceProcessFormulaire = new RessourceProcessFormulaires($dbaccess, 'ressource');
    $retour = $ressourceProcessFormulaire->getFormFromTable('Enregistrement d\'une ressource');
}
$dbaccess->close($handler);
echo $retour;




?>
