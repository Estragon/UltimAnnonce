<?php 
    session_start();
    require_once ('../../application.php');
    require_once(UA_INCLUDE_DIR.'MyPDO.php');
    require_once(UA_INCLUDE_DIR.'fonctions.php');
    
    MyPDO::dbConnect();
    $cat = loadCategories();
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
        <form method="post" action="listAnnonce.php" >
        <div id="center">
                <p>Afficher les annonces de la catégorie : 
                <select id="blop" name="id" onchange="getSousCat($('#blop').val());" >
                    <option value="">Choisissez une Catégorie</option>
                    <?php
                    foreach ($cat as $value){
                        if(empty($value['id_parent']))
                            echo '<option value="'.$value['id'].'">'.$value['nom'].'</option>';
                    }
                    ?>
                </select></p>
                <div id="cacher">
                </div>
                    <br />
                <input type="submit"></input>
        </div>
        <div id="right">
               <input type="button" value="Choix des champs" onclick="document.getElementById('choix').style.display = 'inline';" ></button>
                <br /><br />
                <div id="choix">
                    <input type="checkbox" name="prix">Prix</input><br />
                    <input type="checkbox" name="date">Date de mise en ligne</input><br />
                    <br />
                </div>
        </div>
        </form>
    </body>
</html>