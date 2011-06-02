<?php
    session_start();
    require_once ('../../application.php');
    require_once(UA_INCLUDE_DIR.'MyPDO.php');
    require_once(UA_INCLUDE_DIR.'fonctions.php');
    
    $tab = array('id' => $_POST['id']);
    $annonce= new Annonce($tab);
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
        <script type="text/javascript" src="../js/jquery-1.5.2.min.js"></script>
    </head>
    <body>
        <div id="left" >
            <?php include 'menu.php'; ?>
        </div>
        <div id="center">
            <div id="affiche_annonce">
                <h3><?php echo $annonce->titre; ?></h3>
                <?php 
                echo 'description : <br />'.nl2br($annonce->description);
                foreach ($annonce->champs as $v){
                    foreach ($v as $ke => $va){
                        echo $ke.' : <br />'.$va.'<br/>';
                    }
                }
               echo 'date de création : <br />'.$annonce->date_crea.'<br/>';
               if ($annonce->date_modif) echo 'date de modification : <br />'.$annonce->date_modif.'<br/>';
                ?>
            </div>
        </div>
        <div id="right">
            <form method="post" action="deleteAnnonce.php" >
                <input type="hidden" name="delete"  value="<?php echo $annonce->id ?>"/>
                <input type="submit" value="supprimer l'annonce" /><br />
            </form>
            <form method="post" onclick="alert('en cour de développement.')" >
                <input type="hidden" name="update"  value="<?php echo $annonce->id ?>"/>
                <input type="submit" value="modifier l'annonce" /><br />
            </form>
        </div>
    </body>
</html>