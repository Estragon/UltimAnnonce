var protected_id = 0;
var chaine_champ;
var chaine_search;
var tp;
var conteneur = 'field_sup'; // id de la div ou vont êtres ajouté les champs
var stock = new Array(); // stock les donnée du modele
var i = 0; // indice pour la création du tableau stock

function new_id(){
    return protected_id++;
}

function addIdCat(idCat){
    var id_cat = '<input type="hidden" name="id_categorie" value="'+idCat+'"/></span>';
    $('#'+conteneur).append(id_cat);
}

function addField(label,type,id){
    protec_id = new_id();
    chaine_champ = '<span id="id_'+protec_id+'"><label for="_'+protec_id+'">'+label+' </label><br />\n\
                    <input id="_'+protec_id+'" type="'+type+'" name="champ_'+id+'_'+protec_id+'" size="30" />\n\
                    <button type="button" onclick="document.getElementById(\'id_'+protec_id+'\').innerHTML = \'\';" >X</button><br /></span>';
    $('#'+conteneur).append(chaine_champ);
}

function addField_text(label,id){
    tp = 'text';
    addField(label,tp,id);
}

function addField_date(label,id){
    tp = 'text';
    addField(label,tp,id);
    $( '#_'+protec_id ).datepicker();
}

function addField_file(label,id){
    tp = 'file';
    addField(label,tp,id);
}

function addField_text_long(label,id){
    protec_id = new_id();
    chaine_champ = '<span id="id_'+protec_id+'"><label for="_'+protec_id+'">'+label+' </label>\n\
                    <br /><textarea id="_'+protec_id+'" name="champ_'+id+'_'+protec_id+'" rows="6" cols="40" ></textarea>\n\
                    <button type="button" onclick="document.getElementById(\'id_'+protec_id+'\').innerHTML = \'\';" >X</button><br /></span>';
    $('#'+conteneur).append(chaine_champ);
}

function getType(id){
    var idField;
    var nombre;
    var type;
    protected_id = 0;
    stock = new Array();
    document.getElementById('field_sup').innerHTML = '';
    for(var i=0; i <  modeles["modele_"+id].length; i++) {
        idField = modeles["modele_"+id][i].id_champ; 
        nombre = modeles["modele_"+id][i].nombre
        type = fields[idField]['type'];
        // saveType(type,nombre)
        for(var j=0; j < nombre;j++) {
            switch(type){
                case 'text':addField_text(fields[idField]['label'],fields[idField]['id']);break;
                case 'date':addField_date(fields[idField]['label'],fields[idField]['id']);break;
                case 'file':addField_file(fields[idField]['label'],fields[idField]['id']);break;
                case 'text_long':addField_text_long(fields[idField]['label'],fields[idField]['id']);break;   
            }
        }
    }
    addIdCat(id);
}

function getSousCat(id){
    document.getElementById('cacher').innerHTML = '';
    var select = '<p>Sous catégorie : <select id="blip" name="id" onchange="getType($(\'#blip\').val());" ><option value="sousCat_'+id+'">Toute les sous catégorie</option></select></p>'
    $('#cacher').append(select);
    document.getElementById('cacher').style.display = 'none';
    for(var k=0;k<=cate.length;k++){                 // parcourt de la liste des catégorie
        if(cate[k+1].id_parent == id) {
            document.getElementById('cacher').style.display = 'inline';
            var chaine_select = '<option value="'+cate[k+1].id+'">'+cate[k+1].nom+'</option>';
            $('#blip').append(chaine_select);
        }
    }
}

// pas en place, permet de sauver les champ d'un modele, 
// et à terme de comparer les champs enregistré avec les champs que l'on veut rajouter
function saveType(type,nombre){
    var tab = new Array();
    tab.push(type, nombre);
    stock.push(tab);
    i++;
}

// A mettre en place.
function validation(a){
    // fonction qui test la choérense des données par rapport au type de champ (prix, taille,couleur, ... )
    return a;
}