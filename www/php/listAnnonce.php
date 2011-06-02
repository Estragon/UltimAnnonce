<?php
    session_start();
    require_once ('../../application.php');
    require_once(UA_INCLUDE_DIR.'MyPDO.php');
    require_once(UA_INCLUDE_DIR.'fonctions.php');
    
    $annonces = loadAnnonceByCat($_POST['id']);
    $a = false;
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
                <p>il y a <?php echo count($annonces); ?> annonces dans cette catégorie.</p>
                <br /><br />
                <?php foreach ($annonces as $annonce){ ?>
                <div class="affiche_annonce">
                    <h3><?php echo $annonce->titre; ?></h3>
                    <?php echo 'description : <br />'.nl2br($annonce->description);?>
                    <br />
                    <?php
                    if(!empty($_POST['date'])) echo '<span class="date" >date de création : <br />'.$annonce->date_crea.'</span><br />';
                    foreach ($annonce->champs as $v){
                        foreach ($v as $ke => $va){
                            if($ke == 'prix' && !empty($_POST['prix'])) echo '<span class="prix" >'.$ke.' : <br />'.$va.'<br/></span>';
                        }
                    }
                    echo'<form method="Post" action="afficheAnnonce.php" >';
                        echo '<input type="hidden" name="id"  value="'.$annonce->id.'"/>';
                        echo '<input type="submit" value="Plus d\'info" />';
                    echo '</form>';
                echo '</div><br /><br />';
                }?>
        </div>
    </body>
</html>