<?php
    session_start();
    require_once ('../../application.php');
    require_once(UA_INCLUDE_DIR.'MyPDO.php');
    require_once(UA_INCLUDE_DIR.'fonctions.php');
    
    $tab = array('id' => $_POST['delete']);
    $annonce= new Annonce($tab);
    
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
    </head>
    <body>
        <div id="left" >
            <?php include 'menu.php'; ?>
        </div>
        <div id="center">
            <?php
                if($annonce->delete()) echo '<p>L\'annonce a bien était supprimé</p>';
                else echo '<p>Il y a eu un probléme durant la suppression de l\'annonce.</p>';
            ?>
        </div>
    </body>
</html>
