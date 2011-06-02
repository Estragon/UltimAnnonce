<?php 
    header('Content-type: text/javascript; charset=utf-8');
    
    ini_set('html_errors', 0);
    require_once ('../../application.php');
    require_once(UA_INCLUDE_DIR.'fonctions.php');
    
    
    // Création d'une variable js contenant les la liste des champs des modeles
    $modeles = modelsFieldsList();
    $modeles_js = array();
    foreach($modeles as $k => $v) $modeles_js['modele_'.$k] = $v; 
    echo 'var modeles = '.json_encode($modeles_js).';';
        
    
    // Création d'une variable js contenant les champs 
    //      avec les noms des type à la place des numéros de type
    $fields = loadFields();
    $fields_js = array();
    foreach($fields as $k => $v)    $fields_js[$k+1] = $v;
    foreach ($fields_js as $key => &$value) {
        $value['type'] = switch_type($value['type']);
    }
    echo 'var fields = '.json_encode($fields_js).';';
    
    
    // Création d'une variable js contenant les catégories
    $cate = loadCategories();
    echo 'var cate = '.json_encode($cate).';';
?>