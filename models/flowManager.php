<?php

require_once("Manager.php");

class flowManager extends Manager
{

  //check if flow name already registered
  public function checkFlowExists($team_id,$flow_name){
    $bdd = $this->connectDB();

    $flow_exists=false;
    //check if flow name already exists for the specified team
    $req=$bdd->prepare('SELECT * FROM team,flow WHERE team.id=flow.team_id AND team.id=? AND flow.name= ?');
    $req->execute(array($team_id,$flow_name));

    if ($req->rowCount()){
      $flow_exists=true;
    }

    $bdd=null;
    return $flow_exists;
  }




  //insert a new flow in DB
  public function createFlow(){
    $bdd = $this->connectDB();

    $flow_name=$_POST['flow-name'];
    $team_id=$_POST['flow-team-name'];
    $creator_id=$_SESSION['id'];
    $status_list=explode(";" , $_POST['flow-status-list']);

    //insert record in table flow
    $insertflow=$bdd->prepare('INSERT INTO flow(name,team_id,creator_id) VALUES(?,?,?)');
    $insertflow->execute(array($flow_name,$team_id,$creator_id));
    $flow_id=$bdd->lastInsertId();

    //insert records in table status
    $insertstatus=$bdd->prepare('INSERT INTO status(name,position,id_flow) VALUES(?,?,?)');
    for ($i = 0; $i < count($status_list); $i++){
      $insertstatus->execute(array($status_list[$i],$i,$flow_id));
    }

  }




  //get list of status attached to flow
  public function getStatusList($flow_id){
    $bdd = $this->connectDB();
    $status_list=array();

    //get list of status for the task
    $req=$bdd->prepare('SELECT position, name FROM status WHERE id_flow= ?');
    $req->execute(array($flow_id));
    if ($req->rowCount()){
      while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
        $status_list[$row['position']]=$row['name'];
      }
    }

    $bdd=null;

    return $status_list;

  }




  //get list of flows in the team
  public function getTeamFlows($team_id){

    //insert record in table team
    $bdd = $this->connectDB();
    $req=$bdd->prepare('SELECT id,name FROM flow WHERE team_id= ?');

    $flows=array();
    if ($req->execute(array($team_id))){
      while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
        $flows[]=array(
          "id" => $row['id'],
          "name" => $row['name']
        );
      }
    }
    $bdd=null;

    return $flows;
  }




  //list of flows created by the current user
  public function getUserFlows(){
    if (!isset($_SESSION['id'])){
      throw new Exception("l'id de l'utilisateur n'est pas défini");
    }
    $id_user=$_SESSION['id'];

    //insert record in table team
    $bdd = $this->connectDB();
    $req=$bdd->prepare('SELECT * FROM flow WHERE creator_id= ?');

    $flows_list=array();
    if ($req->execute(array($id_user))){
      while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
        $flows_list[]=$row['name'];
      }
    }
    $bdd=null;

    return $flows_list;
  }

}
