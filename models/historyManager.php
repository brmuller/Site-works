<?php

require_once("Manager.php");

class historyManager extends Manager
{


  //insert a new event in DB
  public function addEvent($team_id, $event, $object_ref=null){
    //date_default_timezone_set('Europe/Paris');
    $bdd = $this->connectDB();

    $user_id=$_SESSION['id'];
    $creation_date=date("Y-m-d H:i:s");


    //insert record in table task
		$inserttask=$bdd->prepare('INSERT INTO history(team_id,event_type,affected_object_ref,user_id,creation_date) VALUES(?,?,?,?,?)');
		$inserttask->execute(array($team_id,$event,$object_ref,$user_id,$creation_date));

    $bdd=null;
  }




  private function getEventMessage($event_type){
    $message="";
    switch ($event_type) {
      case "task_creation":
        $message="a créé la tâche ";
        break;

      case "task_update":
        $message="a modifié la tâche ";
        break;

      case "team_join":
        $message="a rejoint l'équipe";
        break;

      case "team_update":
        $message="a modifié l'équipe";
        break;

      case "comment_add":
        $message="a commenté la tâche ";
        break;

      case "attach_file":
        $message="a ajouté une pièce jointe à la tâche ";
        break;
    }

    return $message;
  }




  //get list of tasks attached to team
  public function getHistoryList($team_id, $max_events=""){

    $bdd = $this->connectDB();

    //get list of events for the team
    $events_list=array();

    $str_query='SELECT event_type, affected_object_ref, creation_date, fullname, avatar FROM history, user
      WHERE history.user_id=user.id AND team_id=?
      ORDER BY creation_date DESC'. ($max_events !="" ? ' limit '. $max_events : '');

    $req=$bdd->prepare($str_query);
    $req->execute(array($team_id));

    if ($req->rowCount()){
      while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
        $events_list[]=array(
          "event_type" => $row['event_type'],
          "affected_object_ref" => $row['affected_object_ref'],
          "creation_date" => $row['creation_date'],
          "fullname" => $row['fullname'],
          "avatar" => $row['avatar'],
          "message" => $this->getEventMessage($row['event_type']),
          "long_date"=> $this->getFullFrenchDate($row['creation_date'])
        );
      }
    }

    $bdd=null;
    return $events_list;
  }


}
