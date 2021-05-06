/* ----------------------------------------------------------------------------
   ----------------------------------------------------------------------------
   DESCRIPTION :                                                         
 * Bibliothèque javascript de gestion des ressources de team planning  
 * 
   ----------------------------------------------------------------------------
 * @author : Cédric Von Felten
 * @since  : 28/10/2016
 * @version : 1.3
   --------------------------------------------------------------------------*/


   function afficherFormRessources(id=null){
    if( $("#affichage_activite").val() == 'form_ressources'){
        $("#div_saisie_activite").toggle();
        $("#affichage_activite").val("");
    } else {
        $.ajax({
            type: "post",
            url: "/teamplanning/ajax/afficherFormRessources.php", 
            data: {"res_id": id},
            dataype: "json",
            success: function(data)
            {
                if(data.length >0) {
                    $('#div_saisie_activite').html(data);
                    $("#div_saisie_activite").slideDown();
                    $("#affichage_activite").val("form_ressources");
                }
                
            },
            error: function(message)
            {
                afficherMessage(message);
            }
        });
        
    }

    
}

function form_departements_load(site_sel){
    $.ajax({
        type: "post",
        url: "/teamplanning/ajax/listeDepartementsLoad.php",
        data: {"site_id": site_sel, "contexte_insertion": true},
        datatype: "json",
        success: function(data)
        {
            var tab_elems = [];
            var str_feedback = jQuery.parseJSON(data);
            $.each(str_feedback, function(cle, valeur) {
                tab_elems.push('<option value="' + cle + '">' + valeur + '</option>');
            });
            $("#res_departement").html(tab_elems.join(''));
            form_services_load(site_sel, $("#res_departement").val());


        },
        error: function(message)
        {
            afficherMessage(message);
        }
    });
}

function form_services_load(site_sel, departement_sel){
    $.ajax({
        type: "POST",
        url: "/teamplanning/ajax/listeServicesLoad.php",
        data: {"site_sel": site_sel, "departement_sel":departement_sel, "contexte_insertion": true},
        datatype: "json",
        success: function(data)
        {
            var tab_elems = [];
            var str_feedback = jQuery.parseJSON(data);
            $.each(str_feedback, function(cle, valeur) {
                tab_elems.push('<option value="' + cle + '">' + valeur + '</option>');
            });
             $("#res_service").html(tab_elems.join(''));

        }
    });
}

function infoRessource(nom, prenom){
    infoRessource.nom = replaceBlancs(nom);
    infoRessource.prenom = replaceBlancs(prenom);
    $("#lgd_saisie_activite").text("Saisie d'absence de <i>" + infoRessource.prenom + " " + infoRessource.nom + "</i>");
}

function validerSaisieRessource(num_res){
    var json_string = validerSaisieForm("panel_ressource");
    if(json_string !== false && json_string !==undefined){
        $("#img_loading").show();
        $.post("/teamplanning/ajax/insererRessource.php", {
            json_datas: json_string}, 
            function(data){
                $("#img_loading").hide();
                if(data.length >0) {
                    if(data.substr(0, 7) !== 'Erreur:') {
                        $("#div_saisie_activite").slideUp(2000).delay( 2000 ).fadeOut( 1000 );
                        initialiserFormulaire();
                    }
                    afficherMessage(data);
                }
            }       
        );
    }
}


function validerModificationRessource(num_res){
    var json_string = validerSaisieForm("panel_ressource");
    if(json_string !== false && json_string !==undefined){
        $("#img_loading").show();
        $.post("/teamplanning/ajax/modifierRessource.php", 
        {
            json_datas: json_string,
            num_res: num_res
        }, 
            function(data){
                $("#img_loading").hide();
                if(data.length >0) {
                    if(data.substr(0, 7) !== 'Erreur:') {
                        $("#div_saisie_activite").slideUp(2000).delay( 2000 ).fadeOut( 1000 );
                        initialiserFormulaire();
                    }
                    afficherMessage(data);
                }
            }       
        );
    }
}




