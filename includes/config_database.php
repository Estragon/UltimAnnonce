<?php
    define('TABLE' , 'UltimAnnonce');
    define('BASE' , 'localhost');
    define('LOG_BASE' , 'root');
    define('PASS_BASE' , '');
    
    define('CHAMP_CREA_ANNONCE' , '(id,titre,description,date_crea,date_modif,id_categorie,id_auteur)');
    define('CHAMP_AUTEUR' , '(id,login,password)');
    define('CHAMP_CHAMP' , '(id,nom)');
    define('CHAMP_ANNONCE_CHAMP' , '(id,id_annonce,id_champ,valeur)');
    define('CHAMP_CHAMP' , '(id,nom)');
    define('CHAMP_CATEGORIE' , '(id,nom,id_parent)');
    define('CHAMP_MODELE_CHAMP' , '(id,id_champ,id_type,nombre)');
?>
