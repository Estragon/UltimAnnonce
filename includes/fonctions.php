<?php

require_once ('../../application.php');
require_once("MyPDO.php");
require_once("class.php");

MyPDO::dbConnect();

/**
 * @global $dbh -> connection a la base
 * @return un tableau contenant la liste des catégories 
 */
function loadCategories(){
    $sql = ('Select * from CATEGORIE');
    global $dbh;
    $query = $dbh->query($sql);
    $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
    return $donnees;
}

/**
 * @global $dbh -> connection a la base
 * @return un tableau contenant la liste des champs 
 */
function loadFields(){
    global $dbh;
    $sql = ('Select * from Champ');
    $query = $dbh->query($sql);
    $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
    return $donnees;
}

/** Transphorme le nombre stocké dans la base en une chaine de caractére 
 * pour plus de lisibilité dasn le code
 *
 * @param $type -> nombre entre 1 et 4
 * @return $type -> chaine de caractère
 */
function switch_type($type){
    switch ($type){
        case 1: $type = 'text';
            break;
        case 2: $type = 'text_long';
            break;
        case 3: $type = 'date';
            break;
        case 4: $type = 'file';
            break;
        default: $type = 'text';
            break;
    }
    return $type;
}

/**
 * @global $dbh -> connection a la base
 * @return un tableau contenant la liste des champs des models
 */
function modelsFieldsList(){
    try{
        global $dbh;
        $sql = ('Select * from  MODELE_CHAMP');
        $query = $dbh->query($sql);
        $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
        $modeles = array();
        foreach ($donnees as $row){
            $modeles[$row['id_modele']][] = array('id_champ' => $row['id_champ'], 'nombre' => $row['nombre']);
        }
    } catch (Exception $e){
        echo $e->getMessage();
        ;
    }
    return $modeles;
}

/**
 * N'est pas en place, il faut revoir la fonction en entier.
 * 
 * Enregistre le fichier immage sur le réseau.
 */
function uploadImages(){
    // Testons si le fichier a bien été envoyé et s'il n'y a pas d'erreur
    if (isset($_FILES['img']) AND $_FILES['img']['error'] == 0)
    {
        // Testons si le fichier n'est pas trop gros
        if ($_FILES['img']['size'] <= SIZE)
        {
            // Testons si l'extension est autorisée
            $infosfichier = pathinfo($_FILES['img']['name']);
            $extension_upload = $infosfichier['extension'];
            $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
            if (in_array($extension_upload, $extensions_autorisees))
            {
                // On peut valider le fichier et le stocker définitivement
                move_uploaded_file($_FILES['img']['tmp_name'], '../../images/profil/profil_' . $_SESSION['login'] . '.' . $infosfichier['extension']);
            } else
            {
                echo 'L\'extention n\'est pas prise en compte. Vous avez le droit d\'up des .jpeg , .jpg , .gif et .png';
            }
        } else
        {
            echo 'Le fichier doit faire moins de ' . size;
        }
    }
}

function loadAnnonceByCat($id){
    $annonces = array();
    global $dbh;
    
    if(!strncasecmp('sousCat' , $id , 7)){
        $id = explode('_', $id);
        $sousCat = loadCategories();
        foreach ($sousCat as $value){
            if($value['id_parent'] == $id[1]){
                $sql = ('Select id from ANNONCE where id_categorie = \'' . $value['id'] . '\'');
                $query = $dbh->query($sql);
                $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach ($donnees as $annonce){
                    $tab = array('id' => $annonce['id']);
                    $annonces[$annonce['id']] = new Annonce($tab);
                }
            }
        }
        $id = $id[1];
    }
    
    if (empty($id))
        $sql = ('Select id from ANNONCE');
    else
        $sql = ('Select id from ANNONCE where id_categorie = \'' . $id . '\'');
    $query = $dbh->query($sql);
    $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($donnees as $annonce)
    {
        $tab = array('id' => $annonce['id']);
        $annonces[$annonce['id']] = new Annonce($tab);
    }
    return $annonces;
}

/**
 *
 * @global $dbh -> connection a la base
 * @param $where 
 * @param $value 
 * 
 * @return $annonces -> un tableau d'objet Annonce qui a pour clef l'id de l'annonce et pour valeur l'objet annonce. 
 */
function getIdAnnonceByAuteur($where,$value){
    global $dbh;
    $sql = ('Select id from ANNONCE where id_auteur = (Select id from Auteur where '.$where.' = \'' . $value . '\' )');
    $query = $dbh->query($sql);
    $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($donnees as $annonce)
    {
        $tab = array('id' => $annonce['id']);
        $annonces[$annonce['id']] = new Annonce($tab);
    }
    return $annonces;
}

/**
 * @global $dbh -> connection a la base
 * @param $titre
 * 
 * @return $annonces -> un tableau d'objet Annonce qui a pour clef l'id de l'annonce et pour valeur l'objet annonce.  -> un tableau d'objet Annonce qui a pour clef l'id de l'annonce et pour valeur l'objet annonce. once 
 */
function getIdAnnonceByTitre($titre){
    global $dbh;
    $sql = ('Select id from ANNONCE where titre = \'' . $titre . '\'');
    $query = $dbh->query($sql);
    $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($donnees as $annonce)
    {
        $tab = array('id' => $annonce['id']);
        $annonces[$annonce['id']] = new Annonce($tab);
    }
    return $annonces;
}

?>