<?php 
    session_start();
    $_SESSION['id_auteur'] = 3;

    require_once ('../../application.php');
    require_once(UA_INCLUDE_DIR.'MyPDO.php');
    require_once(UA_INCLUDE_DIR.'fonctions.php');
    
    MyPDO::dbConnect();
    
    ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Acceuil</title>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
    </head>
    <body>
        <div id="left" >
            <?php include 'menu.php'; ?>
        </div>
        <div id="center">
            <h2><br/> Welcome in the Ultime Annonce administration</h2>
        </div>
    </body>
</html>
