<?php 
    session_start();
    $_SESSION['id_auteur'] = 3;
    require_once ('../../application.php');
    require_once(UA_INCLUDE_DIR.'MyPDO.php');
    require_once(UA_INCLUDE_DIR.'fonctions.php');
    
    MyPDO::dbConnect();
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
            <p>Recherche d'annonce par : 
                                                <!-- A optimiser pour l'afichage des div -->
            <select id="choice_find" onchange=" document.getElementById('find_auteur').style.display = 'none';
                                                document.getElementById('find_id').style.display = 'none';
                                                document.getElementById('find_titre').style.display = 'none';
                                                document.getElementById($('#choice_find').val()).style.display = 'inline';" >
                <option value="">------</option>
                <option value="find_auteur">Auteur</option>
                <option value="find_id">Id</option>
                <option value="find_titre">Titre</option>
            </select></p>
            <br />
            <div id="recherche" >
                <form id="find_auteur" method="post" action="updateAnnonceList.php" >
                    <p>Entrez le pseudo du créateur : <input type="text" name="pseudo" /></p>
                    <p>Ou</p>
                    <p>Entrez l'adresse mail : <input type="text" name="email" size="55" /></p>
                    <br />
                    <p>Si vous remplissez les deux champs, seul l'email sera prit en compte.</p>
                    <br />
                    <input type="submit" />
                </form>
                <form id="find_id" method="post" action="updateAnnonce.php" >
                    <p>Entrer l'Id de l'annonce : <input type="text" name="id" /></p>
                    <br />
                    <input type="submit" />
                </form>
                <form id="find_titre" method="post" action="updateAnnonce.php" >
                    <p>Entrer le titre de l'annnoce : <input type="text" name="titre" /></p>
                    <br />
                    <input type="submit" />
                </form> 
            </div>
        </div>
        <div id="right">
            
        </div>
    </body>
</html>