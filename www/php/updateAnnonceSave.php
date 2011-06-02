<?php

session_start();
    $_SESSION['id_auteur'] = 3;
    require_once ('../../application.php');
    require_once(UA_INCLUDE_DIR.'MyPDO.php');
    require_once(UA_INCLUDE_DIR.'fonctions.php');
    
    MyPDO::dbConnect();

    echo $_POST['hidden_champ_4_170'];echo '<br />';
    echo $_POST['hidden_champ_4_171'];echo '<br />';
    
print_r($_POST);echo '<br />';
    
    
    foreach ($_POST as $key => $value){
        if ($key=='titre' || $key=='description' || $key=='id_categorie')
            $champAnn[$key] = $value;
        if(!strncasecmp("champ_" , $key , 6)) {
            $nomChamp = explode("_", $key);
            $champSup[$nomChamp[2]] = array($nomChamp[1] => $value);
        }
        if(!strnatcasecmp("hidden_" , $key)){
            $nomChamp = explode("_", $key);
            print_r($nomChamp);echo '<br />';
            $champSup[$nomChamp[3]] = array($nomChamp[2] => $value);
        }
    } 
    
    $champAnn['date_modif'] = date(DATE);
    $ann = new Annonce($champAnn,$champSup);
    print_r($ann);echo '<br />';
    
    
/*
foreach ($this->champs as $id => $value){
    $tib = array('id'=>$id);
    $chp = new ValeurChamp($tib);
    $chp->delete();
}
 * 
 */
?>