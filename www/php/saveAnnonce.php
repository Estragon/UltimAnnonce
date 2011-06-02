<?php
    session_start();
    $_SESSION['id_auteur'] = 3;
    require_once ('../../application.php');
    require_once(UA_INCLUDE_DIR.'MyPDO.php');
    require_once(UA_INCLUDE_DIR.'fonctions.php');
    
    /*******  IMPORTANT   :
     *          Pour qu'une annonce soit sauvegarder il faut qu'une session soit ouverte, 
     *          et que l'id de l'auteur soit stocké dedans.  
     */
    
    MyPDO::dbConnect();

    foreach ($_POST as $key => $value){
        if ($key=='titre' || $key=='description' || $key=='id_categorie')
            $champAnn[$key] = $value;
        if(!strncasecmp("champ_" , $key , 6)) {
            $nomChamp = explode("_", $key);
            $champSup[$nomChamp[2]] = array($nomChamp[1] => $value);
        }
    } 
    
    $champAnn['id_auteur'] = $_SESSION['id_auteur'];
    $champAnn['date_crea'] = date(DATE);
    $annonce = new Annonce($champAnn,$champSup);
    $res = $annonce->save();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Creation d'annonce</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
    </head>
    <body>
        <div id="left" >
            <?php include 'menu.php'; ?>
        </div>
        <div id="center">
            <?php
            if($res) echo '<p>L\'annonce a bien été sauvegardé.</p>';
            else echo '<p>Il y a eu un probléme durant la création de l\'annonce, veuillez renter</p>
                <br/>
                <p>Si le probléme persiste, veuillez contacter le webmaster</p>';
            ?>
            <br /><br />
        </div>
    </body>
</html>