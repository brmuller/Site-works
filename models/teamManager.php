<?php

require_once("Manager.php");

class teamManager extends Manager
{

  //check if team has a public scope
  public function checkPublicTeam($team_name){
    $bdd = $this->connectDB();

    $team_public=false;
    //$req=$bdd->query("SELECT * FROM user");
    $req=$bdd->prepare('SELECT * FROM team WHERE name= ? AND scope=\'public\'');
    $req->execute(array($team_name));

    if ($req->rowCount()){
      $team_public=true;
    }

    $bdd=null;
    return $team_public;
  }




  //check if team name is in DB
  public function checkTeamExists($team_name){
    $bdd = $this->connectDB();

    $team_exists=false;
    //$req=$bdd->query("SELECT * FROM user");
    $req=$bdd->prepare('SELECT * FROM team WHERE name= ?');
    $req->execute(array($team_name));

    if ($req->rowCount()){
      $team_exists=true;
    }

    return $team_exists;
    $bdd=null;
  }




  //check if user is member of a team
  public function checkUserInTeam($team_name,$user_id) {
    $bdd = $this->connectDB();

    $in_team=false;
    //$req=$bdd->query("SELECT * FROM user");
    $req=$bdd->prepare('SELECT * FROM team,user_team WHERE team.id=user_team.id_team AND team.name= ? AND id_user=?');
    $req->execute(array($team_name,$user_id));

    if ($req->rowCount()){
      $in_team=true;
    }

    $bdd=null;
    return $in_team;
  }




  //insert a new team in DB
  public function createTeam(){
    $bdd = $this->connectDB();
		//faire les contrôles de saisie en JS
    $team_name=htmlspecialchars($_POST['team-name']);
    $team_scope=htmlspecialchars($_POST['team-scope']);
    $creator=$_SESSION['id'];

    //insert record in table team
		$insertteam=$bdd->prepare('INSERT INTO team(name,creator,scope) VALUES(?,?,?)');
		$insertteam->execute(array($team_name,$creator,$team_scope));

    //insert record in table user_team
    $id_team=$bdd->lastInsertId();
    $insertuserteam=$bdd->prepare('INSERT INTO user_team(id_user,id_team) VALUES(?,?)');
		$insertuserteam->execute(array($creator,$id_team));
    $bdd=null;
	}




  //get list of members in team
  public function getTeamMembers($team_id){

    //get list of users
    $bdd = $this->connectDB();
    $req=$bdd->prepare('SELECT user.id AS user_id, user.fullname AS user_fullname FROM user,user_team WHERE user.id=user_team.id_user AND user_team.id_team= ?');

    $users=array();
    $req->execute(array($team_id));
    if ($req->rowCount()){
      while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
        $users[]=array(
          "id" => $row['user_id'],
          "fullname" => $row['user_fullname']
        );
      }
    }
    $bdd=null;

    return $users;
  }




  //get list of teams joined by user
  public function getTeams(){
    if (!isset($_SESSION['id'])){
      throw new Exception("l'id de l'utilisateur n'est pas défini");
    }
    $id_user=$_SESSION['id'];

    //insert record in table team
    $bdd = $this->connectDB();
    $req=$bdd->prepare('SELECT team.id AS team_id, team.name AS team_name FROM user_team,team WHERE user_team.id_team=team.id AND id_user= ?');

    $teams_list=array();
    if ($req->execute(array($id_user))){
      while ($row = $req->fetch(PDO::FETCH_ASSOC)) {
        $teams_list[]=array(
          "id" => $row['team_id'],
          "name" => $row['team_name']
        );
      }
    }
    $bdd=null;

    return $teams_list;
  }




  //insert new member in team
  public function joinTeam(){

    $bdd = $this->connectDB();

		//faire les contrôles de saisie en JS
    $team_name=htmlspecialchars($_POST['team-name-join']);
    $user_id=$_SESSION['id'];

    //get id of the team
    $req=$bdd->prepare('SELECT id FROM team WHERE name= ?');
    $req->execute(array($team_name));
    $row = $req->fetch();
    $team_id=$row['id'];

    //insert record in table user_team
		$insertteamuser=$bdd->prepare('INSERT INTO user_team(id_user,id_team) VALUES(?,?)');
		$insertteamuser->execute(array($user_id,$team_id));

    $bdd=null;
	}

}
