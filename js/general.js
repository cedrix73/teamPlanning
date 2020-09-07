/* ----------------------------------------------------------------------------
   ----------------------------------------------------------------------------
   DESCRIPTION :                                                         
 * Bibliothèque javascript au format jQuery de fonctions générales  
 * (formulaires, traitement des dates, encodage, REGEXs)
   ----------------------------------------------------------------------------
 * @author : Cédric Von Felten
 * @since  : 28/10/2014
 * @version : 1.3
   --------------------------------------------------------------------------*/


 
 /**
  * Hack de la fonction .text() de jQuery:
  * Elle est réutilisée pour afficher de l'html et
  * decode du texte depuis l'utf-8
  * @param {type} $
  * @param {type} oldHtmlMethod
  * @returns {undefined}
  */
 (function( $, oldHtmlMethod ){
 // Override the core html method in the jQuery object.
     $.fn.text = function(){
     // Check to see if we have an argument (that the user
     // is setting HTML content).
     //
     // NOTE: This does not take into account the fact
     // that we can now pass a function to this method -
     // this is just a lightweight demo.
     if (arguments.length){
     // Prepend our own custom HTML.
     //arguments[ 0 ] = utf8_decode(arguments[ 0 ]);
     }
 // Execute the original HTML method using the
 // augmented arguments collection.
 return(oldHtmlMethod.apply( this, arguments ));
 };
 })( jQuery, jQuery.fn.html ); 

function utf8_decode(chaine){
    //return decodeURIComponent(escape(chaine));
}

function utf8_encode(chaine) {
  return unescape(encodeURIComponent(chaine));
}

/*
 * valid_DatePourComparaison
 * @param {type} strDate
 * @returns {String}
 * DESCRIPTION :
   Convertit la date <strDate> (qui est au format jj/mm/aaaa) au
   format international défini par l'ISO 8601:1988, c'est à dire
   au format "aaaa-mm-jj".
   L'avantage de ce format est qu'il peut être utilisé pour
   la comparaison de dates
 */
function valid_DatePourComparaison(strDate) {

    var datePat = /^(\d{1,2})(\/)(\d{1,2})(\/)(\d{4})$/;
    var matchArray = strDate.match(datePat); // is the format ok?
 
    // parse date into variables
    day = matchArray[1];
    month = matchArray[3]; 
    year = matchArray[5];
    // On ajoute des zéro (éventuellement) devant le jour et le mois
    if (day.length == 1) {
       day = "0" + day;
    }
    if (month.length == 1) {
       month = "0" + month;
    }
    return(year + "-" + month + "-" + day);
    
 }

 function convertPxToInt(chaine){
    return parseInt(chaine.replace("px", ""));
}


function afficherMessage(txt_message, temps=3000){
    $("#message").html("<div><div>" + txt_message + "</div></div");
    $("#message").fadeIn(800).delay( temps );
    $("#message").fadeOut(400);
}



function replaceBlancs(chaine){
    var reg=new RegExp("(---)", "g");
    if(reg.test(chaine)){
        chaine = chaine.replace(reg, " ");
    }
    return chaine;
}

function validerSaisieForm(container_name){
    var div = $("#" + container_name);
    var fields_tab = [];
    var unfilled_required_tab = [];
    var bln_ok = true;
    var label ='';
    var unfilled_required_string ='Les champs suivants sont requis:<ul>';

    var ressourceLabel = '';

    $(div).find('input:text, input:password, input:file, select, textarea')
        .each(function() {
            
            var ressourceObject = new Object();
            ressourceObject.nom = $(this).attr('name');
            ressourceObject.valeur = $(this).val();
            ressourceObject.required = $(this).attr('required');
            ressourceLabel = $(this).prev("label").html();
            if($(this).attr('required') && ($(this).val()===null || $(this).val()==='')){
                bln_ok = false;
                unfilled_required_tab.push(ressourceLabel);
            }else{
                fields_tab.push(ressourceObject);
            }
            
        });
        
        if(!bln_ok){
            $.each(unfilled_required_tab, function(key, value) {
                unfilled_required_string += '<li>' + value + '</li>';
            });
            unfilled_required_string += '</ul>'
            afficherMessage(unfilled_required_string);
            return false;
        }else{
            var json_string = JSON.stringify(fields_tab);
            return json_string;
        }
}


