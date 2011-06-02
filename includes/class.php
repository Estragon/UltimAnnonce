<?php

/**
 * Pour la création d'un objet annonce, 
 * il faut lui donner en paramétre un tableau de forme array( 'id' => 'valeur voulut' ).
 */
class Annonce{
    public $id = null;
    public $titre = null;
    public $description = null;
    public $date_crea = null;
    public $date_modif = null;
    public $id_categorie = null;
    public $id_auteur = null;
    public $champs = null; 

    public static $params_model = array(
        'id','titre','description','date_crea','date_modif','id_categorie','id_auteur'
    );
    
    public function __construct($params=array(),$champs=array()){
        foreach ($params as $key => $value)
            if (in_array($key, self::$params_model))
                $this->$key = $value;
        
        $this->champs = $champs;

        if ($this->id!=null){
            $this->load();
            $this->loadFieldsAnnonce();
        }
    }

    /** Charge l'annonce depuis la base dans l'objet grace à son id.
     * 
     * @global type $dbh permet de se connecter a la base.
     */
    public function load(){
        try{
            global $dbh;
            $sql = ('Select * from ANNONCE where id = "' . $this->id . '"');
            $query = $dbh->query($sql);
            $donnees = $query->fetch(PDO::FETCH_ASSOC);
            if($donnees){
                $this->id = $donnees['id'];
                $this->titre = $donnees['titre'];
                $this->description = $donnees['description'];
                $this->date_crea = $donnees['date_crea'];
                $this->date_modif = $donnees['date_modif'];
                $this->id_categorie = $donnees['id_categorie'];
                $this->id_auteur = $donnees['id_auteur'];
            }
            else throw new TWAdsException('l\'annonce n\'existe pas<br />',404);
        }
        catch (Exception $e){
            echo $e->getMessage();;
        }
    }
    
    /**Choisi entre update ou insert
     * 
     * @return boolean qui donne true si la function a bien fait sont travail, ou false dans le cas contraire. 
     */
    public function save(){
        if($this->id == null) $bool = $this->insert();
        else $bool = $this->update();
        
        return $bool;
    }

    /**Ajoute une annonce avec les valeur de l'objet.
     * charge le nouvel id, et recharge les champs avec les nouveaux id.
     * 
     * @global type $dbh permet de se connecter a la base
     * @return boolean qui donne true si l'annonce ajouté et false dans le cas contraire.
     */
    public function insert(){
        try{
            global $dbh;
            $sql = ('Insert into ANNONCE ' . CHAMP_CREA_ANNONCE . ' VALUES (NULL , "' . $this->titre . '", "' . $this->description . '", "'.$this->date_crea.'", NULL, "' . $this->id_auteur . '", "' . $this->id_categorie . '") ');
            $query = $dbh->exec($sql);
            $this->id = $dbh->lastInsertId();

            if(!empty ($this->champs)){
                foreach ($this->champs as $champs){
                    foreach ($champs as $id_champ => $valeur){
                        $tib = array( 'id_champ' => $id_champ,'valeur' => $valeur,'id_annonce' => $this->id );
                        $chp = new ValeurChamp($tib);
                        $chp->save();
                    }
                }
            }
            $this->loadFieldsAnnonce();
            $res = true;
        }
        catch (Exception $e){
            echo 'Erreur : '. $e->getMessage();
            $res = false;
        }
        return $res;
    }

    /**Modifie l'annonce avec les valeur de l'objet.
     *
     * @global type $dbh permet de se connecter a la base
     * @return boolean qui donne true si l'annonce est modifié et false dans le cas contraire.
     */
    public function update(){
        try{
            global $dbh;
            $sql = ('UPDATE ANNONCE set titre = "' . $this->titre . '" ,description = "' . $this->description . '" ,date_modif = "' . date(DATE) . '" where id = "' . $this->id . '" ');
            $query = $dbh->exec($sql);
            if(!empty ($this->champs)){
                foreach ($this->champs as $key => $value){
                    $res = $value;
                    $id = $key;
                    $chp = new ValeurChamp($id);
                    foreach ($res as $key => $value){
                        $chp->nom = $key;
                        $chp->valeur = $value;
                    }
                    $chp->updateValeur();
                }
            }
            $res = true;
        }
        catch (Exception $e){
            echo 'Erreur : '. $e->getMessage();
            $res = false;
        }
        return $res;
    }

    /**Charge les champs depuis la base dans l'objet.
     *
     * @global type $dbh permet de se connecter a la base.
     */
    public function loadFieldsAnnonce(){
        try{
            global $dbh;
            $sql = ('Select ANNONCE_CHAMP.id,ANNONCE_CHAMP.valeur,CHAMP.nom
                from ANNONCE_CHAMP join CHAMP
                on ANNONCE_CHAMP.id_champ = CHAMP.id
                where id_annonce = "' . $this->id . '"');
            $query = $dbh->query($sql);
            $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($donnees){
                foreach ($donnees as $value){
                    $tab[$value['id']] = array($value['nom'] => $value['valeur']);
                }
                $this->champs = $tab;
            }
        }
        catch (Exception $e){
            echo $e->getMessage();;
        }
    }
    
    /** Permet d'ajouter un champs , cette fonction n'est pas encore utilié.
     *
     * @global type $dbh
     * @param type $i
     * @param type $val
     * @param type $id_champ 
     */
    public function addfield($i,$val,$id_champ){   
        global $dbh;
        $sql = ('SELECT id FROM Annonce_champ order by id desc limit 0,1');
        $query = $dbh->query($sql);
        $donnees = $query->fetch(PDO::FETCH_ASSOC);
        foreach ($donnees as $value)
            $id = $value+$i;
        
        $chps = 'champ';
        $this->champs[$id] = array($id_champ => $val);
    }
    
    /** Permet de delete une annonce ainsi que les champs annonce_champs qui lui sont associé
     *
     * @global type $dbh permet de se connecter a la base.
     * @return boolean true = annonce bien delete, false = annonce pas delete.
     */
    public function delete(){
        try{
            global $dbh;
            if (!empty($this->champs))
            {
                /* Pour chaque valeur du tableau $champ, 
                 * on créer un nouvel objet ValuerChamp pour le supprimer enssuite.
                 */
                foreach ($this->champs as $id => $value){
                    $tib = array('id'=>$id);
                    $chp = new ValeurChamp($tib);
                    $chp->delete();
                }
            }
            /* Une fois que la suppression des champs et bien faite, on supprime l'annonce.
             */
            $sql = ('DELETE from ANNONCE where id = "'.$this->id.'"');
            $query = $dbh->exec($sql);
            
            $res = true;
        }
        catch (Exception $e){
            echo 'Erreur : '. $e->getMessage();
            $res = false;
        }
        return $res;
    }
}

/**
 * Pour la création d'un objet ValeurChamp, 
 * il faut lui donner en paramétre un tableau de forme $params_model.
 */
class ValeurChamp{
    public $id = null;
    public $id_champ = null;
    public $valeur = null;
    public $id_annonce = null;
    
    public static $params_model = array( 'id','valeur','id_annonce','id_champ' );

    public function __construct($params=array()){
        foreach ($params as $key => $value)
            if (in_array($key, self::$params_model))
                $this->$key = $value;

        if ($this->id!=null){
            $this->load();
        }
    }

    public function load(){
        try{
            global $dbh;
            $sql = ('select * from annonce_champ where id = "' . $this->id . '"');
            $query = $dbh->query($sql);
            $donnees = $query->fetch(PDO::FETCH_ASSOC);
            if ($donnees){               
                $this->id = $donnees['id'];
                $this->valeur = $donnees['valeur'];
                $this->id_champ = $donnees['id_champ'];
                $this->id_annonce = $donnees['id_annonce'];
            }
            else throw new TWAdsException('le champ n\'existe pas<br />',1);
        
            $res = true;
        }
        catch (Exception $e){
            echo $e->getMessage();
            $res = false;
        }
        return $res;
    }

    public function save(){
        if($this->id == null){ $this->insert();$res = true; }
        else { $this->update(); $res = false; }
    }
    
    public function insert(){
        try{
            global $dbh;
            $sql = ('Insert into ANNONCE_CHAMP ' . CHAMP_ANNONCE_CHAMP . ' VALUES (NULL , "' . $this->id_annonce . '", "' . $this->id_champ . '", "' . $this->valeur . '") ');
            $query = $dbh->exec($sql);    
            $this->id = $dbh->lastInsertId();        
            $res = true;
        }
        catch (TWAdsException $e){
            throw new TWAdsException('Imposible d\'éxacuter las requéte<br />',3);
            $res = false;
        }
        return $res;
    }

    public function update(){
        try{
            global $dbh;
            $sql = ('UPDATE ANNONCE_CHAMP set valeur = "' . $this->valeur . '" where id = "' . $this->id . '" ');
            $query = $dbh->exec($sql);
            $res = true;
        }
        catch (TWAdsException $e){
            throw new TWAdsException('Imposible d\'éxacuter las requéte<br />',3);
            $res = false;
        }
        return $res;
    }

    public function delete(){
        try{
            global $dbh;
            $sql = ('DELETE from ANNONCE_CHAMP where id = "'.$this->id.'"');
            $query = $dbh->exec($sql);        
            $res = true;
        }
        catch (TWAdsException $e){
            throw new TWAdsException('Impossible de supprimer<br />',4);
            $res = false;
        }
        return $res;
    }
}

/**
 * Pour la création d'un objet NomChamp, 
 * il faut lui donner en paramétre un tableau de forme $params_model.
 */
class NomChamp{
    public $id = null;
    public $nom = null;
    public $label = null;
    public $type = null;
    
    public static $params_model = array( 'id','nom','label','type' );

    public function __construct($params=array()){
        foreach ($params as $key => $value)
            if (in_array($key, self::$params_model))
                $this->$key = $value;

        if ($this->id!=null){
            $this->load();
        }
    }
    
    function load(){
        try{
            global $dbh;
            $sql = ('Select * from CHAMP where id = "' . $this->id . '"');
            $query = $dbh->query($sql);
            $donnees = $query->fetch(PDO::FETCH_ASSOC);
            if (!empty($donnees)){
                $this->nom = $donnees['nom'];
                $this->label = $donnees['label'];
                $this->type= $donnees['type'];
            }
            else throw new TWAdsException('le champ n\'existe pas<br />',2);
        
            $res = true;
        }
        catch (Exception $e){
            echo $e->getMessage();
            $res = false;
        }
        return $res;
    }
    
    function save(){
        if(empty ($this->id) || $this->id == NULL) { $this->insert(); $res = true; }
        else { $this->update(); $res = false; }
        return $res;
        }
    
    function insert(){
        try{
            global $dbh;
            $sql = ('Insert into CHAMP ' . CHAMP_CHAMP . ' VALUES (NULL , "' . $this->nom . '") ');
            $query = $dbh->exec($sql);
            $this->id = $dbh->lastInsertId();
            $res = true;
        }
        catch (TWAdsException $e){
            throw new TWAdsException('Imposible d\'éxecuter la requéte<br />',3);
            $res = false;
        }
        return $res;
    }
    
    function update(){
        try{
            global $dbh;
            $sql = ('update CHAMP set nom = "'.$this->nom.'" where id = "'.$this->id.'"');
            $query = $dbh->exec($sql);
            $res = true;
        }
        catch (TWAdsException $e){
            throw new TWAdsException('Imposible d\'éxecuter la requéte<br />',3);
            $res = false;
        }
        return $res;
    }
    
    public function delete(){
        try{
            global $dbh;
            $sql = ('DELETE from CHAMP where id = "'.$this->id.'"');
            $query = $dbh->exec($sql);
            $res = true;
        }
        catch (TWAdsException $e){
            throw new TWAdsException('Impossible de supprimer<br />',4);
            $res = false;
        }
        return $res;
    }
}

/**
 * Pour la création d'un objet Categorie, 
 * il faut lui donner en paramétre un tableau de forme $params_model.
 */
class Categorie
{
    public $id = null;
    public $nom = null;
    public $id_modele = null;
    public $id_parent = null;
    
    public static $params_model = array( 'id','nom','id_parent' );

    public function __construct($params=array()){
        foreach ($params as $key => $value)
            if (in_array($key, self::$params_model))
                $this->$key = $value;

        if ($this->id!=null){
            $this->load();
        }
    }
    
    function load(){
        try{
            global $dbh;
            $sql = ('Select * from categorie where id = "' . $id . '"');
            $query = $dbh->query($sql);
            $donnees = $query->fetch(PDO::FETCH_ASSOC);
            if ($donnees){
                $this->nom = $donnees['nom'];
                $this->id_parent = $donnees['id_parent'];
                $this->id_modele = $donnees['id_modele'];
            }
            else throw new TWAdsException('la categorie n\'existe pas<br />',5);
            
            $res = true;
        }
        catch (Exception $e){
            echo $e->getMessage();
            $res = false;
        }
    }
    
    function save(){
        if(empty ($this->id) || $this->id == NULL) { $this->insert(); $res = true; }
        else { $this->update(); $res = false; }
        return $res;
    }
    
    function insert(){
        try{
            global $dbh;
            $sql = ('Insert into CATEGORIE ' . CHAMP_CATEGORIE . ' VALUES (NULL , "' . $this->nom . '","' . $this->id_parent . '") ');
            $query = $dbh->exec($sql);
            $this->id = $dbh->lastInsertId();
            $res = true;
        }
        catch (TWAdsException $e){
            throw new TWAdsException('Imposible d\'éxacuter las requéte<br />',3);
            $res = false;
        }
    return $res;
    }
    
    function update(){
        try{
            global $dbh;
            $sql = ('update CATEGORIE set nom = "'.$this->nom.'", id_parent = "'.$this->id_parent.'" where id = "'.$this->id.'"');
            $query = $dbh->exec($sql);
            $res = true;
        }
        catch (TWAdsException $e){
            throw new TWAdsException('Imposible d\'éxacuter las requéte<br />',3);
            $res = false;
        }
        return $res;
    }
    
    public function delete(){
        try{
            global $dbh;
            $sql = ('DELETE from CATEGORIE where id = "'.$this->id.'"');
            $query = $dbh->exec($sql);
            $res = true;
        }
        catch (TWAdsException $e){
            throw new TWAdsException('Impossible de supprimer<br />',4);
            $res = false;
        }
        return $res;
    }
}

/**
 * Pour la création d'un objet Model, 
 * il faut lui donner en paramétre un tableau de forme $params_model.
 */
class Modele // revoir par rapport au ModeleValeur
{
    public $id = null ;
    public $nom = null;
    public $champs = array(); 
    
   public static $params_model = array( 'id','nom' );

    public function __construct($params=array(),$champs = array()){
        foreach ($params as $key => $value)
            if (in_array($key, self::$params_model))
                $this->$key = $value;
            $this->champs = $champs;

        if ($this->id!=null){
            $this->load();
            $this->loadFields();
        }
    }
    
    public function load(){
        try{
            global $dbh;
            $sql = ('Select * from MODELE where id = "' . $this->id . '"');
            $query = $dbh->query($sql);
            $donnees = $query->fetch(PDO::FETCH_ASSOC);
            if (!empty($donnees)){
                $this->nom = $donnees['nom'];
            }
            else throw new TWAdsException('le type n\'existe pas<br />',5);
            $res = true;
        }
        catch (Exception $e){
            echo $e->getMessage();
            $res = false;
        }
        return $res;
    }
    
    public function save(){
        if(empty ($this->id) || $this->id == NULL){ $this->insert(); $res = true; }
        else { $this->update(); $res = false; }
        return $res;
    }
    
    public function insert(){
        try{
            global $dbh;
            $sql = ('Insert into MODELE ' . CHAMP_MODELE . ' VALUES (NULL , "' . $this->nom . '") ');
            $query = $dbh->exec($sql);

            if ($this->champs != array()){
                foreach ($this->champs as $key => $value){
                    $tp = new ValeurModele(array());
                    $val = $value;
                    foreach ($val as $key => $value){
                        $tp->id_champ = $key;
                        $tp->nombre = $value;
                    }
                    $tp->insert();
                }
            }
            $res = true;
        }
        catch (TWAdsException $e){
            throw $e('Impossible d\'executer la requete');
            $res = false;
        }
        return $res;
    }

    public function update(){
        try{
            global $dbh;
            $sql = ('UPDATE MODELE set nom = "' . $this->nom . '" where id = "' . $this->id . '" ');
            $query = $dbh->exec($sql);
            
            if ($this->champs != array()){
                foreach ($this->champs as $key => $value){
                    $tp = new ValeurType(array('id'=>$key));
                    $val = $value;
                    foreach ($val as $key => $value){
                        $tp->id_champ = $key;
                        $tp->nombre = $value;
                    }
                    $tp->update();
                }
            }
            $res = true;
        }
        catch (Exception $e){
            throw $e('Impossible d\'executer la requete');
            $res = false;
        }
        return $res;
    }

    public function loadFields(){
        try{
            global $dbh;
            $sql = ('Select * from MODELE_CHAMP where id_type = "' . $this->id . '"');
            $query = $dbh->query($sql);
            $donnees = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($donnees as $value){
                $this->champs[$value['id']] = array('id_champ' => $value['id_champ'], 'nombre' => $value['nombre']);
            }
            $res = true;
        }
        catch (Exception $e){
            throw $e('Impossible d\'executer la requete');
            $res = false;
        }
        return $res;
    }
    
    public function delete(){
        try{
            foreach ($this->champs as $key => $value){
                $chp->deleteNom();
            }
            global $dbh;
            $sql = ('DELETE from MODELE where id = "'.$this->id.'"');
            $query = $dbh->exec($sql);
            $res = true;
        }
        catch (TWAdsException $e){
            throw $e('Impossible de supprimer');
            $res = false;
        }
        return $res;
    }
}

/**
 * Pour la création d'un objet ValeurModel, 
 * il faut lui donner en paramétre un tableau de forme $params_model.
 */
class ValeurModele
{
    public $id_champ = null;
    public $id_type = null;
    public $nombre = null;
    
    public static $params_model = array( 'id_type','id_champ','nombre' );

    public function __construct($params=array()){
        foreach ($params as $key => $value)
            if (in_array($key, self::$params_model))
                $this->$key = $value;

        if (empty($this->nombre)){
            $this->load();
        }
    }
    
    public function load(){
        try{
            global $dbh;
            $sql = ('Select * from MODELE_CHAMP where id_champ = "' . $this->id_champ . '" AND id_type = "' . $this->id_type . '" ');
            $query = $dbh->query($sql);
            $donnees = $query->fetch(PDO::FETCH_ASSOC);
            if (!empty($donnees)){
                $this->nombre = $donnees['nombre'];
            }
            else throw new TWAdsException('le type n\'existe pas<br />',5);
            $res = true;
        }
        catch (Exception $e){
            echo $e->getMessage();
            $res = false;
        }
        return $res;
    }
    
    public function insert(){
        try{
            global $dbh;
            $sql = ('Insert into MODELE_CHAMP ' . CHAMP_MODELE_CHAMP . ' VALUES ("' . $this->id_champ . '", "' . $this->id_type . '", "' . $this->nombre . '") ');
            $query = $dbh->exec($sql);
        }
        catch (TWAdsException $e){
            throw new TWAdsException('Imposible d\'éxecuter la requéte<br />',3);
        }
    }
    
    public function update(){
        try{
            global $dbh;
            $sql = ('update MODELE_CHAMP set nombre = "'.$this->nombre.'" where id_champ = "'.$this->id_champ.'" AND id_type = "'.$this->id_type.'"');
            $query = $dbh->exec($sql);
            $res = true;
        }
        catch (TWAdsException $e){
            throw new TWAdsException('Imposible d\'éxecuter la requéte<br />',3);
            $res = false;
        }
        return $res;
    }
    
    public function delete(){
        try{
            global $dbh;
            $sql = ('DELETE from MODELE_CHAMP where id = "'.$this->id.'"');
            $query = $dbh->exec($sql);
            $res = true;
        }
        catch (TWAdsException $e){
            throw new TWAdsException('Impossible de supprimer<br />',4);
            $res = false;
        }
        return $res;
    }
}
?>