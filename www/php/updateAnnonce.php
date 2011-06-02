<?php
    session_start();
    require_once ('../../application.php');
    require_once(UA_INCLUDE_DIR.'MyPDO.php');
    require_once(UA_INCLUDE_DIR.'fonctions.php');
    
    $tab = array('id' => $_POST['id']);
    $annonce = new Annonce($tab);
    
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
            <form method="post" >
                <div class="affiche_annonce">
                <?php 
                    echo '<label for="titre">Titre : </label><br />
                    <input id="titre" type="text" name="titre" value="'.$annonce->titre.'" size="55" /><br />'; 
                    echo '<label for="description">Description : </label><br />
                    <textarea id="description" name="description" rows="6" cols="40" >'.$annonce->description.'</textarea><br/>';
                    echo '<div id="field_sup">';
                    if (!empty($annonce->champs)){
                        foreach ($annonce->champs as $id => $value){
                            $tab = array('id' => $id);
                            $valueChamp = new ValeurChamp($tab);
                            $tab = array('id' => $valueChamp->id_champ);
                            $nomChamp = new NomChamp($tab);
        switch ($nomChamp->type){
            case '1' : echo '<span id="id_'.$valueChamp->id.'"><label for="_'.$valueChamp->id.'">'.$nomChamp->label.' : </label><br />
<input id="_'.$valueChamp->id.'" type="text" name="champ_'.$nomChamp->type.'_'.$valueChamp->id.'" size="55" value="'.$valueChamp->valeur.'" />
<button type="button" onclick="document.getElementById(\'id_'.$valueChamp->id.'\').innerHTML = \'\';" >X</button><br /></span>';
                break;
            case '2' : echo '<span id="id_'.$valueChamp->id.'"><label for="_'.$valueChamp->id.'">'.$nomChamp->label.' : </label>
<br /><textarea id="_'.$valueChamp->id.'" name="champ_2_'.$valueChamp->id.'" rows="6" cols="40" >'.$valueChamp->valeur.'</textarea>
<button type="button" onclick="document.getElementById(\'id_'.$valueChamp->id.'\').innerHTML = \'\';" >X</button><br /></span>';
                break;
            case '3' : echo '<span id="id_'.$valueChamp->id.'"><label for="_'.$valueChamp->id.'">'.$nomChamp->label.' : </label><br />
<input id="_'.$valueChamp->id.'" type="text" name="champ_'.$nomChamp->type.'_'.$valueChamp->id.'" onselect="$( \'#_'.$valueChamp->id.'\').datepicker();" size="55"  value="'.$valueChamp->valeur.'" />
<button type="button" onclick="document.getElementById(\'id_'.$valueChamp->id.'\').innerHTML = \'\';" >X</button><br /></span>';
                break;
            case '4' : echo '<span id="id_'.$valueChamp->id.'"><label for="_'.$valueChamp->id.'">'.$nomChamp->label.' : 
<input id="_'.$valueChamp->id.'" type="file" name="champ_'.$nomChamp->type.'_'.$valueChamp->id.'" size="55" />
<button type="button" onclick="document.getElementById(\'id_'.$valueChamp->id.'\').innerHTML = \'\';" >X</button><br /></span>';
                break;

            default : echo '<span id="id_'.$valueChamp->id.'"><label for="_'.$valueChamp->id.'">'.$nomChamp->label.' : </label><br />
<input id="_'.$valueChamp->id.'" type="text" name="champ_'.$nomChamp->type.'_'.$valueChamp->id.'" size="20" value="'.$valueChamp->valeur.'" />
<button type="button" onclick="document.getElementById(\'id_'.$valueChamp->id.'\').innerHTML = \'\';" >X</button><br /></span>';
        }
                        }  
                    }
                    echo '</div>';
                ?>
                </div>
            <input type="submit" onclick="alert('en cours de développement.')" />
            </form>
        </div>
        <div id="right">
            <table>
                <tr><th>CHAMPS</th></tr><?php
                $fields = loadFields();
                foreach ($fields as $key => $value){
                    $type = switch_type($value['type']);
                    $command = "addField_{$type}('{$value['label']}');";
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