<?php

require_once("config_database.php");

/**
 * Classe qui sert a se connecter a la base de donnée, 
 * elle recupére les données de connection dans le fichier "config_database.php".
 * La modification de ces données se fait donc dans le fichier "config_database.php"
 */
class MyPDO
{
    function dbConnect()
    {
        global $dbh;
        try
        {
            $dbConnString = "mysql:host=" . BASE . "; dbname=" . TABLE;
            $dbh = new PDO($dbConnString, LOG_BASE, PASS_BASE);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            if ($verbose)
            {
                echo 'Erreur : '.$e->getMessage();
            }
            else
            {
                echo 'Ce service est momentanément indisponible. Veuillez nous excuser pour la gêne occasionnée.';
            }
        }
    }
}

/**
 * Classe qui sert a personaliser les exception.
 * Pour l'instant il n'y a pas de pérsonalisation.
 */
class TWAdsException extends Exception
{
    public function  __construct($message, $code=null)
    {
        parent::__construct($message, $code);
    }
}

?>