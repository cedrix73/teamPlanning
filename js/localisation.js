/* ----------------------------------------------------------------------------
   ----------------------------------------------------------------------------
   DESCRIPTION :                                                         
 * Bibliothèque javascript pour les localisation sites, departements et 
 * services,ainsi que pour l'enregistrement des ressources. 
 * 
   ----------------------------------------------------------------------------
 * @author : Cédric Von Felten
 * @since  : 28/10/2014
 * @version : 1.3
   --------------------------------------------------------------------------*/




function afficherTypesLocalisation(type){
    $.post("ajax/listeTypesLocalisationLoad.php", {
        type_localisation: ""+type+""}, 
            function(data){
            if(data.length >0) {
                $('#div_saisie_activite').html(data);
                $("#div_saisie_activite").slideDown();
            }
    });
   
}

function insererTypeLocalisation(type){
    var type_localisation  = type;
    var libelle_localisation  = $('#libelle_localisation').val();
    var description_localisation  = $('#description_localisation').val();
    var key_localisation  = $('#key_localisation').val();
    $("#img_loading").show();
    $.post("ajax/insererLocalisation.php", {
        type_localisation: ""+type_localisation+"", 
        libelle_localisation: ""+libelle_localisation+"", 
        description_localisation: ""+description_localisation+"", 
        key_localisation: ""+key_localisation+""}, 
        function(data){ 
            $("#img_loading").hide();
            if(data.length >0) {
                afficherTypesEvents();
                afficherMessage(data);
                document.location.reload(true);
                // @todo
                // appliquer le résultat du patch 1.1.1 de la fonction afficherSaisie
                // directement dans la chaine initialiserFormulaire.saisieActivite
            }
        }       
    );
}


function afficherFormRessources(){
    if($("#div_saisie_activite").css("display") == 'none'){
        var contenuActivite = $("#div_saisie_activite").html();
        $.post("ajax/afficherFormRessources.php", 
             function(data){
                if(data.length >0) {
                    $('#div_saisie_activite').html(data);
                    $("#div_saisie_activite").slideDown();
                }
        });
    }else{
        $("#div_saisie_activite").toggle();
    }
}

function afficherTexteStarter(){
    $.post("ajax/afficherTexteStarter.html", 
            function(data){
            if(data.length >0) {
                $("#planning").append(" <div id=\"guide\" />");
                $('#guide').html(data);
            }
    });
}





