<?php

include_once '../../../config.php';
require_once ABS_CLASSES_PATH . $dbFile;
require_once ABS_CLASSES_PATH . 'DbAccess.php';
require_once ABS_CLASSES_PATH . 'CvfDate.php';
require_once ABS_PLANNING_PATH . CLASSES_PATH . 'Planning.php';


/* 
 * Création ou modification d'un événement selon la variable javascript 
 * infoRessource.action ={insertion, modification}
 */

$retour = '';   
$isOk = false;

$ressourceId = '';
if(isset($_POST['ressource_id']) && !is_null($_POST['ressource_id']) &&  $_POST['ressource_id'] == true){
    $ressourceId = $_POST['ressource_id'];
    $isOk = true;
}

$activiteSel = '';
if(isset($_POST['activite_sel']) && !is_null($_POST['activite_sel']) &&  $_POST['activite_sel'] == true){
    $activiteSel = $_POST['activite_sel'];
    $isOk = true;
}

$dateDebut = '';
if(isset($_POST['date_debut']) && !is_null($_POST['date_debut']) &&  $_POST['date_debut'] == true){
    $dateDebut = $_POST['date_debut'];
    $isOk = true;
}

$dateFin = '';
if(isset($_POST['date_fin']) && !is_null($_POST['date_fin']) &&  $_POST['date_fin'] == true){
    $dateFin = $_POST['date_fin'];
    $isOk = true;
}

$actionUser = '';
if(isset($_POST['action_user']) && !is_null($_POST['action_user']) &&  $_POST['action_user'] == true){
    $actionUser = $_POST['action_user'];
    $isOk = true;
}

$periode = 1;
if(isset($_POST['periode_sel']) && !is_null($_POST['periode_sel']) &&  $_POST['periode_sel'] == true){
    $periode = $_POST['periode_sel'];
    $isOk = true;
}

if(isset($_POST['old_date_debut']) && !is_null($_POST['old_date_debut']) &&  $_POST['old_date_debut'] == true){
    $oldDateDebut = $_POST['old_date_debut'];
    $isOk = true;
}

if(isset($_POST['old_date_fin']) && !is_null($_POST['old_date_fin']) &&  $_POST['old_date_fin'] == true){
    $oldDateFin = $_POST['old_date_fin'];
    $isOk = true;
}
if($isOk===FALSE){
    $retour = 'Paramètres incorrects';
}

// Connexion
$dbaccess = new DbAccess($dbObj);
$handler = $dbaccess->connect();
if($handler===FALSE){
    $retour = 'Problème de connexion à la base ';
    $isOk = false;
}

if($isOk){
    $retour = '';
    $modification = false;
    $planning = new Planning($dbaccess, $ressourceId, $activiteSel, $dateDebut, $dateFin, $periode, true);
    $planning->setOldDateDebut($oldDateDebut);
    $planning->setOldDateFin($oldDateFin);
    // Est ce qu'on a un evenement pour la même ressource et pour le(s) même(s) jour(s) ?
    $tabActivites = $planning->read();
    // Si tel est le cas, on le(s) modifie
    if(count($tabActivites) > 0){
        $modification = false;
        ?>
        <script>
        $( function() {
        $( "#dialog-confirm" ).dialog({
        resizable: false,
        height: "auto",
        width: 400,
        modal: true,
        buttons: {
            "Oui ": function() {
            $( this ).dialog( "close" );
            },
            Annuler: function() {
            $( this ).dialog( "close" );
            }
        }
        });
    } );
    </script>
        <?php
        //$retour .= "Ce jour est déjà pris !";
        $retour .= '<div id="dialog-confirm" title="Empty the recycle bin?">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Vous allez écraser s\'autres événements vous concernant. Êtes vous sûr ?</p>
      </div>';
    } else {
        try {
            $rs = $planning->update();
            if($rs !== false) {
                $modification = true;
            }else{
                $retour .= "Il y a eu un problème SQL au moment de la modification !";
            }
        }catch(Exception $e){
            $modification = false;
            $retour .= "Il y a eu un problème SQL au moment de la modification !";
        }
    }
    
    
    
    if($modification){
        $retour .= "Modification effectuée avec succès.";
    }else{
        $retour .= " Désolé...";
    }
    //$retour .= $planning->getSql();
}
$dbaccess->close($handler);
echo $retour;


?>
