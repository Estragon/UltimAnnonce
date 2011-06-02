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
        <title>Creation d'annonce</title>
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
            <form method="post" action="saveAnnonce.php" >
                <label for="titre">Titre : </label><br />
                    <input id="titre" type="text" name="titre" size="55" /><br />
                <label for="description">Description : </label><br />
                    <textarea id="description" name="description" rows="6" cols="40" ></textarea><br />
                <div id="field_sup" >
                </div><br />
                <input type="submit" />
            </form>
        </div>
        <div id="right">
            <form method="post" >
                <p>Categorie : 
                <select id="select_cat" name="id" onchange="getType($('#select_cat').val());getSousCat($('#select_cat').val());" >
                    <option value="">Choisissez une Catégorie</option>
                    <?php
                    foreach ($cat as $value){
                        if(empty($value['id_parent'])){
                            echo '<option value="'.$value['id'].'">'.$value['nom'].'</option>';
                        }
                    }
                    ?>
                </select></p>
                <div id="cacher">
                </div>
            </form>
            <table>
                <tr><th>CHAMPS</th></tr><?php
                $fields = loadFields();
                foreach ($fields as $key => $value){
                    $type = switch_type($value['type']);
                    $command = "addField_{$type}('{$value['label']}','{$value['id']}');";
                    echo '<br />';
                    echo '<tr>
                            <td>
                                <button class="button" type="button" onclick="'.$command.'" >'.$value['nom'].'</button>
                            </td>
                         </tr>';
                }
                ?>
            </table>
            <br /><br />
            <button id="clear" type="button" onclick="document.getElementById('field_sup').innerHTML = '';" >Supprimer tout les champs</button>
        </div>
    </body>
</html>