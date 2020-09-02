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

function liste_departements_load(site_sel){
    $.ajax({
        type: "get",
        url: "ajax/listeDepartementsLoad.php",
        data: "site=" + site_sel,
        datatype: "json",
        success: function(data)
        {
            var tab_elems = [];
            tab_elems.push('<option value="Tous *">Tous *</option>');
            var str_feedback = jQuery.parseJSON(data);
            $.each(str_feedback, function(cle, valeur) {
                tab_elems.push('<option value="' + valeur + '">' + valeur + '</option>');
            });
            $("#cbo_departements").html(tab_elems.join(''));

        }
    });
}

function liste_services_load(site_sel, departement_sel){
    $.ajax({
        type: "POST",
        url: "ajax/listeServicesLoad.php",
        data: {"site_sel": site_sel, "departement_sel":departement_sel },
        datatype: "json",
        success: function(data)
        {
            var tab_elems = [];
            tab_elems.push('<option value="Tous *">Tous *</option>');
            var str_feedback = jQuery.parseJSON(data);
            $.each(str_feedback, function(cle, valeur) {
                tab_elems.push('<option value="' + valeur + '">' + valeur + '</option>');
            });
             $("#cbo_services").html(tab_elems.join(''));

        }
    });
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





