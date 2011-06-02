<?php
    session_start();
    require_once ('../../application.php');
    require_once(UA_INCLUDE_DIR.'MyPDO.php');
    require_once(UA_INCLUDE_DIR.'fonctions.php');
    
    $tab = array('id' => $_POST['id'],
                'pseudo' => $_POST['pseudo'],
                'titre' => $_POST['titre'],
                'email' => $_POST['email']);
    
    if(!empty($tab['id'])) $annonce= new Annonce($tab);
    if(!empty($tab['titre'])) $annonce= getIdAnnonceByTitre($tab['titre']);
    if(!empty($tab['pseudo'])||!empty($tab['email'])){
        if(!empty($tab['email'])){
            $where = 'email';
            $value = $tab['email'];
        }else{
            $where = 'pseudo';
            $value = $tab['pseudo'];
        }
    $annonces= getIdAnnonceByAuteur($where, $value);
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <link rel="stylesheet" type="text/css" href="../css/dot-luv/jquery-ui-1.8.12.custom.css" />
        <script type="text/javascript" src="../js/jquery-1.5.2.min.js"></script>
        <script type="text/javascript" src="../js/jquery-ui-1.8.12.custom.min.js"></script>
        <script type="text/javascript" src="../js/jquery.ui.datepicker-fr.js"></script>
        <script type="text/javascript" src="../js/plan_modele.js.php"></script>
        <script type="text/javascript" src="../js/gestion-fields.js"></script>
    </head>
    <body>
        <div id="left" >
            <?php include 'menu.php'; ?>
        </div>
        <div id="center">
            <?php foreach ($annonces as $annonce){ ?>
            <div class="affiche_annonce">
                <?php 
                echo $annonce->titre.'<br />'; 
                echo 'description : '.nl2br($annonce->description).'<br/>';
                if (!empty($annonce->champs)){
                    foreach ($annonce->champs as $v){
                        foreach ($v as $ke => $va){
                            echo $ke.' : <br />'.$va.'<br/>';
                        }
                    }
                }
                echo 'date de création : <br />'.$annonce->date_crea.'<br/>';
                if ($annonce->date_modif) echo 'dérnière modification le : <br />'.$annonce->date_modif.'<br/>';
                echo'<form method="Post" action="updateAnnonce.php" >';
                    echo '<input type="hidden" name="id"  value="'.$annonce->id.'"/>';
                    echo '<br/><input type="submit" value="Modifier cette annonce"/>';
                echo '</form>';
                ?>
            </div>
            <br />
            <?php } ?>
        </div>
    </body>
</html>