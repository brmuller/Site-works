<?php

require_once("Manager.php");

class commentManager extends Manager
{

  //insert comment in DB
  public function addComment($task_id,$comment,$creator_id){
    //date_default_timezone_set('Europe/Paris');
    $bdd = $this->connectDB();
		//faire les contrôles de saisie en JS
    $creation_date=date("Y-m-d H:i:s");


    //insert record in table comment
		$insertcomment=$bdd->prepare('INSERT INTO comment(comment,task_id,creator,creation_date) VALUES(?,?,?,?)');
		$insertcomment->execute(array($comment,$task_id,$creator_id,$creation_date));

    //retrieve the task team id
    $req=$bdd->prepare('SELECT team from task WHERE id= ?');
    $req->execute(array($task_id));

    if ($req->rowCount()){
      $row = $req->fetch();
      $team=$row['team'];
    }

    //update history
    $history_manager=new historyManager();
    $history_manager->addEvent($team,'comment_add',$task_id);

    $bdd=null;
    return true;

	}




  //get list of comments attached to task
  public function getTaskComments($task_id){
    $bdd = $this->connectDB();

    //get team id
    $req=$bdd->prepare('SELECT * FROM comment,user WHERE comment.creator=user.id AND task_id= ? ORDER BY creation_date ASC');
    $req->execute(array($task_id));
    $comments=array();
    if ($req->rowCount()){
      while ($row = $req->fetch(PDO::FETCH_ASSOC)) {

        $comments[]=array(
          "text" => $row['comment'],
          "creation_date" => $row['creation_date'],
          "fullname" => $row['fullname'],
          "avatar" => $row['avatar']
        );
      }
    }
    $bdd=null;

    return $comments;
  }

}
