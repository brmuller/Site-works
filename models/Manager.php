<?php
require("config.php");

class Manager
{
  protected function connectDB(){
    try{
      $db = new PDO(DB_SOURCE, DB_USER, DB_PWD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }catch (PDOException $e) {
      die($e->getMessage());
    }

    return $db;
  }




  protected function getFullFrenchDate($str_date){
    $nom_jour_fr = array("dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi");
    $mois_fr = array("", "janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août",
            "septembre", "octobre", "novembre", "décembre");

    // on extrait la date du jour
    list($nom_jour, $jour, $mois, $annee) = explode('/', date("w/d/n/Y",strtotime($str_date)));
    return $nom_jour_fr[$nom_jour].' '.$jour.' '.$mois_fr[$mois].' '.$annee;
  }




  //returns the interval between two dates
  public function dateDiff($date1, $date2){
    $diff = abs(strtotime($date1) - strtotime($date2)); // abs pour avoir la valeur absolute, ainsi éviter d'avoir une différence négative
    $retour = array();

    $tmp = $diff;
    $retour['second'] = $tmp % 60;

    $tmp = floor( ($tmp - $retour['second']) /60 );
    $retour['minute'] = $tmp % 60;

    $tmp = floor( ($tmp - $retour['minute'])/60 );
    $retour['hour'] = $tmp % 24;

    $tmp = floor( ($tmp - $retour['hour'])  /24 );
    $retour['day'] = $tmp;

    return $retour;
  }

}
